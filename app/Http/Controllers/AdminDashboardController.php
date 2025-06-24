<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Order;
use App\Models\Kantin;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $totalUsers = User::role('user')->count();
        $totalSellers = User::role('seller')->count();
        $totalKantins = Kantin::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'paid')->sum('total');

        $latestOrders = Order::with(['user'])->latest()->take(10)->get();
        $latestUsers = User::role('user')->latest()->take(5)->get();
        $latestSellers = User::role('seller')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalSellers', 'totalKantins', 'totalOrders', 'totalRevenue',
            'latestOrders', 'latestUsers', 'latestSellers'
        ));
    }
}
