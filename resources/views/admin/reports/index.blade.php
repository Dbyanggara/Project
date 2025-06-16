@extends('layouts.admin') {{-- Sesuaikan dengan layout admin Anda --}}

@section('content')
<main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Laporan</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            {{-- Tambahkan tombol aksi jika diperlukan, misalnya "Cetak Laporan" --}}
            {{-- <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-printer"></i> Cetak
            </button> --}}
        </div>
    </div>

    {{-- Contoh Ringkasan Laporan dalam Bentuk Card --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Laporan Penjualan</div>
                <div class="card-body">
                    <h5 class="card-title">Rp 15.000.000</h5>
                    <p class="card-text">Total penjualan bulan ini.</p>
                    <a href="#" class="btn btn-outline-light btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Pengguna Terdaftar</div>
                <div class="card-body">
                    <h5 class="card-title">150 Pengguna</h5>
                    <p class="card-text">Total pengguna aktif.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Menu Populer</div>
                <div class="card-body">
                    <h5 class="card-title">Nasi Goreng Spesial</h5>
                    <p class="card-text">Menu paling banyak dipesan.</p>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-light btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Area untuk Detail Laporan (misalnya tabel) --}}
    <div class="card">
        <div class="card-header">
            Detail Laporan
        </div>
        <div class="card-body">
            <p>Di sini Anda dapat menampilkan tabel data, grafik, atau filter untuk laporan yang lebih mendetail.</p>
            <p>Misalnya, Anda bisa menambahkan filter berdasarkan tanggal, kategori, atau penjual, lalu menampilkan hasilnya dalam bentuk tabel di bawah ini.</p>
            {{-- Contoh: <div id="chartContainer" style="height: 370px; width: 100%;"></div> --}}
            {{-- Contoh: <table class="table table-bordered"> ... </table> --}}
        </div>
    </div>
</main>
@endsection
