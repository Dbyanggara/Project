@extends('layouts.app')

@section('title', 'Dashboard Penjual - KantinKu')

@push('styles')
<style>
    /* Navbar styles are now inherited from layouts.app.blade.php */

    /* Statistik Card */
    .stat-card {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        background-color: var(--app-card-bg); /* Menggunakan variabel tema */
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        border: none;
        min-height: 120px;
        animation: fadeInUp 0.5s;
    }
    .stat-card:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 8px 24px rgba(37,99,235,0.10);
    }
    .stat-card .stat-icon {
        font-size: 2.2rem;
        opacity: 0.85;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Tabel & List */
    .table-hover tbody tr:hover {
        background-color: var(--app-secondary-bg); /* Menggunakan variabel tema untuk hover */
        transition: background 0.2s;
    }
    .product-img-sm {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 1px solid var(--app-card-border-color); /* Menggunakan variabel tema */
    }
    .section-card {
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        background-color: var(--app-card-bg); /* Menggunakan variabel tema */
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s;
    }
    .section-card .card-header { /* Menyesuaikan card-header di dalam section-card */
        background-color: var(--app-secondary-bg);
        border-bottom: 1px solid var(--app-card-border-color);
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    /* Media query untuk seller-navbar sudah ada di layouts.app.blade.php */
</style>
@endpush

@section('content')
{{-- Navbar Seller sekarang di-handle oleh layouts.app.blade.php --}}
<div class="container py-5"> {{-- style="margin-top: 80px;" dihapus, padding-top body dari layout utama yang mengatur --}}
    <div class="mb-4">
        <h2 class="fw-bold">Halo, {{ $sellerName ?? 'Penjual' }}!</h2>
        <p class="text-muted">Berikut adalah ringkasan data Anda hari ini.</p>
    </div>
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card text-success border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3"><i class="bi bi-cash-coin"></i></div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Pendapatan Hari Ini</h6>
                        <h4 class="card-title fw-bold">Rp{{ number_format($todaySales, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card text-primary border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3"><i class="bi bi-receipt"></i></div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Pesanan Hari Ini</h6>
                        <h4 class="card-title fw-bold">{{ $todayOrdersCount }} <small>pesanan</small></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card text-info border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3"><i class="bi bi-star-fill"></i></div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Menu Terlaris</h6>
                        <h4 class="card-title fw-bold">{{ $bestSellingMenu->name }} <small>({{ $bestSellingMenu->count }} porsi)</small></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card text-warning border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3"><i class="bi bi-hand-thumbs-up"></i></div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Rating Kantin</h6>
                        <h4 class="card-title fw-bold"><i class="bi bi-star-fill"></i>{{ $canteenRating->rating }} <small>({{ $canteenRating->reviews }} ulasan)</small></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-5">
        <div class="col-lg-7">
            <div class="card section-card">
                <div class="card-header d-flex justify-content-between align-items-center"> {{-- bg-light dihapus, style diatur via CSS --}}
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-list-check me-2"></i>Pesanan Aktif</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua Pesanan</a>
                </div>
                <div class="card-body p-0">
                    @if($activeOrders->isEmpty())
                        <p class="text-center text-muted p-4">Tidak ada pesanan aktif saat ini.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Pemesan</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->order_time->format('H:i') }} <small class="text-muted d-block">{{ $order->order_time->diffForHumans() }}</small></td>
                                    {{-- Untuk badge, sebaiknya gunakan class Bootstrap yang theme-aware seperti bg-body-secondary atau bg-*-subtle --}}
                                    {{-- Contoh: <span class="badge bg-body-secondary {{ $order->status_class }} border border-1">{{ $order->status }}</span> --}}
                                    {{-- Atau, jika $order->status_class sudah termasuk warna background: <span class="badge {{ $order->status_class }}">{{ $order->status }}</span> --}}
                                    <td><span class="badge bg-light-subtle {{ $order->status_class }} border border-secondary-subtle">{{ $order->status }}</span></td> {{-- Menggunakan bg-light-subtle sebagai contoh, sesuaikan $order->status_class --}}
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-info me-1" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-secondary" title="Ubah Status"><i class="bi bi-pencil"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card section-card">
                <div class="card-header d-flex justify-content-between align-items-center"> {{-- bg-light dihapus --}}
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-2"></i>Produk Saya</h5>
                    <a href="{{ route('seller.menus.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle"></i> Tambah Produk</a>
                </div>
                <div class="card-body p-0">
                     @if($products->isEmpty())
                        <p class="text-center text-muted p-4">Anda belum menambahkan produk.</p>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach($products as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img-sm me-3">
                                <div>
                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                    <small class="text-muted">Rp{{ number_format($product->price, 0, ',', '.') }} - Stok: {{ $product->stock }}</small>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-sm btn-outline-secondary me-1" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                 @if(!$products->isEmpty())
                <div class="card-footer text-center"> {{-- bg-light dihapus, akan mengikuti style .section-card .card-header jika diperlukan atau default card-footer --}}
                    <a href="{{ route('seller.menus.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Produk</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
