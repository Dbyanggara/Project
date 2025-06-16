@extends('layouts.app')

@section('title', 'Manajemen Pesanan - KantinKu Seller')

@push('styles')
<style>
    /* Navbar styles are now inherited from layouts.app.blade.php */

    .order-nav-filters .nav-link {
        color: var(--bs-secondary-color); /* Menggunakan variabel Bootstrap untuk teks muted */
        border: 1px solid transparent;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        font-weight: 500;
        padding: 0.75rem 1rem;
    }
    .order-nav-filters .nav-link.active,
    .order-nav-filters .nav-link:hover {
        color: var(--app-link-color);
        border-bottom-color: var(--app-link-color);
    }
    /* Style tambahan untuk teks pada filter aktif */
    .order-nav-filters .nav-link.active {
        background-color: var(--bs-primary-bg-subtle, #cfe2ff); /* Fallback jika var tidak ada */
        color: var(--bs-primary-text-emphasis, #084298) !important; /* Fallback jika var tidak ada */
        border-radius: 0.375rem 0.375rem 0 0; /* Sedikit rounded corner di atas */
    }
    .order-card-modern {
        background-color: var(--app-card-bg);
        border-radius: 0.75rem;
        box-shadow: var(--app-shadow-sm); /* Menggunakan shadow dari tema */
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .order-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .order-card-modern .card-header {
        background-color: var(--app-secondary-bg);
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        border-bottom: 1px solid var(--app-card-border-color);
    }
    .order-card-modern .card-body {
        padding: 1.25rem;
    }
    .order-card-modern .order-id {
        font-weight: 600;
        color: var(--app-text-color);
    }
    .order-card-modern .order-time {
        font-size: 0.85rem;
        color: var(--bs-secondary-color);
    }
    .order-card-modern .order-status-badge {
        font-size: 0.8rem;
        padding: 0.3em 0.7em;
    }
    .order-card-modern .items-summary {
        font-size: 0.9rem;
        color: var(--app-text-color);
    }
    .order-card-modern .total-amount {
        font-size: 1.1rem;
        font-weight: 700;
        color: #28a745;
    }
    .order-actions .btn {
        font-size: 0.85rem;
        padding: 0.375rem 0.75rem;
    }
    .empty-orders-container {
        text-align: center;
        padding: 3rem 1rem;
        background-color: var(--app-card-bg);
        border-radius: 0.75rem;
        box-shadow: var(--app-shadow-sm);
    }
    .empty-orders-container i {
        font-size: 3.5rem;
        color: var(--bs-secondary-color);
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
{{-- Navbar Seller sekarang di-handle oleh layouts.app.blade.php --}}
<div class="container py-4"> {{-- style="margin-top: 80px;" dihapus --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Pesanan</h2>
    </div>
    <ul class="nav nav-pills order-nav-filters mb-4">
        @php
            $statuses = [
                'semua' => 'Semua Pesanan',
                'menunggu-konfirmasi' => 'Menunggu Konfirmasi',
                'diproses' => 'Diproses',
                'siap-diambil' => 'Siap Diambil',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
            ];
        @endphp
        @foreach($statuses as $key => $displayName)
        <li class="nav-item">
            <a class="nav-link {{ (request('status', 'semua') == $key) ? 'active' : '' }}"
               href="{{ route('seller.orders.index', ['status' => $key]) }}">
                {{ $displayName }}
            </a>
        </li>
        @endforeach
    </ul>
    @if($orders->isEmpty())
        <div class="empty-orders-container">
            <i class="bi bi-cart-x"></i>
            <h4 class="fw-semibold">Tidak Ada Pesanan</h4>
            <p class="text-muted">Belum ada pesanan dengan status "{{ $statuses[$statusFilter ?? 'semua'] ?? 'yang dipilih' }}".</p>
        </div>
    @else
        <div class="row">
            @foreach($orders as $order)
            <div class="col-lg-6">
                <div class="order-card-modern">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="order-id">ID: {{ $order->id }}</span>
                        @php
                            $statusClass = 'secondary';
                            if ($order->status == 'Menunggu Konfirmasi') $statusClass = 'warning';
                            elseif ($order->status == 'Diproses') $statusClass = 'primary';
                            elseif ($order->status == 'Siap Diambil') $statusClass = 'info';
                            elseif ($order->status == 'Selesai') $statusClass = 'success';
                            elseif ($order->status == 'Dibatalkan') $statusClass = 'danger';
                        @endphp
                        <span class="badge rounded-pill bg-{{$statusClass}} order-status-badge">{{ $order->status }}</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Pelanggan:</strong> {{ $order->customer_name }}
                        </div>
                        <div class="mb-2 order-time">
                            <i class="bi bi-clock"></i> {{ $order->order_date->format('d M Y, H:i') }} ({{ $order->order_date->diffForHumans() }})
                        </div>
                        <div class="mb-2 items-summary">
                            <strong>Item:</strong> {{ $order->items_summary }}
                        </div>
                        <div class="mb-3">
                            <strong>Pembayaran:</strong>
                            <span class="badge bg-{{ $order->payment_status == 'Lunas' ? 'success-subtle text-success-emphasis' : 'warning-subtle text-warning-emphasis' }}">{{ $order->payment_status }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="total-amount">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                            <div class="order-actions">
                                <a href="{{ route('seller.orders.show', ['orderId' => $order->id]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Detail</a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-pencil-square"></i> Ubah Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @foreach(['Menunggu Konfirmasi', 'Diproses', 'Siap Diambil', 'Selesai', 'Dibatalkan'] as $newStatus)
                                            @if($order->status !== $newStatus)
                                            <li>
                                                <form action="{{ route('seller.orders.updateStatus', ['orderId' => $order->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $newStatus }}">
                                                    <button type="submit" class="dropdown-item">
                                                        {{ $newStatus }}
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
