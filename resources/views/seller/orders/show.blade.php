@extends('layouts.app')

@section('title', 'Detail Pesanan #'. $order->id .' - KantinKu Seller')

@push('styles')
<style>
    .order-detail-card {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        background-color: var(--app-card-bg);
    }
    .order-detail-header {
        background-color: var(--app-secondary-bg);
        border-bottom: 1px solid var(--app-card-border-color);
        padding: 1rem 1.5rem;
    }
    .order-detail-header h4 {
        margin-bottom: 0.25rem;
        color: var(--app-text-color);
    }
    .order-detail-header .badge {
        font-size: 0.9rem;
    }
    .section-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--app-text-color);
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--app-link-color);
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
        color: var(--bs-secondary-color);
        margin-bottom: 0.2rem;
        font-size: 0.9rem;
    }
    .items-table th {
        background-color: var(--app-secondary-bg);
        color: var(--app-text-color);
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
        background-color: transparent;
        padding: 1.5rem;
        border-top: 1px solid var(--app-card-border-color);
    }
    /* Pastikan dropdown tidak terpotong */
    .btn-group { position: relative; }
    .dropdown-menu { z-index: 1050; }
    /* Pastikan dropdown berfungsi dengan baik */
    .dropdown { position: relative; }
    .dropdown-menu {
        z-index: 1050;
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 10rem;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.175);
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
    }
    .dropdown-menu.show {
        display: block !important;
    }
    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.25rem 1rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
    }
    .dropdown-item:hover {
        color: #1e2125;
        background-color: #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="container py-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
    </div>
    <div class="card order-detail-card">
        <div class="order-detail-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold">Detail Pesanan #{{ $order->id }}</h4>
                <span class="text-muted">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</span>
            </div>
            <span class="badge rounded-pill bg-{{$order->status_color}} fs-6">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="card-body p-4">
            <div class="info-grid mb-4">
                <div class="info-block">
                    <h6 class="section-title">Informasi Pelanggan</h6>
                    <p><strong>Nama:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
                    <p><strong>Nomor Meja:</strong> {{ $order->shipping_address['nomor_meja'] ?? 'N/A' }}</p>
                </div>
                <div class="info-block">
                    <h6 class="section-title">Informasi Pembayaran</h6>
                    <p><strong>Metode Pembayaran:</strong> <span class="badge bg-{{ $order->payment_method == 'cod' ? 'info-subtle text-info-emphasis' : 'primary-subtle text-primary-emphasis' }}">{{ strtoupper($order->payment_method) }}</span></p>
                    <p><strong>Status Pembayaran:</strong>
                        <span class="badge bg-{{ $order->status == 'paid' || $order->status == 'completed' ? 'success-subtle text-success-emphasis' : 'warning-subtle text-warning-emphasis' }}">
                            {{ $order->status == 'paid' || $order->status == 'completed' ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </p>
                    <p><strong>Total:</strong> <strong class="fs-5 text-success">Rp{{ number_format($order->total, 0, ',', '.') }}</strong></p>
                </div>
            </div>
            <h6 class="section-title mt-4">Item Pesanan</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered items-table">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->menu->name ?? 'Produk Dihapus' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    @if($order->notes)
                        <h6 class="section-title mt-4">Catatan dari Pelanggan</h6>
                        <p class="text-muted"><em>{{ $order->notes }}</em></p>
                    @endif
                </div>
                <div class="col-md-6">
                    <h6 class="section-title mt-4">Ringkasan Pembayaran</h6>
                    <dl class="price-summary">
                         @php
                            $subtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
                            // Biaya-biaya lain bisa ditambahkan di sini jika disimpan di DB
                            $ongkir = 5000;
                            $biayaLayanan = 2000;
                            $diskon = ($subtotal + $ongkir + $biayaLayanan) - $order->total;
                        @endphp
                        <div class="d-flex justify-content-between"> <dt>Subtotal Item:</dt> <dd>Rp{{ number_format($subtotal, 0, ',', '.') }}</dd> </div>
                        <div class="d-flex justify-content-between"> <dt>Ongkos Kirim:</dt> <dd>Rp{{ number_format($ongkir, 0, ',', '.') }}</dd> </div>
                        <div class="d-flex justify-content-between"> <dt>Biaya Layanan:</dt> <dd>Rp{{ number_format($biayaLayanan, 0, ',', '.') }}</dd> </div>
                         @if ($diskon > 0)
                        <div class="d-flex justify-content-between text-danger"> <dt>Diskon:</dt> <dd>- Rp{{ number_format($diskon, 0, ',', '.') }}</dd> </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between total"> <dt>Total Pembayaran:</dt> <dd>Rp{{ number_format($order->total, 0, ',', '.') }}</dd> </div>
                    </dl>
                </div>
            </div>
        </div>
        {{-- Aksi Cepat telah dipindahkan ke halaman daftar pesanan untuk UX yang lebih baik --}}
    </div>
    <div style="height: 40px; background: transparent;"></div>
</div>
@endsection

@push('scripts')
<script>
// Tidak ada script khusus yang dibutuhkan di sini lagi.
</script>
@endpush
