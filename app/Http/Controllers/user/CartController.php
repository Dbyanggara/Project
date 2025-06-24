<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index(): View
    {
        // Untuk sekarang, hanya tampilkan view kosong
        return view('user.cart.index');
    }
}
