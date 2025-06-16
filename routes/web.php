<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\UserDashboardController; // Tetap
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Seller\ProfileController as SellerProfileController; // Tambahkan ini
use App\Http\Controllers\Seller\MenuController as SellerMenuController;
use App\Http\Controllers\Admin\KantinController;
use App\Http\Controllers\User\UserPesananController;
use App\Http\Controllers\Seller\ReportController as SellerReportController; // Tambahkan ini
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('seller')) {
        return redirect()->route('seller.dashboard');
    } elseif ($user->hasRole('user')) {
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chat routes
    Route::get('/chat/with/{sellerId}', [ChatController::class, 'getConversationWithSeller'])->name('chat.with.seller');
    Route::get('/chat/conversation/{conversationId}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/conversation/{conversationId}/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    // Seller chat routes
    Route::get('/seller/chat', [ChatController::class, 'sellerConversations'])->name('seller.chat.index');
});

// Rute untuk Admin Panel
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // For submitting the create form
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // For submitting the edit form
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/sellers', [AdminSellerController::class, 'index'])->name('sellers.index');
    Route::resource('kantins', KantinController::class);
});

Route::middleware(['auth', 'verified', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('menus', SellerMenuController::class);
    Route::get('orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{orderId}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/{orderId}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::get('profile', [SellerProfileController::class, 'edit'])->name('profile.edit'); // Route baru untuk profil seller
    Route::get('reports', [SellerReportController::class, 'index'])->name('reports.index'); // Route untuk laporan
    // Route::patch('orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', fn () => view('user.menu'))->name('menu'); // Jika masih digunakan
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/pesanan', [UserPesananController::class, 'index'])->name('pesanan.index');
    Route::post('/notifications/{notificationId}/read', [UserNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-as-read', [UserNotificationController::class, 'markAllAsRead'])->name('notifications.markallasread');
});

require __DIR__.'/auth.php';
