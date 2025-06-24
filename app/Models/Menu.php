<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'stock', 'image', 'kantin_id',
    ];

    public function kantin()
    {
        return $this->belongsTo(Kantin::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the URL of the menu image.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('img/icon-default.png');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($this->image)) {
            \Log::warning('Menu image file not found', [
                'menu_id' => $this->id,
                'image_path' => $this->image
            ]);
            return asset('img/icon-default.png');
        }

        return Storage::url($this->image);
    }
}
