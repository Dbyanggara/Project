<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationCountComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $unreadNotificationsCountGlobal = 0;

        if (Auth::check()) {
            $user = Auth::user();
            // Menggunakan relasi unreadNotifications yang disediakan oleh trait Notifiable
            $unreadNotificationsCountGlobal = $user->unreadNotifications->count();
        }

        $view->with('unreadNotificationsCountGlobal', $unreadNotificationsCountGlobal);
    }
}
