<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'status', 'total', 'shipping_address', 'payment_method', 'notes'
    ];

    protected $casts = [
        'shipping_address' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get status color for Bootstrap badges
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'primary',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'paid' => 'success',
            'shipped' => 'info',
            'delivered' => 'success',
            default => 'secondary'
        };
    }
}
