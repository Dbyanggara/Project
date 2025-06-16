<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Tetap gunakan ini
use Illuminate\Database\Eloquent\Relations\MorphTo; // Tambahkan ini

class Notification extends Model
{
    use HasFactory;

    /**
     * Tabel yang terhubung dengan model.
     * Laravel akan menyimpulkan 'notifications' secara default jika ini tidak diatur.
     *
     * @var string
     */
    // protected $table = 'notifications';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',             // Tipe notifikasi (misalnya, nama kelas notifikasi)
        'notifiable_type',  // Tipe model yang ternotifikasi (misalnya, App\Models\User)
        'notifiable_id',    // ID dari model yang ternotifikasi (misalnya, user_id)
        'data',             // Payload JSON dengan detail notifikasi
        'read_at',          // Timestamp kapan notifikasi dibaca
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Mendapatkan model parent yang dapat dinotifikasi (user, dll.).
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     *
     * @return bool
     */
    public function markAsRead(): bool
    {
        if (is_null($this->read_at)) {
            return $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
        return true; // Sudah dibaca sebelumnya
    }

    /**
     * Menandai notifikasi sebagai belum dibaca.
     *
     * @return bool
     */
    public function markAsUnread(): bool
    {
        // Selalu set read_at ke null, bahkan jika sudah null
        return $this->forceFill(['read_at' => null])->save();
    }
}
