@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Menu: {{ $menu->name }}</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama Menu</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $menu->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $menu->price) }}" required>
        </div>
        <div class="mb-3">
            <label for="seller_id" class="form-label">Penjual</label>
            <select class="form-control" id="seller_id" name="seller_id" required>
                <option value="">-- Pilih Penjual --</option>
                @foreach ($sellers as $id => $name)
                    <option value="{{ $id }}" {{ old('seller_id', $menu->seller_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Gambar Menu</label>
            @if($menu->image_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                </div>
            @endif
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="is_available" class="form-label">Status</label>
            <select class="form-control" id="is_available" name="is_available" required>
                <option value="1" {{ old('is_available', $menu->is_available) == 1 ? 'selected' : '' }}>Tersedia</option>
                <option value="0" {{ old('is_available', $menu->is_available) == 0 ? 'selected' : '' }}>Habis</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
