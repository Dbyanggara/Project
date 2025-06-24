@extends('layouts.admin')

@section('title', 'Manajemen Kantin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Kantin</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.kantins.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Kantin
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Kantin</th>
                        <th>Lokasi</th>
                        <th>Penjual</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kantins as $kantin)
                        <tr>
                            <td>{{ $kantin->id }}</td>
                            <td>
                                <strong>{{ $kantin->name }}</strong>
                            </td>
                            <td>{{ $kantin->location }}</td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="bi bi-person me-1"></i>
                                    {{ $kantin->user->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success">Aktif</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.kantins.edit', $kantin->id) }}"
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.kantins.destroy', $kantin->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kantin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Tidak ada data kantin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kantins->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $kantins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
