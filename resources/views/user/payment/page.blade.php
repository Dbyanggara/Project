@extends('layouts.app')

@section('title', 'Pembayaran - Pesanan #' . $order->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran</h1>
                <p class="text-gray-600">Selesaikan pembayaran untuk pesanan Anda</p>
            </div>

            <!-- Payment Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <!-- Order Info -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Pesanan #{{ $order->id }}</span>
                        <span class="text-lg font-semibold text-blue-600">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $order->orderItems->first()->menu->name ?? 'Menu' }}
                        ({{ $order->orderItems->first()->quantity ?? 1 }}x)
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Metode Pembayaran</h3>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        @if($paymentData['payment_method'] === 'dana')
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="DANA" class="h-8">
                            <span class="font-medium">DANA</span>
                        @elseif($paymentData['payment_method'] === 'gopay')
                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" alt="GoPay" class="h-8">
                            <span class="font-medium">GoPay</span>
                        @elseif($paymentData['payment_method'] === 'ovo')
                            <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" alt="OVO" class="h-8">
                            <span class="font-medium">OVO</span>
                        @endif
                    </div>
                </div>

                <!-- QR Code -->
                @if(isset($paymentData['qr_code']))
                <div class="mb-6 text-center">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Scan QR Code</h3>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 inline-block">
                        <img src="data:image/png;base64,{{ $paymentData['qr_code'] }}"
                             alt="QR Code Pembayaran"
                             class="w-48 h-48 mx-auto">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Buka aplikasi {{ strtoupper($paymentData['payment_method']) }} dan scan QR code di atas
                    </p>
                </div>
                @endif

                <!-- Deep Link Button -->
                @if(isset($paymentData['deep_link']))
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Atau Klik Tombol di Bawah</h3>
                    <a href="{{ $paymentData['deep_link'] }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>Buka Aplikasi {{ strtoupper($paymentData['payment_method']) }}</span>
                    </a>
                </div>
                @endif

                <!-- Payment URL -->
                @if(isset($paymentData['payment_url']))
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Atau Buka Link Pembayaran</h3>
                    <a href="{{ $paymentData['payment_url'] }}"
                       target="_blank"
                       class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        <span>Buka Halaman Pembayaran</span>
                    </a>
                </div>
                @endif

                <!-- Status Check -->
                <div class="mb-6">
                    <div id="payment-status" class="text-center">
                        <div class="flex items-center justify-center space-x-2 text-gray-600">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                            <span>Memeriksa status pembayaran...</span>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Instruksi Pembayaran:</h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li>• Pastikan aplikasi {{ strtoupper($paymentData['payment_method']) }} sudah terinstall</li>
                        <li>• Scan QR code atau klik tombol di atas</li>
                        <li>• Masukkan PIN atau konfirmasi pembayaran</li>
                        <li>• Tunggu konfirmasi dari sistem</li>
                        <li>• Pembayaran akan otomatis terkonfirmasi</li>
                    </ul>
                </div>

                <!-- Back Button -->
                <div class="mt-6 text-center">
                    <a href="{{ route('user.pesanan.index') }}"
                       class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        ← Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusElement = document.getElementById('payment-status');
    const orderId = {{ $order->id }};

    // Check payment status every 5 seconds
    function checkPaymentStatus() {
        fetch(`/user/payment/${orderId}/status`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    statusElement.innerHTML = `
                        <div class="flex items-center justify-center space-x-2 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Pembayaran Berhasil!</span>
                        </div>
                    `;

                    // Redirect after 3 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("user.pesanan.index") }}';
                    }, 3000);

                } else if (data.status === 'FAILED') {
                    statusElement.innerHTML = `
                        <div class="flex items-center justify-center space-x-2 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="font-medium">Pembayaran Gagal</span>
                        </div>
                    `;
                } else if (data.status === 'EXPIRED') {
                    statusElement.innerHTML = `
                        <div class="flex items-center justify-center space-x-2 text-orange-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Pembayaran Kadaluarsa</span>
                        </div>
                    `;
                } else {
                    // Continue checking
                    setTimeout(checkPaymentStatus, 5000);
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
                setTimeout(checkPaymentStatus, 5000);
            });
    }

    // Start checking
    checkPaymentStatus();
});
</script>
@endsection
