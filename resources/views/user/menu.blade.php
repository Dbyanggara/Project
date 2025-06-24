@extends('layouts.app')

@section('content')
<style>
    .filter-btn.active, .filter-btn:focus {
        background: #2563eb;
        color: #fff;
    }
    .kantin-card {
        border-radius: 1.25rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.08);
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
        position: relative;
    }
    .kantin-card:hover {
        box-shadow: 0 8px 32px rgba(99,102,241,0.16);
        transform: translateY(-2px) scale(1.02);
    }
    .kantin-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .kantin-status {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #22c55e;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.2rem 0.8rem;
        z-index: 2;
    }
    .kantin-status.closed {
        background: #ef4444;
    }
    .bottom-nav {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        background: #fff;
        box-shadow: 0 -2px 16px rgba(0,0,0,0.06);
        z-index: 1000;
        border-top-left-radius: 1.2rem;
        border-top-right-radius: 1.2rem;
    }
    .bottom-nav .nav-link {
        color: #64748b;
        font-size: 1.1rem;
        font-weight: 500;
        padding: 0.7rem 0 0.2rem 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 0.75rem;
        transition: background 0.2s, color 0.2s;
    }
    .bottom-nav .nav-link.active, .bottom-nav .nav-link:focus {
        color: #2563eb;
        background: #e0e7ff;
    }
    .notif-badge {
        position: absolute;
        top: 6px;
        left: 55%;
        transform: translateX(-50%);
        background: #ef4444;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 999px;
        padding: 0.1rem 0.5rem;
        z-index: 2;
    }
    @media (min-width: 992px) {
        .bottom-nav { display: none; }
    }
</style>
<div class="container py-3 mb-5">
    <div class="mb-3">
        <h2 class="fw-bold mb-1">Halo, Mahasiswa!</h2>
        <div class="text-muted mb-3">Mau makanan apa hari ini?</div>
        <div class="input-group mb-3">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Cari makanan atau kantin...">
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-funnel"></i></button>
        </div>
        <div class="mb-4">
            <button class="btn filter-btn active me-2 mb-2">Semua</button>
            <button class="btn filter-btn btn-outline-secondary me-2 mb-2">Makanan Utama</button>
            <button class="btn filter-btn btn-outline-secondary me-2 mb-2">Minuman</button>
            <button class="btn filter-btn btn-outline-secondary mb-2">Snack</button>
        </div>
    </div>
    <div class="mb-4">
        <h4 class="fw-bold mb-3">Kantin Kampus</h4>
        <div class="row g-4">
            @forelse ($kantins as $kantin)
            <div class="col-md-3 col-6">
                <div class="card kantin-card h-100">
                    <span class="kantin-status {{ $kantin->is_open ? '' : 'closed' }}">
                        {{ $kantin->is_open ? 'Buka' : 'Tutup' }}
                    </span>
                    <div class="kantin-img-container">
                        @if($kantin->image)
                            <img src="{{ $kantin->image_url }}"
                                 alt="Gambar {{ $kantin->name }}"
                                 class="kantin-img"
                                 onerror="this.onerror=null; this.src='{{ asset('img/logo1.png') }}';">
                        @else
                            <img src="{{ asset('img/logo1.png') }}"
                                 alt="Default Logo"
                                 class="kantin-img">
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $kantin->name }}</h5>
                        <div class="small text-muted mb-1">
                            <i class="bi bi-geo-alt me-1"></i> {{ $kantin->location }}
                        </div>
                        <div class="small text-muted mb-1">
                            <i class="bi bi-clock me-1"></i> {{ $kantin->operating_hours ?? 'Jam Operasional' }}
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <span class="fw-semibold">4.5</span>
                            <a href="{{ route('user.chat.index', ['seller_id' => $kantin->user_id, 'seller_name' => $kantin->user->name]) }}"
                               class="btn btn-sm btn-outline-primary ms-auto"
                               title="Chat dengan penjual">
                                <i class="bi bi-chat-dots"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">Belum ada kantin yang tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
    <!-- Menu Populer section can be added here if needed -->
</div>
<!-- Bottom Navigation Bar -->
<nav class="bottom-nav d-lg-none d-block px-2 py-1">
    <div class="d-flex justify-content-between align-items-center">
        <a href="#" class="nav-link active position-relative flex-fill">
            <i class="bi bi-house-door fs-4"></i>
            <span class="small">Beranda</span>
        </a>
        <a href="#" class="nav-link position-relative flex-fill">
            <span class="notif-badge">103</span>
            <i class="bi bi-bell fs-4"></i>
            <span class="small">Notifikasi</span>
        </a>
        <a href="#" class="nav-link position-relative flex-fill">
            <i class="bi bi-receipt fs-4"></i>
            <span class="small">Pesanan</span>
        </a>
        <a href="#" class="nav-link position-relative flex-fill">
            <i class="bi bi-person fs-4"></i>
            <span class="small">Profil</span>
        </a>
    </div>
</nav>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
