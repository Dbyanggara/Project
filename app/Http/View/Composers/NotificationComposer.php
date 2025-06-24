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
            // Menggunakan relasi unreadNotifications yang disediakan oleh trait Notifiable
            $unreadNotificationsCount = Auth::user()->unreadNotifications->count();
        }
        $view->with('unreadNotificationsCountGlobal', $unreadNotificationsCount);
    }
}
