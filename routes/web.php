<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Seller\ProfileController as SellerProfileController;
use App\Http\Controllers\Seller\MenuController as SellerMenuController;
use App\Http\Controllers\Admin\KantinController;
use App\Http\Controllers\Seller\ReportController as SellerReportController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\User\UserMenuController; // Corrected namespace
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserChatController;
use App\Http\Controllers\User\PesananController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('seller')) {
        return redirect()->route('seller.dashboard');
    } elseif ($user->hasRole('user')) {
        return redirect()->route('user.dashboard');
    } else {
        // Fallback untuk pengguna yang terautentikasi tetapi tidak memiliki peran admin/seller/user secara eksplisit.
        // Ini akan mengarahkan pengguna baru (yang mungkin belum diberi peran) ke dasbor pengguna.
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/sellers', [AdminSellerController::class, 'index'])->name('sellers.index');
    Route::resource('kantins', KantinController::class);
});

// Seller Routes
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [SellerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [SellerProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [SellerProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/kantin', [SellerProfileController::class, 'updateKantin'])->name('kantin.update');
    Route::patch('/payment', [SellerProfileController::class, 'updatePayment'])->name('payment.update');

    // Menu
    Route::resource('menus', SellerMenuController::class);

    // Reports
    Route::get('/reports', [SellerReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [SellerReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/orders', [SellerReportController::class, 'orders'])->name('reports.orders');

    // Orders
    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/card', [SellerOrderController::class, 'showCard'])->name('orders.card');
    Route::patch('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}', [SellerOrderController::class, 'destroy'])->name('orders.destroy');

    // Chat
    Route::get('/chat', [\App\Http\Controllers\Seller\ChatController::class, 'index'])->name('chat.index');
});

// User Routes
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [UserMenuController::class, 'index'])->name('menu');
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{order}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan/{order}/card', [PesananController::class, 'getOrderCard'])->name('pesanan.card');
    Route::post('/notifications/{notificationId}/read', [UserNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-as-read', [UserNotificationController::class, 'markAllAsRead'])->name('notifications.markallasread');
    Route::get('/chat', [UserChatController::class, 'index'])->name('chat.index');
    Route::get('/menu/{menu_id}/detail', [UserMenuController::class, 'showMenuDetail'])->name('menu.detail');
    Route::post('/orders/add-to-cart', [PesananController::class, 'addToCart'])->name('orders.add-to-cart');
    Route::post('/orders/buy-now', [PesananController::class, 'buyNow'])->name('orders.buy-now');
    Route::get('/checkout', [PesananController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout/process', [PesananController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/cart', [App\Http\Controllers\User\CartController::class, 'index'])->name('cart.index');

    // Payment routes
    Route::get('/payment/{orderId}', [\App\Http\Controllers\PaymentController::class, 'paymentPage'])->name('payment.page');
    Route::get('/payment/{orderId}/status', [\App\Http\Controllers\PaymentController::class, 'checkPaymentStatus'])->name('payment.status');

    // Routes specifically for users with 'user' role.
    // These routes will inherit the 'user.' name prefix and '/user' URL prefix from the parent group.
    Route::middleware(['role:user'])->group(function () {
        // The dashboard route defined here (/user/dashboard with name user.dashboard)
        // will effectively be the one used due to Laravel's route precedence (last one defined for the same path/name).
        // It ensures this dashboard access requires the 'role:user'.
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard'); // Results in name 'user.dashboard'
        Route::get('/kantin/{id}/menu', [UserMenuController::class, 'showMenu'])->name('kantin.menu');
    });

    Route::get('/notifications/unread-count', function() {
        $count = 0;
        if (auth()->check()) {
            $count = auth()->user()->unreadNotifications()->count();
        }
        return response()->json(['count' => $count]);
    })->name('notifications.unread-count');
});

// Payment callback routes (tidak perlu auth karena dari external service)
Route::post('/payment/dana/callback', [\App\Http\Controllers\PaymentController::class, 'danaCallback'])->name('payment.dana.callback');
Route::get('/payment/dana/return', [\App\Http\Controllers\PaymentController::class, 'danaReturn'])->name('payment.dana.return');

// Handle missing images
Route::get('/user/kantin-images/{filename}', function ($filename) {
    \Log::warning('Attempted to access missing kantin image', ['filename' => $filename]);
    return redirect(asset('img/logo1.png'));
})->where('filename', '.*');

Route::get('/user/menu-images/{filename}', function ($filename) {
    \Log::warning('Attempted to access missing menu image', ['filename' => $filename]);
    return redirect(asset('img/icon-default.png'));
})->where('filename', '.*');

Route::get('/chat/{receiver}', function (User $receiver) {
    if (!$receiver || $receiver->id === Auth::id()) {
        abort(404);
    }
    return view('chat.index', ['receiver' => $receiver]);
})->middleware('auth')->name('chat.show');

require __DIR__.'/auth.php';
