@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
</div>
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Total Pengguna</h5>
                    <p class="card-text fs-4">{{ $totalUsers ?? 'N/A' }}</p>
                </div>
                <i class="bi bi-people-fill card-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Total Penjual</h5>
                    <p class="card-text fs-4">{{ $totalSellers ?? 'N/A' }}</p>
                </div>
                <i class="bi bi-shop card-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Total Kantin</h5>
                    <p class="card-text fs-4">{{ $totalKantins ?? 'N/A' }}</p>
                </div>
                <i class="bi bi-building card-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Total Pesanan</h5>
                    <p class="card-text fs-4">{{ $totalOrders ?? 'N/A' }}</p>
                </div>
                <i class="bi bi-receipt card-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-dark">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Total Pendapatan</h5>
                    <p class="card-text fs-4">Rp{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                </div>
                <i class="bi bi-cash-stack card-icon"></i>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">Pesanan Terbaru</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? '-' }}</td>
                                <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td><span class="badge bg-{{ $order->status == 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">User Terbaru</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($latestUsers as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-circle me-2"></i>{{ $user->name }}</span>
                        <span class="badge bg-primary">{{ $user->created_at->format('d/m') }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center">Tidak ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-3 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">Penjual Terbaru</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($latestSellers as $seller)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-shop me-2"></i>{{ $seller->name }}</span>
                        <span class="badge bg-success">{{ $seller->created_at->format('d/m') }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center">Tidak ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
