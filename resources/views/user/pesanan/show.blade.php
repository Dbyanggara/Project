@extends('layouts.app')

@section('title', 'Detail Pesanan')

@push('styles')
<style>
    .order-detail-card {
        border: 1px solid #e0e0e0;
        border-radius: 0.75rem;
        background-color: #fff;
    }
    .order-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .order-status:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .order-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 0;
    }
    .order-item:last-child {
        border-bottom: none;
    }
    .kantin-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
    }
    .payment-method-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        background-color: #e3f2fd;
        color: #1976d2;
        font-weight: 500;
    }

    /* Custom status colors yang konsisten dengan tema */
    .badge.bg-primary {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7) !important;
        border: 1px solid #0a58ca;
    }
    .badge.bg-success {
        background: linear-gradient(135deg, #198754, #157347) !important;
        border: 1px solid #146c43;
    }
    .badge.bg-info {
        background: linear-gradient(135deg, #0dcaf0, #0aa2c0) !important;
        border: 1px solid #099aa7;
    }
    .badge.bg-danger {
        background: linear-gradient(135deg, #dc3545, #bb2d3b) !important;
        border: 1px solid #b02a37;
    }
    .badge.bg-secondary {
        background: linear-gradient(135deg, #6c757d, #5c636a) !important;
        border: 1px solid #565e64;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Detail Pesanan</h2>
        <a href="{{ route('user.pesanan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Pesanan
        </a>
    </div>

    @if($order)
    <div class="row">
        <div class="col-lg-8">
            <!-- Order Info -->
            <div class="card order-detail-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Pesanan</h5>
                    <span class="badge bg-{{ $order->status_color }} order-status">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>ID Pesanan:</strong><br>
                            <span class="text-muted">{{ $order->id }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Pesanan:</strong><br>
                            <span class="text-muted">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'Tanggal tidak tersedia' }}</span>
                        </div>
                    </div>

                    @if($order->payment_method)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Metode Pembayaran:</strong><br>
                            <span class="payment-method-badge">{{ strtoupper($order->payment_method) }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Total Pembayaran:</strong><br>
                            <span class="h5 text-primary mb-0">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($order->notes)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Catatan:</strong><br>
                            <span class="text-muted">{{ $order->notes }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Kantin Info -->
            @if($order->orderItems->first() && $order->orderItems->first()->menu && $order->orderItems->first()->menu->kantin)
            <div class="card order-detail-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Kantin</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ $order->orderItems->first()->menu->kantin->image_url }}"
                             alt="{{ $order->orderItems->first()->menu->kantin->name }}"
                             class="kantin-img me-3"
                             onerror="this.src='{{ asset('img/logo1.png') }}'">
                        <div>
                            <h5 class="mb-1">{{ $order->orderItems->first()->menu->kantin->name }}</h5>
                            <p class="text-muted mb-0">{{ $order->orderItems->first()->menu->kantin->address ?? 'Alamat tidak tersedia' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Items -->
            <div class="card order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                    <div class="order-item">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                @if($item->menu)
                                <h6 class="mb-1">{{ $item->menu->name }}</h6>
                                <p class="text-muted mb-0">{{ $item->menu->description ?? 'Tidak ada deskripsi' }}</p>
                                @else
                                <h6 class="mb-1">Menu (tidak tersedia)</h6>
                                <p class="text-muted mb-0">Menu ini mungkin telah dihapus</p>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge bg-light text-dark">{{ $item->quantity }}x</span>
                            </div>
                            <div class="col-md-2 text-end">
                                <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Shipping Info -->
            @if($order->shipping_address)
            <div class="card order-detail-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Nama:</strong><br>
                        <span class="text-muted">{{ $order->shipping_address['nama'] ?? 'Tidak tersedia' }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong><br>
                        <span class="text-muted">{{ $order->shipping_address['email'] ?? 'Tidak tersedia' }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Nomor Meja:</strong><br>
                        <span class="text-muted">{{ $order->shipping_address['nomor_meja'] ?? 'Tidak tersedia' }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Summary -->
            <div class="card order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkos Kirim:</span>
                        <span>Rp 5.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Layanan:</span>
                        <span>Rp 2.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Diskon:</span>
                        <span class="text-success">- Rp 5.000</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong class="text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body text-center">
            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
            <h5 class="mt-3">Pesanan tidak ditemukan</h5>
            <p class="text-muted">Pesanan yang Anda cari tidak ditemukan atau Anda tidak memiliki akses ke pesanan ini.</p>
            <a href="{{ route('user.pesanan.index') }}" class="btn btn-primary">Kembali ke Pesanan</a>
        </div>
    </div>
    @endif
</div>
@endsection
