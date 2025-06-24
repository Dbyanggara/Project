<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Events\NewOrderEvent;
use App\Events\NewOrderForUserEvent;

class PesananController extends Controller
{
    /**
     * Menampilkan halaman daftar pesanan pengguna.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Temporary debugging to check orders for the logged-in user
        $orderCount = Order::where('user_id', $user->id)->count();
        \Log::info("User {$user->name} (ID: {$user->id}) has {$orderCount} orders.");

        $orders = Order::where('user_id', $user->id)
                       ->with(['orderItems.menu.kantin'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        return view('user.pesanan.index', compact('orders'));
    }

    /**
     * Menampilkan riwayat pesanan pengguna.
     */
    public function riwayat(): View
    {
        $user = Auth::user();
        $pesanans = Order::where('user_id', $user->id)
                        ->with(['orderItems.menu.kantin'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('user.riwayat.pesanan', compact('pesanans'));
    }

    /**
     * Menampilkan detail pesanan.
     */
    public function show($id): View
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
                      ->with(['orderItems.menu.kantin', 'user'])
                      ->findOrFail($id);

        return view('user.pesanan.show', compact('order'));
    }

    /**
     * Tambah menu ke keranjang.
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menangani permintaan "Beli Sekarang".
     */
    public function buyNow(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->menu_id);

        if ($menu->stock < $request->quantity) {
            return back()->with('error', 'Maaf, stok tidak mencukupi.');
        }

        $checkoutData = [
            'menu_id' => $menu->id,
            'quantity' => $request->quantity,
            'price' => $menu->price,
            'subtotal' => $menu->price * $request->quantity,
        ];

        session(['checkout_data' => $checkoutData]);

        return redirect()->route('user.checkout.show');
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function showCheckout()
    {
        $checkoutData = session('checkout_data');

        // Debug: Log session data
        \Log::info('ShowCheckout called', [
            'checkout_data_exists' => !empty($checkoutData),
            'checkout_data' => $checkoutData
        ]);

        if (!$checkoutData) {
            return redirect()->route('user.dashboard')->with('error', 'Tidak ada item untuk di-checkout.');
        }

        $item = Menu::find($checkoutData['menu_id']);
        if (!$item) {
            session()->forget('checkout_data');
            return redirect()->route('user.dashboard')->with('error', 'Item yang akan di-checkout tidak valid.');
        }

        $quantity = $checkoutData['quantity'];
        $subtotal = $checkoutData['subtotal'];

        // Total sama dengan subtotal (tanpa biaya tambahan)
        $total = $subtotal;

        // Data untuk ringkasan pesanan
        $orderSummary = [
            'items' => [
                [
                    'name' => $item->name,
                    'quantity' => $quantity,
                    'price' => $item->price,
                    'subtotal' => $subtotal,
                    'icon' => 'food' // Bisa disesuaikan dengan kategori menu
                ]
            ],
            'total' => $total
        ];

        return view('user.checkout.index', compact('item', 'quantity', 'orderSummary'));
    }

    /**
     * Memproses pesanan dari halaman checkout.
     */
    public function processCheckout(Request $request)
    {
        // Debug: Log request data
        \Log::info('Checkout form submitted', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $request->validate([
            'nomor_meja' => 'nullable|string|max:10',
            'payment_method' => 'required|in:cod,gopay,ovo,dana',
            'notes' => 'nullable|string|max:500',
        ]);

        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('user.dashboard')->with('error', 'Sesi checkout telah berakhir.');
        }

        $menu = Menu::findOrFail($checkoutData['menu_id']);
        $quantity = $checkoutData['quantity'];
        $user = Auth::user();

        try {
            DB::beginTransaction();

            if ($menu->stock < $quantity) {
                throw new \Exception('Stok produk tidak mencukupi.');
            }

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $checkoutData['subtotal'],
                'notes' => $request->input('notes'),
                'shipping_address' => [
                    'nama' => $user->name,
                    'email' => $user->email,
                    'nomor_meja' => $request->nomor_meja,
                ],
                'payment_method' => $request->payment_method,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'quantity' => $quantity,
                'price' => $menu->price,
            ]);

            $menu->decrement('stock', $quantity);
            session()->forget('checkout_data');

            DB::commit();

            // Fire events
            try {
                broadcast(new NewOrderEvent($order))->toOthers();
                broadcast(new NewOrderForUserEvent($order)); // No toOthers() here
            } catch (\Exception $e) {
                \Log::error('Broadcasting events failed: ' . $e->getMessage());
            }

            // Debug: Log successful order creation
            \Log::info('Order created successfully and events broadcasted', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'kantin_id' => $menu->kantin_id
            ]);

            // Handle pembayaran berdasarkan metode yang dipilih
            if (in_array($request->payment_method, ['gopay', 'ovo', 'dana'])) {
                return $this->processEWalletPayment($order, $request->payment_method);
            } else {
                // COD - redirect ke menu kantin dengan pesan sukses
                $kantinId = $menu->kantin_id;
                return redirect()->route('user.kantin.menu', $kantinId)
                    ->with('success', 'Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('user.checkout.show')->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Proses pembayaran e-wallet
     */
    protected function processEWalletPayment(Order $order, $paymentMethod)
    {
        try {
            switch ($paymentMethod) {
                case 'dana':
                    return $this->processDanaPayment($order);
                case 'gopay':
                    return $this->processGopayPayment($order);
                case 'ovo':
                    return $this->processOvoPayment($order);
                default:
                    throw new \Exception('Metode pembayaran tidak didukung');
            }
        } catch (\Exception $e) {
            // Jika terjadi error, redirect ke menu kantin
            $menu = $order->orderItems->first()->menu;
            $kantinId = $menu->kantin_id;
            return redirect()->route('user.kantin.menu', $kantinId)
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Proses pembayaran DANA
     */
    protected function processDanaPayment(Order $order)
    {
        $danaService = app(\App\Services\DanaPaymentService::class);
        $result = $danaService->createPayment($order, auth()->user()->phone ?? null);

        if ($result['success']) {
            // Redirect ke halaman pembayaran DANA
            return redirect()->away($result['payment_url']);
        } else {
            // Jika gagal, redirect ke menu kantin
            $menu = $order->orderItems->first()->menu;
            $kantinId = $menu->kantin_id;
            return redirect()->route('user.kantin.menu', $kantinId)
                ->with('error', $result['message']);
        }
    }

    /**
     * Proses pembayaran GoPay
     */
    protected function processGopayPayment(Order $order)
    {
        // Implementasi untuk GoPay
        // Similar dengan DANA
        $menu = $order->orderItems->first()->menu;
        $kantinId = $menu->kantin_id;
        return redirect()->route('user.kantin.menu', $kantinId)
            ->with('error', 'Pembayaran GoPay belum tersedia');
    }

    /**
     * Proses pembayaran OVO
     */
    protected function processOvoPayment(Order $order)
    {
        // Implementasi untuk OVO
        // Similar dengan DANA
        $menu = $order->orderItems->first()->menu;
        $kantinId = $menu->kantin_id;
        return redirect()->route('user.kantin.menu', $kantinId)
            ->with('error', 'Pembayaran OVO belum tersedia');
    }

    public function getOrderCard(Order $order)
    {
        // Pastikan user hanya bisa mengambil kartunya sendiri
        if (auth()->id() !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $html = view('user.pesanan._order_card', compact('order'))->render();
        return response()->json(['html' => $html]);
    }

    public function process(Request $request)
    {
        // ...
    }
}
