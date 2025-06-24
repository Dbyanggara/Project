<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class UserNotificationController extends Controller
{
    /**
     * Menampilkan daftar notifikasi pengguna.
     */
    public function index(): View
    {
        $notifications = Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        // Count unread notifications
        $unreadCount = Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        \Log::info('User notifications loaded', [
            'user_id' => Auth::id(),
            'notification_count' => $notifications->count(),
            'total_count' => $notifications->total(),
            'unread_count' => $unreadCount
        ]);

        return view('user.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Menandai notifikasi sebagai telah dibaca.
     */
    public function markAsRead($notificationId)
    {
        try {
            $notification = Notification::where('id', $notificationId)
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', Auth::id())
                ->first();

            if (!$notification) {
                \Log::warning('Notification not found or unauthorized access', [
                    'notification_id' => $notificationId,
                    'user_id' => Auth::id()
                ]);
                return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
            }

            if ($notification->read_at) {
                return redirect()->back()->with('info', 'Notifikasi sudah ditandai sebagai dibaca.');
            }

            $notification->markAsRead();

            \Log::info('Notification marked as read', [
                'notification_id' => $notificationId,
                'user_id' => Auth::id(),
                'read_at' => $notification->read_at
            ]);

            return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');

        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage(), [
                'notification_id' => $notificationId,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menandai notifikasi sebagai dibaca.');
        }
    }

    /**
     * Menandai semua notifikasi sebagai telah dibaca.
     */
    public function markAllAsRead()
    {
        try {
            $unreadCount = Notification::where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', Auth::id())
                ->whereNull('read_at')
                ->count();

            if ($unreadCount === 0) {
                return redirect()->back()->with('info', 'Tidak ada notifikasi yang belum dibaca.');
            }

            $updatedCount = Notification::where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            \Log::info('All notifications marked as read', [
                'user_id' => Auth::id(),
                'updated_count' => $updatedCount
            ]);

            return redirect()->back()->with('success', "{$updatedCount} notifikasi telah ditandai sebagai dibaca.");

        } catch (\Exception $e) {
            \Log::error('Error marking all notifications as read: ' . $e->getMessage(), [
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menandai semua notifikasi sebagai dibaca.');
        }
    }
}
