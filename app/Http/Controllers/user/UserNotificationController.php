<?php

namespace App\Http\Controllers\User; // Mengubah namespace sesuai dengan direktori baru

use App\Http\Controllers\Controller; // Menambahkan use statement untuk base Controller
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
// App\Models\Notification tidak perlu di-import di sini jika kita menggunakan relasi dari User

class UserNotificationController extends Controller
{
    /**
     * Menampilkan halaman notifikasi pengguna.
     */
    public function index(): View
    {
        $user = Auth::user();

        if (!$user) {
            // Seharusnya ditangani oleh middleware auth, tapi sebagai penjagaan
            abort(403, 'User tidak terautentikasi.');
        }

        // Mengambil notifikasi milik user yang login menggunakan relasi
        // Paginasi juga bisa ditambahkan di sini jika diperlukan, contoh: ->paginate(15)
        $notifications = $user->notifications()
                               ->orderBy('created_at', 'desc')
                               ->paginate(15); // Menggunakan paginasi

        // Variabel ini sudah ada di view dan sepertinya untuk global count
        $unreadNotificationsCountGlobal = $user->unreadNotifications->count();

        return view('user.notifications.index', compact('notifications', 'unreadNotificationsCountGlobal'));
    }

    /**
     * Menandai semua notifikasi pengguna yang belum dibaca sebagai sudah dibaca.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        if ($user) {
            // Menggunakan relasi untuk menandai semua notifikasi yang belum dibaca
            $user->unreadNotifications()->update(['read_at' => now()]);
            return redirect()->route('user.notifications.index')
                             ->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
        }
        return redirect()->route('user.notifications.index')
                         ->with('error', 'Gagal menandai notifikasi.');
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(Request $request, string $notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification && $notification->markAsRead()) { // Memanggil metode markAsRead dari model Notification
            return redirect()->route('user.notifications.index')->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
        }
        return redirect()->route('user.notifications.index')
                         ->with('error', 'Gagal menandai notifikasi atau notifikasi tidak ditemukan.');
    }
}
