<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'stock', 'image', 'kantin_id',
    ];

    public function kantin()
    {
        return $this->belongsTo(Kantin::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
