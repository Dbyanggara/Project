<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public User $sender;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->sender = $message->sender;
    }

    public function broadcastOn(): array
    {
        return [
            // Channel untuk percakapan spesifik
            new PrivateChannel('chat.' . $this->message->conversation_id),

            // Channel personal untuk penerima (notifikasi)
            new PrivateChannel('App.Models.User.' . $this->message->receiver_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message' => $this->message->message,
            'conversation_id' => $this->message->conversation_id,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
            ]
        ];
    }
}
