<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class SellerDashboardController extends Controller
{
    /**
     * Display the seller's dashboard.
     */
    public function index(Request $request): View
    {
        try {
            $seller = Auth::user();
            $seller->load('kantin'); // Eager load kantin relationship
            $kantinId = $seller->kantin ? $seller->kantin->id : null;

            $todaySales = 0;
            $todayOrdersCount = 0;
            $activeOrders = collect();
            $products = collect();
            $newOrdersNotificationCount = 0;
            $bestSellingMenu = (object)[
                'name' => 'Belum ada data',
                'total_sold' => 0
            ];

            if ($kantinId) {
                $today = Carbon::today();

                // Menghitung total penjualan hari ini
                $todaySales = Order::whereHas('orderItems.menu', function ($query) use ($kantinId) {
                    $query->where('kantin_id', $kantinId);
                })
                ->whereDate('created_at', $today)
                ->where('status', 'Selesai')
                ->sum('total');

                // Menghitung jumlah pesanan hari ini
                $todayOrdersCount = Order::whereHas('orderItems.menu', function ($query) use ($kantinId) {
                    $query->where('kantin_id', $kantinId);
                })
                ->whereDate('created_at', $today)
                ->count();

                // Ambil pesanan aktif dengan eager loading
                $activeOrders = Order::whereHas('orderItems.menu', function ($query) use ($kantinId) {
                    $query->where('kantin_id', $kantinId);
                })
                ->whereRaw('LOWER(status) = ?', ['pending'])
                ->with(['user', 'orderItems.menu']) // Eager load relationships
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

                // Ambil menu terbaru
                $products = Menu::where('kantin_id', $kantinId)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                // Hitung pesanan baru yang menunggu konfirmasi
                $newOrdersNotificationCount = Order::whereHas('orderItems.menu', function ($query) use ($kantinId) {
                    $query->where('kantin_id', $kantinId);
                })
                ->where('status', 'Menunggu Konfirmasi')
                ->count();

                // Data untuk menu terlaris
                $bestSellingMenuData = DB::table('order_items')
                    ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('menus.kantin_id', $kantinId)
                    ->where('orders.status', 'Selesai')
                    ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                    ->groupBy('menus.id', 'menus.name')
                    ->orderBy('total_sold', 'desc')
                    ->first();

                if ($bestSellingMenuData) {
                    $bestSellingMenu = (object)[
                        'name' => $bestSellingMenuData->name,
                        'total_sold' => $bestSellingMenuData->total_sold
                    ];
                }
            }

            // Data untuk rating kantin
            $canteenRating = (object)[
                'rating' => 4.5, // Ini bisa diambil dari tabel ratings jika ada
                'reviews' => 100 // Ini bisa diambil dari tabel reviews jika ada
            ];

            return view('seller.dashboard', [
                'sellerName' => $seller->name,
                'todaySales' => $todaySales,
                'todayOrdersCount' => $todayOrdersCount,
                'bestSellingMenu' => $bestSellingMenu,
                'canteenRating' => $canteenRating,
                'activeOrders' => $activeOrders,
                'products' => $products,
                'newOrdersNotificationCount' => $newOrdersNotificationCount,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in SellerDashboardController@index: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return view('seller.dashboard', [
                'sellerName' => Auth::user()->name,
                'todaySales' => 0,
                'todayOrdersCount' => 0,
                'bestSellingMenu' => (object)['name' => 'Error', 'total_sold' => 0],
                'canteenRating' => (object)['rating' => 0, 'reviews' => 0],
                'activeOrders' => collect(),
                'products' => collect(),
                'newOrdersNotificationCount' => 0,
            ])->with('error', 'Terjadi kesalahan saat memuat dashboard. Silakan coba lagi nanti.');
        }
    }
}
