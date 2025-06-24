@extends('layouts.app') {{-- Menggunakan layout app utama --}}

@section('title', 'Laporan Penjualan')

@push('styles')
<style>
    /* Navbar styles are now inherited from layouts.app.blade.php */

    .report-header {
        border-bottom: 1px solid var(--app-card-border-color); /* Menggunakan variabel tema */
        padding-bottom: 1rem;
    }
    .chart-card {
        border-radius: 0.75rem;
        box-shadow: var(--app-shadow-sm); /* Menggunakan shadow dari tema */
        background-color: var(--app-card-bg); /* Menggunakan variabel tema */
    }
    .chart-container {
        position: relative;
        height: 350px; /* Sesuaikan tinggi chart */
        width: 100%;
    }
    .filter-buttons .btn {
        margin-right: 0.5rem;
    }
    .chart-card .card-header { /* Menyesuaikan card-header di dalam chart-card */
        background-color: var(--app-secondary-bg);
        border-bottom: 1px solid var(--app-card-border-color);
    }
    .summary-card {
        border-radius: 0.75rem;
        box-shadow: var(--app-shadow-sm);
        background-color: var(--app-card-bg);
        transition: transform 0.2s;
    }
    .summary-card:hover {
        transform: translateY(-2px);
    }
    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .top-selling-item {
        padding: 0.75rem;
        border-radius: 0.5rem;
        background-color: var(--app-secondary-bg);
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
{{-- Navbar Seller sekarang di-handle oleh layouts.app.blade.php --}}
<div class="container py-4"> {{-- style="margin-top: 80px;" dihapus --}}
    <div class="report-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Laporan Penjualan</h1>
        {{-- Tombol Aksi Tambahan, misal Export --}}
        {{-- <button class="btn btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Export ke Excel</button> --}}
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Ringkasan Data --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="summary-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Total Pendapatan</h6>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($summary['totalRevenue'], 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="summary-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Total Pesanan</h6>
                            <h4 class="mb-0 fw-bold">{{ number_format($summary['totalOrders']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="summary-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Rata-rata Pesanan</h6>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($summary['averageOrderValue'], 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="summary-icon bg-warning bg-opacity-10 text-warning me-3">
                            <i class="bi bi-star"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Item Terlaris</h6>
                            <h4 class="mb-0 fw-bold">{{ count($summary['topSellingItems']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Grafik Pendapatan --}}
        <div class="col-lg-8 mb-4">
            <div class="card chart-card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold me-3">Grafik Pendapatan</h5>
                    <div class="filter-buttons mt-2 mt-md-0">
                        <form method="GET" action="{{ route('seller.reports.index') }}" class="d-inline">
                            <button name="periode" value="hari_ini" type="submit" class="btn btn-sm {{ $filterPeriode == 'hari_ini' ? 'btn-primary' : 'btn-outline-secondary' }}">Hari Ini</button>
                        </form>
                        <form method="GET" action="{{ route('seller.reports.index') }}" class="d-inline">
                            <button name="periode" value="minggu_ini" type="submit" class="btn btn-sm {{ $filterPeriode == 'minggu_ini' ? 'btn-primary' : 'btn-outline-secondary' }}">Minggu Ini</button>
                        </form>
                        <form method="GET" action="{{ route('seller.reports.index') }}" class="d-inline">
                            <button name="periode" value="bulan_ini" type="submit" class="btn btn-sm {{ $filterPeriode == 'bulan_ini' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Ini</button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container">
                        <canvas id="salesReportChart"></canvas>
                    </div>
                    @if(empty($chartData))
                    <p class="text-center text-muted mt-3">Data tidak tersedia untuk periode yang dipilih.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Item Terlaris --}}
        <div class="col-lg-4 mb-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">Item Terlaris</h5>
                </div>
                <div class="card-body">
                    @if(count($summary['topSellingItems']) > 0)
                        @foreach($summary['topSellingItems'] as $index => $item)
                        <div class="top-selling-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                    <span class="fw-medium">{{ $item->name }}</span>
                                </div>
                                <span class="text-muted">{{ $item->total_sold }} terjual</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-center text-muted">Belum ada data penjualan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesReportChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($chartData),
                        borderColor: 'rgb(37, 99, 235)',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.2,
                        fill: true,
                        pointBackgroundColor: 'rgb(37, 99, 235)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
