<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    public function compose(View $view)
    {
        $unreadNotificationsCount = 0;
        if (Auth::check() && Auth::user()->hasRole('user')) {
            // Ganti dengan query ke database notifikasi jika sudah ada model Notification
            $dummyNotifications = collect([
                (object)['read_at' => null], (object)['read_at' => null], (object)['read_at' => now()]
            ]);
            $unreadNotificationsCount = $dummyNotifications->whereNull('read_at')->count();
        }
        $view->with('unreadNotificationsCountGlobal', $unreadNotificationsCount);
    }
}
