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

class OrderStatusChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;

        // Create database notification
        $this->createDatabaseNotification();
    }

    private function createDatabaseNotification()
    {
        try {
            $kantinName = $this->order->orderItems->first()->menu->kantin->name ?? 'Kantin';

            // Define status messages and icons
            $statusConfig = [
                'pending' => [
                    'title' => 'Pesanan Tertunda',
                    'message' => "Pesanan #{$this->order->id} sedang menunggu konfirmasi dari {$kantinName}.",
                    'icon' => 'bi-clock',
                    'color' => 'text-warning'
                ],
                'processing' => [
                    'title' => 'Pesanan Sedang Diproses',
                    'message' => "Pesanan #{$this->order->id} sedang diproses oleh {$kantinName}.",
                    'icon' => 'bi-gear',
                    'color' => 'text-info'
                ],
                'cancelled' => [
                    'title' => 'Pesanan Dibatalkan',
                    'message' => "Pesanan #{$this->order->id} telah dibatalkan oleh {$kantinName}.",
                    'icon' => 'bi-x-circle',
                    'color' => 'text-danger'
                ]
            ];

            $config = $statusConfig[$this->newStatus] ?? [
                'title' => 'Status Pesanan Berubah',
                'message' => "Status pesanan #{$this->order->id} telah berubah menjadi " . ucfirst($this->newStatus),
                'icon' => 'bi-info-circle',
                'color' => 'text-secondary'
            ];

            Notification::create([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\OrderStatusChangedNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->order->user_id,
                'data' => [
                    'title' => $config['title'],
                    'message' => $config['message'],
                    'icon_class' => $config['icon'],
                    'icon_color_class' => $config['color'],
                    'link' => route('user.pesanan.show', $this->order->id),
                    'time_ago' => 'Baru saja',
                    'order_id' => $this->order->id,
                    'kantin_name' => $kantinName,
                    'old_status' => $this->oldStatus,
                    'new_status' => $this->newStatus
                ],
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create database notification for order status change: ' . $e->getMessage(), [
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus
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
        return 'order.status-changed';
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Status pesanan Anda telah berubah.',
        ];
    }
}
