<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\UserDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'seller') {
        return redirect()->route('seller.dashboard');
    } else {
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', 'verified', 'role:seller'])->get('/seller/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
Route::middleware(['auth', 'verified', 'role:user'])->get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
Route::middleware(['auth', 'verified', 'role:user'])->get('/user/menu', function () {
    return view('user.menu');
})->name('user.menu');

require __DIR__.'/auth.php';
