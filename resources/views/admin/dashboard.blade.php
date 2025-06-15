@extends('layouts.app')

@section('content')
<style>
    .admin-sidebar {
        min-height: 100vh;
        background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        padding: 2rem 1rem 2rem 1rem;
        position: sticky;
        top: 0;
    }
    .admin-sidebar .nav-link {
        color: #fff;
        font-weight: 500;
        margin-bottom: 1rem;
        border-radius: 0.75rem;
        transition: background 0.2s;
    }
    .admin-sidebar .nav-link.active, .admin-sidebar .nav-link:hover {
        background: rgba(255,255,255,0.15);
        color: #fff;
    }
    .admin-header {
        font-size: 2rem;
        font-weight: 700;
        color: #6366f1;
        margin-bottom: 2rem;
    }
    .admin-content {
        padding: 2rem;
    }
    @media (max-width: 991px) {
        .admin-sidebar { min-height: auto; padding: 1rem; }
        .admin-content { padding: 1rem; }
    }
</style>
<div class="container-fluid">
    <div class="row">
        <nav class="col-lg-3 col-md-4 admin-sidebar d-flex flex-column mb-4 mb-lg-0">
            <div class="mb-4 text-center">
                <span class="fs-4 fw-bold">Admin Panel</span>
            </div>
            <a href="#" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="#" class="nav-link"><i class="bi bi-people me-2"></i> Kelola User</a>
            <a href="#" class="nav-link"><i class="bi bi-list-ul me-2"></i> Kelola Menu</a>
            <a href="#" class="nav-link"><i class="bi bi-bag-check me-2"></i> Kelola Pesanan</a>
            <a href="#" class="nav-link mt-auto"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
        </nav>
        <main class="col-lg-9 col-md-8 admin-content">
            <div class="admin-header">Dashboard Admin</div>
            <div class="card shadow-sm p-4">
                <h5 class="fw-bold mb-3">Selamat datang, Admin!</h5>
                <p class="mb-0">Ini adalah halaman dashboard admin. Silakan pilih menu di samping untuk mengelola aplikasi KantinKu.</p>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
