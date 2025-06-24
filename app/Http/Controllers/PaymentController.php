<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\DanaPaymentService;
use App\Models\Order;

class PaymentController extends Controller
{
    /**
     * Handle webhook callback dari DANA
     */
    public function danaCallback(Request $request)
    {
        try {
            $payload = $request->all();
            $signature = $request->header('X-DANA-Signature');

            $danaService = app(DanaPaymentService::class);
            $success = $danaService->handleWebhook($payload, $signature);

            if ($success) {
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'error'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('DANA Callback Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Handle return URL dari DANA setelah pembayaran
     */
    public function danaReturn(Request $request)
    {
        try {
            $transactionId = $request->get('transactionId');
            $status = $request->get('status');

            if ($status === 'SUCCESS') {
                return redirect()->route('user.pesanan.index')
                    ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
            } else {
                return redirect()->route('user.pesanan.index')
                    ->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            \Log::error('DANA Return Error: ' . $e->getMessage());
            return redirect()->route('user.pesanan.index')
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    /**
     * Cek status pembayaran secara manual
     */
    public function checkPaymentStatus(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            $paymentData = json_decode($order->payment_data, true);
            $transactionId = $paymentData['transaction_id'] ?? null;

            if (!$transactionId) {
                return response()->json(['status' => 'no_transaction']);
            }

            $danaService = app(DanaPaymentService::class);
            $status = $danaService->checkPaymentStatus($transactionId);

            if ($status) {
                // Update order status berdasarkan status pembayaran
                switch ($status['status']) {
                    case 'SUCCESS':
                        $order->update(['status' => 'paid']);
                        break;
                    case 'FAILED':
                        $order->update(['status' => 'payment_failed']);
                        break;
                    case 'EXPIRED':
                        $order->update(['status' => 'expired']);
                        break;
                }

                return response()->json([
                    'status' => $status['status'],
                    'order_status' => $order->status
                ]);
            }

            return response()->json(['status' => 'unknown']);

        } catch (\Exception $e) {
            \Log::error('Check Payment Status Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Halaman pembayaran dengan QR Code dan Deep Link
     */
    public function paymentPage($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            $paymentData = json_decode($order->payment_data, true);

            return view('user.payment.page', [
                'order' => $order,
                'paymentData' => $paymentData
            ]);

        } catch (\Exception $e) {
            return redirect()->route('user.pesanan.index')
                ->with('error', 'Pesanan tidak ditemukan.');
        }
    }
}
