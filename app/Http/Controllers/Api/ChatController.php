<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatMessageSent;
use Illuminate\Support\Facades\Validator;
use App\Events\MessageRead;

class ChatController extends Controller
{
    public function fetchMessages(Request $request, $receiver)
    {
        \Log::info('fetchMessages called', [
            'auth_user_id' => Auth::id(),
            'receiver_id' => $receiver
        ]);

        $user = Auth::user();
        if (!$user) {
            \Log::warning('Unauthorized access to fetchMessages');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Cari receiver berdasarkan ID
        $receiverUser = \App\Models\User::find($receiver);
        if (!$receiverUser) {
            \Log::warning('Receiver not found in fetchMessages', ['receiver_id' => $receiver]);
            return response()->json(['error' => 'Receiver not found'], 404);
        }

        $conversationId = Message::generateConversationId($user->id, $receiverUser->id);

        $messages = Message::where('conversation_id', $conversationId)
            ->with('sender:id,name')
            ->orderBy('created_at', 'asc')
            ->get();

        \Log::info('Messages fetched', [
            'conversation_id' => $conversationId,
            'messages_count' => $messages->count()
        ]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        \Log::info('sendMessage called', [
            'auth_user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            \Log::warning('Validation failed in sendMessage', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        if (!$user) {
            \Log::warning('Unauthorized access to sendMessage');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $receiverId = $request->input('receiver_id');

        if ($user->id == $receiverId) {
            return response()->json(['error' => 'You cannot send message to yourself.'], 400);
        }

        $conversationId = Message::generateConversationId($user->id, $receiverId);

        try {
            $message = Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'message' => $request->input('message'),
                'conversation_id' => $conversationId,
            ]);

            $message->load('sender');

            \Log::info('Message created successfully', [
                'message_id' => $message->id,
                'conversation_id' => $conversationId
            ]);

            broadcast(new ChatMessageSent($message))->toOthers();

            return response()->json($message->load('sender:id,name'), 201);
        } catch (\Exception $e) {
            \Log::error('Error creating message', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Mengambil daftar seller (user dengan role seller)
     */
    public function listSellers()
    {
        $sellers = \App\Models\User::role('seller')->select('id', 'name')->get();
        return response()->json($sellers);
    }

    /**
     * Mengambil daftar user (user dengan role user)
     */
    public function listUsers()
    {
        $currentUser = Auth::user();
        $users = \App\Models\User::role('user')
            ->where('id', '!=', $currentUser ? $currentUser->id : 0)
            ->select('id', 'name')
            ->get();
        return response()->json($users);
    }

    /**
     * Mengambil daftar user yang sudah chat dengan seller tertentu
     */
    public function listUsersForSeller()
    {
        $seller = Auth::user();

        if (!$seller || !$seller->hasRole('seller')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Ambil user yang sudah pernah chat dengan seller ini
        $users = \App\Models\User::role('user')
            ->where('id', '!=', $seller->id)
            ->where(function($query) use ($seller) {
                $query->whereHas('sentMessages', function($q) use ($seller) {
                    $q->where('receiver_id', $seller->id);
                })
                ->orWhereHas('receivedMessages', function($q) use ($seller) {
                    $q->where('sender_id', $seller->id);
                });
            })
            ->select('id', 'name')
            ->distinct()
            ->get();

        return response()->json($users);
    }

    public function markAsRead(Request $request, User $receiver)
    {
        $user = Auth::user();
        $conversationId = Message::generateConversationId($user->id, $receiver->id);

        // Update semua pesan yang belum dibaca dari pengirim ke penerima
        $messages = Message::where('conversation_id', $conversationId)
            ->where('sender_id', $receiver->id)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->get();

        foreach ($messages as $message) {
            $message->read_at = now();
            $message->save();

            // Broadcast event bahwa pesan telah dibaca
            broadcast(new MessageRead($message))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }

    public function getSellerDetails(User $seller)
    {
        if (!$seller->hasRole('seller')) {
            return response()->json(['error' => 'User bukan penjual'], 404);
        }

        return response()->json([
            'id' => $seller->id,
            'name' => $seller->name
        ]);
    }
}
