<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class DanaPaymentService
{
    protected $config;
    protected $baseUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->config = config('payment.dana');
        $this->baseUrl = $this->config['base_url'];
    }

    /**
     * Generate access token untuk API DANA
     */
    public function getAccessToken()
    {
        try {
            $response = Http::post($this->baseUrl . '/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['access_token'];
                return $this->accessToken;
            }

            Log::error('DANA: Failed to get access token', $response->json());
            return null;
        } catch (\Exception $e) {
            Log::error('DANA: Error getting access token', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Buat transaksi pembayaran DANA
     */
    public function createPayment(Order $order, $userPhone = null)
    {
        try {
            if (!$this->accessToken) {
                $this->getAccessToken();
            }

            $paymentData = [
                'merchantTransId' => 'ORDER_' . $order->id . '_' . time(),
                'amount' => [
                    'currency' => 'IDR',
                    'value' => $order->total
                ],
                'merchantId' => $this->config['merchant_id'],
                'productDetails' => [
                    'name' => 'Pesanan Kantin #' . $order->id,
                    'description' => 'Pembayaran untuk pesanan di kantin'
                ],
                'callbackUrl' => route('payment.dana.callback'),
                'returnUrl' => route('payment.dana.return'),
                'merchantUserId' => $order->user_id,
                'merchantUserPhone' => $userPhone,
                'expiryTime' => now()->addMinutes(30)->toISOString(),
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v1/payment/create', $paymentData);

            if ($response->successful()) {
                $data = $response->json();

                // Simpan data transaksi ke database
                $this->saveTransaction($order, $data);

                return [
                    'success' => true,
                    'payment_url' => $data['paymentUrl'] ?? null,
                    'transaction_id' => $data['transactionId'] ?? null,
                    'qr_code' => $data['qrCode'] ?? null,
                    'deep_link' => $data['deepLink'] ?? null,
                ];
            }

            Log::error('DANA: Failed to create payment', $response->json());
            return ['success' => false, 'message' => 'Gagal membuat transaksi pembayaran'];

        } catch (\Exception $e) {
            Log::error('DANA: Error creating payment', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    /**
     * Cek status pembayaran
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            if (!$this->accessToken) {
                $this->getAccessToken();
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/v1/payment/status/' . $transactionId);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('DANA: Error checking payment status', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Handle webhook callback dari DANA
     */
    public function handleWebhook($payload, $signature)
    {
        try {
            // Verifikasi signature webhook
            if (!$this->verifyWebhookSignature($payload, $signature)) {
                Log::error('DANA: Invalid webhook signature');
                return false;
            }

            $transactionId = $payload['transactionId'] ?? null;
            $status = $payload['status'] ?? null;
            $orderId = $this->extractOrderId($payload['merchantTransId'] ?? '');

            if (!$transactionId || !$orderId) {
                Log::error('DANA: Missing transaction data in webhook');
                return false;
            }

            $order = Order::find($orderId);
            if (!$order) {
                Log::error('DANA: Order not found', ['order_id' => $orderId]);
                return false;
            }

            // Update status order berdasarkan status pembayaran
            switch ($status) {
                case 'SUCCESS':
                    $order->update(['status' => 'paid']);
                    // Kirim notifikasi ke seller
                    $this->notifySeller($order);
                    break;
                case 'FAILED':
                    $order->update(['status' => 'payment_failed']);
                    break;
                case 'EXPIRED':
                    $order->update(['status' => 'expired']);
                    break;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('DANA: Error handling webhook', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Simpan data transaksi ke database
     */
    protected function saveTransaction(Order $order, $paymentData)
    {
        // Buat model PaymentTransaction jika belum ada
        // Atau simpan ke kolom payment_data di tabel orders
        $order->update([
            'payment_data' => json_encode([
                'transaction_id' => $paymentData['transactionId'] ?? null,
                'payment_method' => 'dana',
                'payment_url' => $paymentData['paymentUrl'] ?? null,
                'qr_code' => $paymentData['qrCode'] ?? null,
                'deep_link' => $paymentData['deepLink'] ?? null,
                'created_at' => now(),
            ])
        ]);
    }

    /**
     * Verifikasi signature webhook
     */
    protected function verifyWebhookSignature($payload, $signature)
    {
        $expectedSignature = hash_hmac('sha256', json_encode($payload), $this->config['webhook_secret']);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Extract order ID dari merchant transaction ID
     */
    protected function extractOrderId($merchantTransId)
    {
        if (preg_match('/ORDER_(\d+)_/', $merchantTransId, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Notifikasi ke seller
     */
    protected function notifySeller(Order $order)
    {
        // Implementasi notifikasi ke seller
        // Bisa menggunakan event, notification, atau real-time chat
    }
}
