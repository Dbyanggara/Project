@extends('layouts.seller')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
        <a href="{{ route('seller.menus.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Menu
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.menus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Menu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="100" required>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" min="0" required>
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                {{-- Jika Anda menambahkan field lain seperti description, category, is_available, tambahkan di sini --}}
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Gambar Menu</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">Kosongkan jika tidak ada gambar. Maksimal 2MB (jpg, png, gif).</small>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Menu
                    </button>
                    <a href="{{ route('seller.menus.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
