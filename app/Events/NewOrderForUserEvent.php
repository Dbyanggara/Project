<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NewOrderForUserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
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
            $paymentMethod = strtoupper($this->order->payment_method ?? 'COD');

            Notification::create([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\NewOrderNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->order->user_id,
                'data' => [
                    'title' => 'Pesanan Berhasil Dibuat!',
                    'message' => "Pesanan #{$this->order->id} dari {$kantinName} telah dibuat dengan pembayaran {$paymentMethod}.",
                    'icon_class' => 'bi-receipt',
                    'icon_color_class' => 'text-info',
                    'link' => route('user.pesanan.show', $this->order->id),
                    'time_ago' => 'Baru saja',
                    'order_id' => $this->order->id,
                    'kantin_name' => $kantinName,
                    'payment_method' => $paymentMethod
                ],
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create database notification for new order: ' . $e->getMessage(), [
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id
            ]);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->order->user_id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new-order-for-user';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Pesanan Anda #' . $this->order->id . ' telah dibuat.',
        ];
    }
}
