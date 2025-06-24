@extends('layouts.app') {{-- Menggunakan layout app utama --}}

@section('title', 'Laporan Penjualan')

@push('styles') {{-- Keep other page-specific styles if any --}}
<style>
    .report-header {
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1rem;
    }
    .chart-card {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.07);
    }
    .chart-container {
        position: relative;
        height: 350px; /* Sesuaikan tinggi chart */
        width: 100%;
    }
    .filter-buttons .btn {
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
{{-- Content now relies on the navbar from layouts.app.blade.php --}}
{{-- The body already has padding-top, so margin-top on this container is not needed. --}}
<div class="container py-4">
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

    <div class="card chart-card">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center">
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
                {{-- Tambahkan filter custom range jika perlu --}}
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

    {{-- Anda bisa menambahkan tabel ringkasan data di bawah chart jika diperlukan --}}
    {{--
    <div class="card mt-4">
        <div class="card-header"><h5 class="mb-0 fw-semibold">Ringkasan Data</h5></div>
        <div class="card-body">
            <p>Total Pendapatan ({{ $filterPeriode }}): Rp ...</p>
            <p>Total Pesanan ({{ $filterPeriode }}): ...</p>
        </div>
    </div>
    --}}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesReportChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line', // atau 'bar'
                data: {
                    labels: @json($chartLabels), // Data dari controller
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($chartData), // Data dari controller
                        borderColor: 'rgb(37, 99, 235)', // Warna biru primer
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } },
                        x: { grid: { display: false } }
                    },
                    plugins: { legend: { display: true, position: 'top' } }
                }
            });
        }
    });
</script>
@endpush
