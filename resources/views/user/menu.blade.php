@extends('layouts.app')

@section('content')
<style>
    .user-sidebar {
        min-height: 100vh;
        background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        padding: 2rem 1rem 2rem 1rem;
        position: sticky;
        top: 0;
    }
    .user-sidebar .nav-link {
        color: #fff;
        font-weight: 500;
        margin-bottom: 1rem;
        border-radius: 0.75rem;
        transition: background 0.2s;
    }
    .user-sidebar .nav-link.active, .user-sidebar .nav-link:hover {
        background: rgba(255,255,255,0.15);
        color: #fff;
    }
    .menu-header {
        font-size: 2rem;
        font-weight: 700;
        color: #6366f1;
        margin-bottom: 2rem;
    }
    .menu-content {
        padding: 2rem;
    }
    .product-card {
        border-radius: 1.25rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.08);
        transition: box-shadow 0.2s, transform 0.2s;
        overflow: hidden;
    }
    .product-card:hover {
        box-shadow: 0 8px 32px rgba(99,102,241,0.16);
        transform: translateY(-2px) scale(1.02);
    }
    .product-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 1rem 1rem 0 0;
    }
    .btn-kantin {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 2rem;
        font-weight: 600;
        letter-spacing: 1px;
        transition: background 0.3s, box-shadow 0.3s, transform 0.2s;
    }
    .btn-kantin:hover, .btn-kantin:focus {
        background: linear-gradient(90deg, #60a5fa 0%, #6366f1 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.03);
    }
    @media (max-width: 991px) {
        .user-sidebar { min-height: auto; padding: 1rem; }
        .menu-content { padding: 1rem; }
    }
</style>
<div class="container-fluid">
    <div class="row">
        <nav class="col-lg-2 col-md-3 user-sidebar d-flex flex-column mb-4 mb-lg-0">
            <div class="mb-4 text-center">
                <span class="fs-5 fw-bold">Menu User</span>
            </div>
            <a href="#" class="nav-link active"><i class="bi bi-house-door me-2"></i> Home</a>
            <a href="#" class="nav-link"><i class="bi bi-clock-history me-2"></i> Riwayat</a>
            <a href="#" class="nav-link"><i class="bi bi-person me-2"></i> Profil</a>
            <a href="#" class="nav-link mt-auto"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
        </nav>
        <main class="col-lg-10 col-md-9 menu-content">
            <div class="menu-header">Menu Kantin</div>
            <div class="mb-4">
                <input type="text" class="form-control form-control-lg" placeholder="Cari makanan/minuman...">
            </div>
            <div class="row g-4">
                @for($i=1; $i<=8; $i++)
                <div class="col-md-3">
                    <div class="card product-card h-100">
                        <img src="https://source.unsplash.com/400x300/?food,{{ $i }}" class="product-img" alt="Menu {{ $i }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Menu Makanan {{ $i }}</h5>
                            <p class="card-text text-muted mb-2">Rp{{ number_format(10000 + $i*2000,0,',','.') }}</p>
                            <button class="btn btn-kantin mt-auto">Beli</button>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
