<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; // Asumsikan model ini ada

class UserPesananController extends Controller
{
    /**
     * Menampilkan halaman daftar pesanan pengguna.
     */
    public function index(): View
    {
        $user = Auth::user();
        // Ambil pesanan milik pengguna yang sedang login
        // Asumsikan Order memiliki relasi 'kantin' untuk mendapatkan nama kantin
        // dan relasi 'items' yang masing-masing item memiliki relasi ke 'menu' (atau 'product')
        $orders = Order::where('user_id', $user->id)
                       ->with(['kantin', 'items.menu']) // Eager load relasi
                       ->orderBy('order_date', 'desc')
                       ->paginate(10); // Tambahkan paginasi
        return view('user.pesanan.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan spesifik untuk pengguna.
     *
     * @param  string  $orderId
     * @return \Illuminate\View\View
     */
    // public function show(string $orderId): View
    // {
    //     $user = Auth::user();
    //     $order = Order::where('id', $orderId)
    //                   ->where('user_id', $user->id) // Pastikan pesanan milik pengguna
    //                   ->with(['kantin', 'items.menu']) // Eager load relasi
    //                   ->firstOrFail();
    //     return view('user.pesanan.show', compact('order')); // Anda perlu membuat view ini
    // }
}
