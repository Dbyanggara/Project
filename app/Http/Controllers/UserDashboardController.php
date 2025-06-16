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
        // Ambil semua data kantin. Anda bisa menambahkan logika filter atau paginasi di sini jika perlu.
        // Contoh: $kantins = Kantin::where('status', 'Buka')->get();
        // Untuk sekarang, kita ambil semua kantin sebagai contoh.
        $kantins = Kantin::all(); // Pastikan model Kantin dan tabelnya ada

        return view('user.dashboard', compact('kantins'));
    }
}

