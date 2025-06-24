<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class OrderCompletedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;

        // Create database notification
        $this->createDatabaseNotification();
    }

    private function createDatabaseNotification()
    {
        try {
            $kantinName = $this->order->orderItems->first()->menu->kantin->name ?? 'Kantin';

            Notification::create([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\OrderCompletedNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->order->user_id,
                'data' => [
                    'title' => 'Pesanan Selesai!',
                    'message' => "Pesanan #{$this->order->id} dari {$kantinName} telah selesai dan siap diambil.",
                    'icon_class' => 'bi-check-circle-fill',
                    'icon_color_class' => 'text-success',
                    'link' => route('user.pesanan.show', $this->order->id),
                    'time_ago' => 'Baru saja',
                    'order_id' => $this->order->id,
                    'kantin_name' => $kantinName
                ],
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create database notification for order completed: ' . $e->getMessage(), [
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id
            ]);
        }
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->order->user_id);
    }

    public function broadcastAs()
    {
        return 'order.completed';
    }
}
