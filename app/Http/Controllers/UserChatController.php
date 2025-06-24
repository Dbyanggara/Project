<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserChatController extends Controller
{
    /**
     * Menampilkan halaman chat pengguna.
     */
    public function index(Request $request): View
    {
        $seller = null;
        $sellerId = $request->query('seller_id');
        $sellerName = $request->query('seller_name');

        if ($sellerId) {
            $seller = User::find($sellerId);
            // Jika seller ditemukan dan sellerName tidak ada atau untuk memastikan akurasi
            if ($seller && !$sellerName) {
                $sellerName = $seller->name;
            }
        }

        // Ambil daftar penjual untuk sidebar (jika tidak ada seller yang dipilih)
        $sellers = User::role('seller')->select('id', 'name')->get();

        return view('user.chat.index', [
            'sellers' => $sellers,
            'seller' => $seller,
            'sellerId' => $sellerId,
            'sellerName' => $sellerName
        ]);
    }
}
