<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Events\OrderCompletedEvent;
use App\Events\OrderStatusChangedEvent;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the seller's orders.
     */
    public function index(Request $request)
    {
        $seller = Auth::user();
        $kantinId = $seller->kantin->id ?? null;

        if (!$kantinId) {
            return view('seller.orders.index', [
                'orders' => collect(),
                'statusFilter' => null
            ])->with('error', 'Anda tidak memiliki kantin terdaftar.');
        }

        // Jika tidak ada filter status, redirect ke status=pending
        if (!$request->has('status')) {
            return redirect()->route('seller.orders.index', ['status' => 'pending']);
        }

        $query = Order::whereHas('orderItems.menu', function ($query) use ($kantinId) {
            $query->where('kantin_id', $kantinId);
        })
        ->with(['user', 'orderItems.menu'])
        ->orderByRaw("FIELD(status, 'pending') DESC")
        ->orderBy('created_at', 'desc');

        $statusFilter = $request->query('status');
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $orders = $query->paginate(10);

        return view('seller.orders.index', compact('orders', 'statusFilter'));
    }

    /**
     * Update the status of a specific order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        try {
            $this->authorize('update', $order);

            $request->validate([
                'status' => ['required', 'string', 'in:pending,processing,completed,cancelled'],
            ]);

            $oldStatus = $order->status;
            $newStatus = $request->input('status');

            $order->update(['status' => $newStatus]);

            // Trigger events for status changes
            if ($oldStatus !== $newStatus) {
                if ($newStatus === 'completed') {
                    event(new OrderCompletedEvent($order));
                } else {
                    event(new OrderStatusChangedEvent($order, $oldStatus, $newStatus));
                }
            }

            return redirect()->route('seller.orders.index')
                ->with('success', "Status pesanan #{$order->id} berhasil diperbarui dari " . ucfirst($oldStatus) . " menjadi " . ucfirst($newStatus));

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('seller.orders.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengubah status pesanan ini.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('seller.orders.index')
                ->with('error', 'Status yang dipilih tidak valid.');
        } catch (\Exception $e) {
            \Log::error('Error updating order status: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'requested_status' => $request->input('status')
            ]);

            return redirect()->route('seller.orders.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui status pesanan.');
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        // Eager load relationships
        $order->load(['user', 'orderItems.menu']);

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        try {
            $this->authorize('delete', $order);

            $order->delete();

            return redirect()->route('seller.orders.index')->with('success', "Pesanan #{$order->id} berhasil dihapus.");

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('seller.orders.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus pesanan ini. Pesanan dengan status "' . ucfirst($order->status) . '" tidak dapat dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting order: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'order_status' => $order->status
            ]);

            return redirect()->route('seller.orders.index')
                ->with('error', 'Terjadi kesalahan saat menghapus pesanan.');
        }
    }

    /**
     * Fetch a single order card HTML.
     */
    public function showCard(Order $order): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authorize('view', $order);

            $order->load(['user', 'orderItems.menu']);

            // Definisikan terjemahan status agar konsisten dengan halaman index
            $statusTranslations = [
                'pending' => 'Tertunda',
                'processing' => 'Diproses',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];

            $html = view('seller.orders._order_card', compact('order', 'statusTranslations'))->render();

            return response()->json(['success' => true, 'html' => $html]);

        } catch (\Exception $e) {
            \Log::error("Failed to fetch order card: " . $e->getMessage(), ['order_id' => $order->id]);
            return response()->json(['success' => false, 'message' => 'Gagal memuat data pesanan.'], 500);
        }
    }
}
