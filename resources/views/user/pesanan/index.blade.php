@extends('layouts.app')

@section('title', 'Pesanan Saya')

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
        <div id="user-orders-list">
            @foreach($orders as $order)
                @include('user.pesanan._order_card', ['order' => $order])
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // (Echo listener for .order.completed removed, now handled globally)
});
</script>
@endpush
