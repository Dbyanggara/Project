<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the seller's orders.
     */
    public function index(Request $request): View
    {
        $seller = Auth::user();
        // TODO: Ambil data pesanan aktual untuk kantin milik seller ini dari database
        // Filter berdasarkan status jika ada parameter request
        $statusFilter = $request->query('status');

        $dummyOrders = collect([
            (object)[
                'id' => 'ORD-KNTN01-001',
                'customer_name' => 'Pelanggan Satu',
                'order_date' => Carbon::now()->subHours(2),
                'total_amount' => 75000,
                'status' => 'Menunggu Konfirmasi',
                'items_summary' => 'Nasi Goreng x2, Es Teh x2',
                'payment_status' => 'Belum Dibayar',
            ],
            (object)[
                'id' => 'ORD-KNTN01-002',
                'customer_name' => 'Pelanggan Dua',
                'order_date' => Carbon::now()->subHours(5),
                'total_amount' => 45000,
                'status' => 'Diproses',
                'items_summary' => 'Ayam Geprek x1, Jus Alpukat x1',
                'payment_status' => 'Lunas',
            ],
            (object)[
                'id' => 'ORD-KNTN01-003',
                'customer_name' => 'Pelanggan Tiga',
                'order_date' => Carbon::now()->subDay(),
                'total_amount' => 20000,
                'status' => 'Selesai',
                'items_summary' => 'Mie Ayam x1',
                'payment_status' => 'Lunas',
            ],
            (object)[
                'id' => 'ORD-KNTN01-004',
                'customer_name' => 'Pelanggan Empat',
                'order_date' => Carbon::now()->subMinutes(30),
                'total_amount' => 30000,
                'status' => 'Siap Diambil',
                'items_summary' => 'Soto Ayam x1, Kerupuk x1',
                'payment_status' => 'Lunas',
            ],
        ]);

        $orders = $dummyOrders;
        if ($statusFilter && $statusFilter !== 'semua') {
            $orders = $dummyOrders->filter(function ($order) use ($statusFilter) {
                return strtolower(str_replace(' ', '-', $order->status)) === $statusFilter;
            });
        }

        return view('seller.orders.index', compact('orders', 'statusFilter'));
    }

    /**
     * Update the status of a specific order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, string $orderId): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'in:Menunggu Konfirmasi,Diproses,Siap Diambil,Selesai,Dibatalkan'],
        ]);

        $newStatus = $request->input('status');

        // TODO: Implementasi logika update status pesanan di database
        // $order = Order::where('id', $orderId)->where('seller_id', Auth::id())->firstOrFail();
        // $order->status = $newStatus;
        // $order->save();

        return redirect()->route('seller.orders.index', ['status' => strtolower(str_replace(' ', '-', $newStatus))])
                         ->with('success', "Status pesanan #{$orderId} berhasil diperbarui menjadi {$newStatus}.");
    }

    /**
     * Display the specified order.
     *
     * @param  string  $orderId
     * @return \Illuminate\View\View
     */
    public function show(string $orderId): View
    {
        // Data dummy untuk detail pesanan
        $order = (object)[
            'id' => $orderId,
            'customer_name' => 'Pelanggan Satu Detail',
            'customer_email' => 'pelanggan1@example.com',
            'customer_phone' => '081234567890',
            'order_date' => \Carbon\Carbon::now()->subHours(2),
            'status' => 'Menunggu Konfirmasi',
            'payment_status' => 'Belum Dibayar',
            'payment_method' => 'COD (Bayar di Tempat)',
            'shipping_address' => 'Jl. Kampus No. 123, Fakultas Teknik, Meja 5',
            'items' => collect([
                (object)['product_name' => 'Nasi Goreng Spesial', 'quantity' => 2, 'price_per_item' => 25000, 'subtotal' => 50000, 'notes' => 'Tidak pedas'],
                (object)['product_name' => 'Es Teh Manis', 'quantity' => 2, 'price_per_item' => 5000, 'subtotal' => 10000, 'notes' => null],
            ]),
            'subtotal_amount' => 60000,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'total_amount' => 75000,
            'notes_from_customer' => 'Tolong sendok dan garpu plastik juga ya.',
        ];

        return view('seller.orders.show', compact('order'));
    }
}
