<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
// Jika Anda ingin menampilkan data, uncomment model yang relevan
// use App\Models\User;
// use App\Models\Order; // Contoh

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Data dummy untuk dashboard admin
        $totalUsers = 150; // Contoh: User::count()
        $totalKantins = 25; // Contoh: Kantin::count()
        $totalOrders = 500; // Contoh: Order::count()
        $totalRevenue = 75000000; // Contoh: Order::sum('total_price')

        return view('admin.dashboard', compact('totalUsers', 'totalKantins', 'totalOrders', 'totalRevenue'));
    }
}
