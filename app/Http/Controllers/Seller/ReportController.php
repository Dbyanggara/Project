<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Kantin;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan seller.
     */
    public function index(Request $request): View
    {
        $seller = Auth::user();
        $kantinId = $seller->kantin ? $seller->kantin->id : null;

        if (!$kantinId) {
            return view('seller.report.laporan', [
                'sellerName' => $seller->name,
                'chartLabels' => [],
                'chartData' => [],
                'filterPeriode' => 'minggu_ini',
                'summary' => [
                    'totalRevenue' => 0,
                    'totalOrders' => 0,
                    'averageOrderValue' => 0,
                    'topSellingItems' => []
                ]
            ]);
        }

        $filterPeriode = $request->input('periode', 'minggu_ini');

        // Ambil data berdasarkan periode
        $data = $this->getSalesData($kantinId, $filterPeriode);

        return view('seller.report.laporan', [
            'sellerName' => $seller->name,
            'chartLabels' => $data['labels'],
            'chartData' => $data['revenue'],
            'filterPeriode' => $filterPeriode,
            'summary' => $data['summary']
        ]);
    }

    /**
     * Mendapatkan data penjualan berdasarkan periode
     */
    private function getSalesData($kantinId, $periode)
    {
        $startDate = null;
        $endDate = Carbon::now();
        $groupBy = 'day';
        $dateFormat = 'Y-m-d';

        switch ($periode) {
            case 'hari_ini':
                $startDate = Carbon::today();
                $groupBy = 'hour';
                $dateFormat = 'H:i';
                break;
            case 'minggu_ini':
                $startDate = Carbon::now()->startOfWeek();
                $groupBy = 'day';
                $dateFormat = 'D';
                break;
            case 'bulan_ini':
                $startDate = Carbon::now()->startOfMonth();
                $groupBy = 'day';
                $dateFormat = 'd/m';
                break;
            default:
                $startDate = Carbon::now()->startOfWeek();
        }

        // Ambil data pendapatan per periode
        $revenueData = $this->getRevenueData($kantinId, $startDate, $endDate, $groupBy, $dateFormat);

        // Ambil ringkasan data
        $summary = $this->getSummaryData($kantinId, $startDate, $endDate);

        return [
            'labels' => $revenueData['labels'],
            'revenue' => $revenueData['data'],
            'summary' => $summary
        ];
    }

    /**
     * Mendapatkan data pendapatan
     */
    private function getRevenueData($kantinId, $startDate, $endDate, $groupBy, $dateFormat)
    {
        $query = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('menus.kantin_id', $kantinId)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate]);

        if ($groupBy === 'hour') {
            $data = $query->selectRaw('
                DATE_FORMAT(orders.created_at, "%H:00") as period,
                SUM(order_items.price * order_items.quantity) as revenue
            ')
            ->groupBy('period')
            ->orderBy('period')
            ->get();
        } else {
            $data = $query->selectRaw('
                DATE(orders.created_at) as period,
                SUM(order_items.price * order_items.quantity) as revenue
            ')
            ->groupBy('period')
            ->orderBy('period')
            ->get();
        }

        // Generate labels untuk semua periode
        $labels = [];
        $revenueMap = $data->pluck('revenue', 'period')->toArray();

        if ($groupBy === 'hour') {
            // Generate labels untuk jam (00:00 - 23:00)
            for ($i = 0; $i < 24; $i++) {
                $hour = sprintf('%02d:00', $i);
                $labels[] = $hour;
                if (!isset($revenueMap[$hour])) {
                    $revenueMap[$hour] = 0;
                }
            }
        } else {
            // Generate labels untuk hari
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $period = $current->format('Y-m-d');
                $labels[] = $current->format($dateFormat);
                if (!isset($revenueMap[$period])) {
                    $revenueMap[$period] = 0;
                }
                $current->addDay();
            }
        }

        // Sort revenue data sesuai dengan labels
        $revenue = [];
        if ($groupBy === 'hour') {
            foreach ($labels as $label) {
                $revenue[] = $revenueMap[$label] ?? 0;
            }
        } else {
            // Untuk hari, kita perlu mapping yang benar
            $current = $startDate->copy();
            foreach ($labels as $label) {
                $period = $current->format('Y-m-d');
                $revenue[] = $revenueMap[$period] ?? 0;
                $current->addDay();
            }
        }

        return [
            'labels' => $labels,
            'data' => $revenue
        ];
    }

    /**
     * Mendapatkan ringkasan data
     */
    private function getSummaryData($kantinId, $startDate, $endDate)
    {
        // Total pendapatan
        $totalRevenue = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('menus.kantin_id', $kantinId)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        // Total pesanan
        $totalOrders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('menus.kantin_id', $kantinId)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('orders.id')
            ->count('orders.id');

        // Rata-rata nilai pesanan
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Item terlaris
        $topSellingItems = OrderItem::join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('menus.kantin_id', $kantinId)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'topSellingItems' => $topSellingItems
        ];
    }
}
