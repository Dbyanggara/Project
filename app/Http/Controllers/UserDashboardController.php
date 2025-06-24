<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kantin; // Asumsikan Anda memiliki model Kantin
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard pengguna dengan daftar kantin.
     */
    public function index(): View
    {
        // Ambil semua data kantin dengan relasi user dan urutkan berdasarkan yang terbaru
        $kantins = Kantin::with(['user', 'menus'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Log data kantin untuk debugging
        \Log::info('Kantins data:', $kantins->toArray());

        return view('user.dashboard', compact('kantins'));
    }
}

