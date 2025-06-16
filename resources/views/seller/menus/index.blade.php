@extends('layouts.app') {{-- Menggunakan layout app utama, karena navbar akan didefinisikan di sini --}}

@section('title', 'Kelola Menu Saya')

@section('content')
@push('styles')
<style>
    /* Navbar styles are now inherited from layouts.app.blade.php */

    .menu-card {
        transition: all 0.3s ease-in-out;
        border-radius: 0.75rem;
        overflow: hidden;
        /* background-color: var(--app-card-bg);  Sudah dihandle oleh .card dari Bootstrap via layouts.app.blade.php */
        /* border: 1px solid var(--app-card-border-color); Sudah dihandle oleh .card dari Bootstrap via layouts.app.blade.php */
        box-shadow: var(--app-shadow-sm); /* Menggunakan shadow dari tema */
    }
    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px var(--app-border-color-translucent); /* Shadow lebih jelas saat hover, gunakan variabel tema */
    }
    .menu-img {
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid var(--app-card-border-color); /* Menggunakan variabel tema */
    }
    .menu-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: #28a745; /* Green for price */
    }
    .menu-stock {
        font-size: 0.9rem;
    }
    .btn-actions .btn {
        font-size: 0.85rem;
    }
    .card-title {
        color: var(--app-text-color); /* Pastikan warna title mengikuti tema */
        font-weight: 600;
    }
</style>
@endpush

{{-- Navbar Seller sekarang di-handle oleh layouts.app.blade.php --}}
<div class="container py-4"> {{-- style="margin-top: 80px;" dihapus --}}
    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
        <h1 class="h4 fw-bold"><i class="bi bi-card-list me-2"></i>Menu Saya</h1>
        <a href="{{ route('seller.menus.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Menu Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($menus->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-egg-fried display-1 text-muted"></i>
            <h4 class="mt-3">Belum Ada Menu</h4>
            <p class="text-muted">Anda belum menambahkan menu apapun. Mulai tambahkan sekarang!</p>
            <a href="{{ route('seller.menus.create') }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle"></i> Tambah Menu Pertama Anda
            </a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach ($menus as $menu)
            <div class="col">
                <div class="card h-100 menu-card"> {{-- shadow-sm dihapus, sudah diatur di .menu-card --}}
                    <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://via.placeholder.com/300x200.png?text=Menu' }}" class="card-img-top menu-img" alt="{{ $menu->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $menu->name }}</h5>
                        <p class="card-text menu-price mb-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <p class="card-text menu-stock text-muted"><small>Stok: {{ $menu->stock }}</small></p>
                        {{-- Jika Anda memiliki field 'description' atau 'category', tampilkan di sini --}}
                        {{-- <p class="card-text text-muted small">{{ Str::limit($menu->description, 50) }}</p> --}}
                        <div class="mt-auto pt-2 btn-actions">
                            <a href="{{ route('seller.menus.edit', $menu->id) }}" class="btn btn-outline-secondary btn-sm w-100 mb-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('seller.menus.destroy', $menu->id) }}" method="POST" class="d-grid" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini: {{ $menu->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="bi bi-trash3-fill"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <div class="mt-4">
            @if($menus->hasPages())
            <div class="mt-3">
                {{ $menus->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
