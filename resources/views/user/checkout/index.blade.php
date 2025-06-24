@extends('layouts.app')

@section('title', 'Checkout - Selesaikan Pesanan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout Pesanan</h1>
            <p class="text-gray-600">Selesaikan pesanan Anda dengan mengisi detail di bawah ini</p>
        </div>

        <form action="{{ route('user.checkout.process') }}" method="POST">
            @csrf

            <!-- Debug: Show any validation errors -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Ada kesalahan dalam form:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Checkout -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Alamat Pengiriman -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-semibold text-gray-900">Lokasi Pengiriman</h2>
                                <p class="text-sm text-gray-500">Informasi lokasi pengiriman pesanan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" value="{{ auth()->user()->name }}" readonly>
                                <p class="text-xs text-gray-500 mt-1">Nama diambil dari profil Anda</p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" value="{{ auth()->user()->email }}" readonly>
                                <p class="text-xs text-gray-500 mt-1">Email diambil dari profil Anda</p>
                            </div>
                            <div>
                                <label for="nomor_meja" class="block text-sm font-medium text-gray-700 mb-2">Nomor Meja</label>
                                <input type="text" id="nomor_meja" name="nomor_meja" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: A1, B3, C5, dll." required>
                                <p class="text-xs text-gray-500 mt-1">Masukkan nomor meja tempat Anda duduk</p>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-semibold text-gray-900">Metode Pembayaran</h2>
                                <p class="text-sm text-gray-500">Pilih metode pembayaran yang tersedia</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <!-- E-Wallet Section -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">E-Wallet</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="gopay" class="sr-only" required>
                                        <div class="payment-card">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" alt="GoPay" class="h-6">
                                            <span class="text-sm font-medium">GoPay</span>
                                        </div>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="ovo" class="sr-only" required>
                                        <div class="payment-card">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" alt="OVO" class="h-6">
                                            <span class="text-sm font-medium">OVO</span>
                                        </div>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="dana" class="sr-only" required>
                                        <div class="payment-card">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="DANA" class="h-6">
                                            <span class="text-sm font-medium">DANA</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- COD Section -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">Bayar di Tempat</h3>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="cod" class="sr-only" required>
                                    <div class="payment-card">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Bayar di Tempat (COD)</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Pesanan -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-semibold text-gray-900">Catatan Pesanan</h2>
                                <p class="text-sm text-gray-500">Tambahkan catatan khusus untuk pesanan Anda</p>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="Contoh: Tidak pedas, tambah sambal, dll."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-semibold text-gray-900">Ringkasan Pesanan</h2>
                                <p class="text-sm text-gray-500">Detail pesanan Anda</p>
                            </div>
                        </div>

                        <!-- Item List -->
                        <div class="space-y-4 mb-6">
                            @foreach($orderSummary['items'] as $item)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $item['name'] }}</h4>
                                    <p class="text-xs text-gray-500">{{ $item['quantity'] }}x @ Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 border-t pt-4">
                            <div class="flex justify-between text-lg font-semibold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-blue-600">Rp{{ number_format($orderSummary['total'], 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button type="submit" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>Buat Pesanan</span>
                            </div>
                        </button>

                        <!-- Security Notice -->
                        <div class="mt-4 text-center">
                            <div class="flex items-center justify-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span>Pembayaran aman & terenkripsi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.payment-option {
    cursor: pointer;
    display: block;
}

.payment-option input:checked + .payment-card {
    border-color: #3b82f6;
    background-color: #eff6ff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.payment-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    background-color: #ffffff;
    transition: all 0.2s ease-in-out;
}

.payment-card:hover {
    border-color: #d1d5db;
    background-color: #f9fafb;
}

.payment-card img {
    object-fit: contain;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    const checkoutForm = document.querySelector('form[action*="checkout/process"]');

    // Debug: Log form element
    console.log('Checkout form found:', checkoutForm);

    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Hapus kelas 'selected' dari semua opsi
            const allCards = document.querySelectorAll('.payment-card');
            allCards.forEach(card => card.classList.remove('selected'));

            // Tambahkan kelas 'selected' ke opsi yang dipilih
            const selectedCard = this.closest('.payment-option').querySelector('.payment-card');
            selectedCard.classList.add('selected');
        });
    });

    // Handle form submission
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            console.log('Form submitted');

            // Check if payment method is selected
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPayment) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran');
                return false;
            }

            // Check if nomor meja is filled
            const nomorMeja = document.getElementById('nomor_meja').value.trim();
            if (!nomorMeja) {
                e.preventDefault();
                alert('Silakan isi nomor meja');
                return false;
            }

            console.log('Form validation passed, submitting...');
        });
    }
});
</script>
@endsection
