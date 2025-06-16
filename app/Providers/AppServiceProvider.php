<?php

namespace App\Providers;

use App\Http\View\Composers\NotificationCountComposer; // Mengubah ke Composer yang benar
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Menggunakan NotificationCountComposer yang mengambil data notifikasi aktual
        // Pastikan 'layouts.app' adalah nama layout utama Anda atau view yang relevan
        View::composer('layouts.app', NotificationCountComposer::class);
    }
}
