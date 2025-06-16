<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getConversationWithSeller($sellerId)
    {
        $user = Auth::user();
        $seller = User::findOrFail($sellerId);

        if (!$seller->hasRole('seller')) {
            return response()->json(['error' => 'Invalid seller.'], 403);
        }

        if ($user->id === $seller->id) {
            return response()->json(['error' => 'Cannot chat with yourself.'], 400);
        }

        $conversation = Conversation::firstOrCreate(
            [
                'customer_id' => $user->id,
                'seller_id' => $seller->id,
            ]
        );

        return response()->json($conversation->load(['customer', 'seller', 'messages.sender']));
    }

    public function getMessages($conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($query) use ($user) {
                $query->where('customer_id', $user->id)
                      ->orWhere('seller_id', $user->id);
            })
            ->firstOrFail();

        // Mark messages as read
        $conversation->messages()
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($conversation->messages()->with('sender')->get());
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate(['body' => 'required|string']);

        $user = Auth::user();
        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($query) use ($user) {
                $query->where('customer_id', $user->id)
                      ->orWhere('seller_id', $user->id);
            })
            ->firstOrFail();

        $receiverId = ($conversation->customer_id === $user->id) ? $conversation->seller_id : $conversation->customer_id;

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'body' => $request->body,
        ]);

        $conversation->update(['last_message_id' => $message->id]);

        // Broadcast the new message event
        broadcast(new \App\Events\NewMessageSent($message->load('sender')))->toOthers();

        return response()->json($message->load('sender'));
    }

    public function sellerConversations()
    {
        $seller = Auth::user();
        if (!$seller->hasRole('seller')) {
            abort(403, 'Only sellers can access this page.');
        }

        $conversations = Conversation::where('seller_id', $seller->id)
            ->with(['customer', 'lastMessage.sender'])
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('conversation_id', 'conversations.id')
                    ->latest()
                    ->take(1)
            )
            ->paginate(15);

        return view('seller.chat.index', compact('conversations'));
    }
}
