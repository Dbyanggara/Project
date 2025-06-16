<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
// use App\Models\Order; // Anda mungkin memerlukan ini untuk data chart aktual

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan seller.
     */
    public function index(Request $request): View
    {
        $seller = Auth::user();
        $kantinId = $seller->kantin ? $seller->kantin->id : null;

        // TODO: Ambil data penjualan aktual untuk chart berdasarkan filter waktu
        // Contoh data dummy untuk chart
        $chartLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $chartData = [120000, 190000, 300000, 500000, 200000, 300000, 450000];
        $filterPeriode = $request->input('periode', 'minggu_ini'); // Default 'minggu_ini'

        return view('seller.report.laporan', [
            'sellerName' => $seller->name, // Jika diperlukan di view
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'filterPeriode' => $filterPeriode,
        ]);
    }
}