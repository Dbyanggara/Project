@extends('layouts.app')

@section('title', 'Detail Pesanan #'. $order->id .' - KantinKu Seller')

@push('styles')
<style>
    .order-detail-card {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.07);
    }
    .order-detail-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }
    .order-detail-header h4 {
        margin-bottom: 0.25rem;
    }
    .order-detail-header .badge {
        font-size: 0.9rem;
    }
    .section-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #343a40;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #0d6efd;
        display: inline-block;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    .info-block p {
        margin-bottom: 0.5rem;
    }
    .info-block strong {
        display: block;
        color: #495057;
        margin-bottom: 0.2rem;
    }
    .items-table th {
        background-color: #e9ecef;
    }
    .price-summary dt {
        font-weight: normal;
        color: #6c757d;
    }
    .price-summary dd {
        font-weight: 600;
    }
    .price-summary .total {
        font-size: 1.25rem;
        color: #28a745;
    }
    .actions-bar {
        background-color: #f8f9fa;
        padding: 1rem 1.5rem;
        border-top: 1px solid #dee2e6;
    }
</style>
@endpush

@section('content')
<div class="container py-4" style="margin-top: 70px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
    </div>
    <div class="card order-detail-card">
        <div class="order-detail-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold">Detail Pesanan #{{ $order->id }}</h4>
                <span class="text-muted">Tanggal: {{ $order->order_date->format('d M Y, H:i') }}</span>
            </div>
            @php
                $statusClass = 'secondary';
                if ($order->status == 'Menunggu Konfirmasi') $statusClass = 'warning';
                elseif ($order->status == 'Diproses') $statusClass = 'primary';
                elseif ($order->status == 'Siap Diambil') $statusClass = 'info';
                elseif ($order->status == 'Selesai') $statusClass = 'success';
                elseif ($order->status == 'Dibatalkan') $statusClass = 'danger';
            @endphp
            <span class="badge bg-{{$statusClass}} fs-6">{{ $order->status }}</span>
        </div>
        <div class="card-body p-4">
            <div class="info-grid mb-4">
                <div class="info-block">
                    <h6 class="section-title">Informasi Pelanggan</h6>
                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email ?? '-' }}</p>
                    <p><strong>Telepon:</strong> {{ $order->customer_phone ?? '-' }}</p>
                </div>
                <div class="info-block">
                    <h6 class="section-title">Informasi Pengiriman/Pengambilan</h6>
                    <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
                    <p><strong>Status Pembayaran:</strong> <span class="badge bg-{{ $order->payment_status == 'Lunas' ? 'success-subtle text-success-emphasis' : 'warning-subtle text-warning-emphasis' }}">{{ $order->payment_status }}</span></p>
                    <p><strong>Alamat/Catatan:</strong> {{ $order->shipping_address ?? 'Tidak ada catatan' }}</p>
                </div>
            </div>
            <h6 class="section-title mt-4">Item Pesanan</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered items-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->product_name }}
                                @if($item->notes) <small class="d-block text-muted"><em>Catatan: {{ $item->notes }}</em></small> @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp{{ number_format($item->price_per_item, 0, ',', '.') }}</td>
                            <td class="text-end">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-end">
                <div class="col-md-5">
                    <h6 class="section-title">Ringkasan Pembayaran</h6>
                    <dl class="price-summary">
                        <div class="d-flex justify-content-between"> <dt>Subtotal Item:</dt> <dd>Rp{{ number_format($order->subtotal_amount, 0, ',', '.') }}</dd> </div>
                        <div class="d-flex justify-content-between"> <dt>Biaya Pengiriman:</dt> <dd>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</dd> </div>
                        <div class="d-flex justify-content-between"> <dt>Diskon:</dt> <dd>- Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</dd> </div>
                        <hr>
                        <div class="d-flex justify-content-between total"> <dt>Total Pembayaran:</dt> <dd>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</dd> </div>
                    </dl>
                </div>
            </div>
            @if($order->notes_from_customer)
            <h6 class="section-title mt-4">Catatan dari Pelanggan</h6>
            <p class="text-muted"><em>{{ $order->notes_from_customer }}</em></p>
            @endif
        </div>
        <div class="actions-bar text-end">
            <button class="btn btn-primary"><i class="bi bi-printer"></i> Cetak Struk</button>
        </div>
    </div>
</div>
@endsection
