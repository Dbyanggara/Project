<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Order; // Asumsikan model ini ada
use App\Models\Menu;  // Asumsikan model ini ada (atau Product)
use Illuminate\Support\Facades\DB; // Untuk query yang lebih kompleks jika perlu

class SellerDashboardController extends Controller
{
    /**
     * Display the seller's dashboard.
     */
    public function index(Request $request): View
    {
        $seller = Auth::user();
        $kantinId = $seller->kantin ? $seller->kantin->id : null;

        $todaySales = 0;
        $todayOrdersCount = 0;
        $activeOrders = collect();
        $products = collect();
        $newOrdersNotificationCount = 0;

        if ($kantinId) {
            $today = Carbon::today();
            $todaySales = Order::where('kantin_id', $kantinId)
                               ->whereDate('order_date', $today)
                               ->where('status', 'Selesai') // Atau status lain yang dianggap sebagai penjualan
                               ->sum('total_amount');

            $todayOrdersCount = Order::where('kantin_id', $kantinId)
                                     ->whereDate('order_date', $today)
                                     ->count();

            // Ambil beberapa pesanan aktif (misalnya, yang belum selesai atau dibatalkan)
            $activeOrders = Order::where('kantin_id', $kantinId)
                                 ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                                 ->with('customer')
                                 ->orderBy('order_date', 'desc')
                                 ->take(5) // Ambil 5 pesanan terbaru
                                 ->get();

            // Ambil beberapa produk dari kantin seller
            $products = Menu::where('kantin_id', $kantinId) // Asumsikan Menu model dan kolom kantin_id
                            ->orderBy('created_at', 'desc')
                            ->take(5) // Ambil 5 produk terbaru
                            ->get();

            $newOrdersNotificationCount = Order::where('kantin_id', $kantinId)
                                               ->where('status', 'Menunggu Konfirmasi')
                                               ->count();
        }

        // Data dummy untuk yang belum ada querynya
        $bestSellingMenu = (object)['name' => 'Ayam Geprek (Contoh)', 'count' => 10]; // Perlu query lebih kompleks
        $canteenRating = (object)['rating' => 4.5, 'reviews' => 100]; // Perlu data rating kantin

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
    }
}
