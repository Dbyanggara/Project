@extends('layouts.app')

@section('title', 'Pesanan Saya - KantinKu')

@push('styles')
<style>
    .order-card {
        border: 1px solid #e0e0e0;
        border-radius: 0.75rem;
        transition: box-shadow 0.3s ease-in-out;
        background-color: #fff;
    }
    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .order-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
    }
    .order-card .kantin-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.375rem;
    }
    .order-item-list {
        font-size: 0.9rem;
        color: #495057;
    }
    .order-status {
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.25rem 0.6rem;
        border-radius: 0.25rem;
    }
    .order-total {
        font-weight: bold;
        font-size: 1.1rem;
    }
    .empty-orders {
        text-align: center;
        padding: 50px 20px;
        color: #6c757d;
    }
    .empty-orders i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    .btn-detail-pesanan {
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Riwayat Pesanan Saya</h2>
    </div>
    @if($orders->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body empty-orders">
                <i class="bi bi-cart-x"></i>
                <p class="h5">Anda belum memiliki riwayat pesanan.</p>
                <p>Yuk, mulai pesan makanan favoritmu di KantinKu!</p>
                <a href="{{ route('user.dashboard') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-shop"></i> Jelajahi Kantin
                </a>
            </div>
        </div>
    @else
        @foreach($orders as $order)
        <div class="card order-card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Pesanan: <strong>{{ $order->id }}</strong></span>
                <span class="badge bg-{{ $order->status_color }} order-status">{{ $order->status }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $order->kantin_image }}" alt="{{ $order->kantin_name }}" class="kantin-img me-3">
                            <div>
                                <h5 class="mb-0">{{ $order->kantin_name }}</h5>
                                <small class="text-muted">{{ $order->order_date->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        <ul class="list-unstyled order-item-list mb-0">
                            @foreach($order->items as $item)
                            <li>{{ $item->quantity }}x {{ $item->name }} <span class="text-muted">(@ Rp {{ number_format($item->price, 0, ',', '.') }})</span></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <p class="mb-1 text-muted">Total Pembayaran:</p>
                        <p class="order-total text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <a href="{{ $order->link_detail }}" class="btn btn-outline-primary btn-sm btn-detail-pesanan">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
