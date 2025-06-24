<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kantin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'user_id', // Pastikan kolom ini ada untuk menyimpan ID penjual
        'image',
        'description',
        'operating_hours',
        'is_open',
        'phone',
        'email',
        'address'
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Clear cache when model is updated
        static::updated(function ($kantin) {
            cache()->forget('kantins');
            cache()->forget('kantin_' . $kantin->id);
        });

        // Clear cache when model is created
        static::created(function ($kantin) {
            cache()->forget('kantins');
        });

        // Clear cache when model is deleted
        static::deleted(function ($kantin) {
            cache()->forget('kantins');
            cache()->forget('kantin_' . $kantin->id);
        });
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Mendapatkan user (penjual) yang memiliki kantin ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the URL of the kantin image.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('img/logo1.png');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($this->image)) {
            \Log::warning('Kantin image file not found', [
                'kantin_id' => $this->id,
                'image_path' => $this->image
            ]);
            return asset('img/logo1.png');
        }

        // Log untuk debugging
        \Log::info('Getting image URL for kantin', [
            'kantin_id' => $this->id,
            'image_path' => $this->image,
            'full_url' => Storage::url($this->image),
            'storage_path' => Storage::disk('public')->path($this->image),
            'exists' => Storage::disk('public')->exists($this->image)
        ]);

        return Storage::url($this->image);
    }

    public function refreshData()
    {
        $this->refresh();
        cache()->forget('kantins');
        cache()->forget('kantin_' . $this->id);
        return $this;
    }
}
