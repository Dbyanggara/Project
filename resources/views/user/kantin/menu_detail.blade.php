@extends('layouts.app')

@section('content')
<div class="container py-4 menu-detail-main-container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">{{ $kantin->name }}</h5>
                            <p class="text-muted mb-2">{{ $kantin->description }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $kantin->is_open ? 'bg-success' : 'bg-danger' }}">
                                {{ $kantin->is_open ? 'Buka' : 'Tutup' }}
                            </span>
                            <p class="text-muted mb-1">Penjual: {{ $kantin->user->name }}</p>
                            @if(Auth::check() && Auth::id() !== $kantin->user->id)
                                <a href="{{ route('user.chat.index', ['seller_id' => $kantin->user->id, 'seller_name' => $kantin->user->name]) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-chat-dots-fill"></i> Chat Penjual
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="mb-4 mt-4">Menu Tersedia di {{ $kantin->name }}</h4>

            @if($kantin->menus->isEmpty())
                <div class="alert alert-info">
                    Belum ada menu yang tersedia di kantin ini.
                </div>
            @else
                <div class="menu-scroll-x">
                    @foreach($kantin->menus as $menuItem)
                        <div class="card menu-item-card">
                            @if($menuItem->image)
                                <img src="{{ Storage::url($menuItem->image) }}" class="card-img-top menu-item-image" alt="{{ $menuItem->name }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $menuItem->name }}</h5>
                                <p class="text-muted mb-2">Rp {{ number_format($menuItem->price, 0, ',', '.') }}</p>
                                <p class="card-text small">{{ Str::limit($menuItem->description, 50) }}</p>
                                <form method="POST" class="d-flex align-items-end gap-2 mt-2 justify-content-center flex-nowrap">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menuItem->id }}">
                                    <div>
                                        <label for="quantity_{{ $menuItem->id }}" class="form-label form-label-sm">Jumlah</label>
                                        <input type="number" class="form-control form-control-sm" id="quantity_{{ $menuItem->id }}" name="quantity" value="1" min="1" style="width: 65px;" placeholder="Jml" aria-label="Jumlah">
                                    </div>
                                    <button type="submit" formaction="{{ route('user.orders.add-to-cart') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-cart-plus"></i> Tambah
                                    </button>
                                    <button type="submit" formaction="{{ route('user.orders.buy-now') }}" class="btn btn-success btn-sm">
                                        <i class="bi bi-bag-check"></i> Beli
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.menu-item-card {
    transition: box-shadow 0.2s ease-in-out, transform 0.2s ease-in-out;
    border-radius: 0.5rem;
    min-height: 0;
}
.menu-item-card:hover {
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 0 24px 0 rgba(59,130,246,0.35), 0 10px 20px rgba(0,0,0,0.15);
    z-index: 10;
    position: relative;
}
.menu-item-image {
    height: 120px;
    object-fit: cover;
    width: 100%;
}
.card-title {
    font-size: 0.95rem;
}
.card-text.small {
    font-size: 0.8rem;
    min-height: 2.2em;
}
.card-body.d-flex.flex-column {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    flex: 1 1 auto;
    min-height: 200px;
    padding-bottom: 1rem;
}
.menu-item-card .btn {
    margin-top: auto;
}
.menu-scroll-x {
    display: flex;
    flex-direction: row;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 1rem;
    scroll-snap-type: x mandatory;
}
.menu-scroll-x .menu-item-card {
    min-width: 280px; /* Lebarkan sedikit */
    max-width: 280px; /* Lebarkan sedikit */
    flex: 0 0 auto;
    scroll-snap-align: start;
}
.menu-item-card form.d-flex {
    flex-wrap: nowrap !important;
}
.menu-item-card .form-label {
    margin-bottom: 0.25rem;
}
.menu-item-card .d-flex.align-items-end {
    margin-top: 0.5rem;
    gap: 0.5rem;
}
.menu-item-card form {
    margin-bottom: 0;
}
@media (max-width: 991.98px) {
    .menu-detail-main-container {
        padding-bottom: 220px;
    }
    .row.g-4 {
        padding-bottom: 120px;
    }
    .menu-item-card:last-child {
        margin-bottom: 0 !important;
    }
}
</style>
@endpush
