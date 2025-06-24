@extends('layouts.app')

@section('title', 'Edit Profil Saya')

@push('styles')
<style>
    .profile-card {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .profile-card .card-header {
        font-weight: 600;
        background-color: var(--app-secondary-bg); /* Menggunakan variabel CSS untuk tema */
        border-bottom: 1px solid var(--app-card-border-color); /* Menggunakan variabel CSS untuk tema */
    }
    .form-label {
        font-weight: 500;
    }
    .btn-danger-soft {
        color: #dc3545;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }
    .btn-danger-soft:hover {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Pengaturan Akun Saya</h2>
    <div class="row">
        <div class="col-lg-8">
            {{-- Update Profile Information --}}
            <div class="card profile-card mb-4">
                <div class="card-header">Informasi Profil</div>
                <div class="card-body p-4">
                    <p class="text-muted small">Perbarui informasi profil akun Anda dan alamat email.</p>
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Nama') }}</label>
                            <input id="name" name="name" type="text" class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name', 'updateProfileInformation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email', 'updateProfileInformation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="small text-muted">
                                        {{ __('Alamat email Anda belum diverifikasi.') }}
                                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">{{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}</button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 small text-success">
                                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-4">
                            <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
                            @if (session('status') === 'profile-updated')
                                <p class="mb-0 small text-success">{{ __('Tersimpan.') }}</p>
                            @endif
                        </div>
                    </form>
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            {{-- Update Password --}}
            <div class="card profile-card mb-4">
                <div class="card-header">Ubah Kata Sandi</div>
                <div class="card-body p-4">
                    <p class="text-muted small">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Kata Sandi Saat Ini') }}</label>
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                            <input type="hidden" name="username" autocomplete="username" value="{{ auth()->user()->email }}">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Kata Sandi Baru') }}</label>
                            <input id="password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            <input type="hidden" name="username" autocomplete="username" value="{{ auth()->user()->email }}">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Kata Sandi Baru') }}</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                             @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex align-items-center gap-4">
                            <button type="submit" class="btn btn-primary">{{ __('Simpan Kata Sandi') }}</button>
                            @if (session('status') === 'password-updated')
                                <p class="mb-0 small text-success">{{ __('Tersimpan.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Logout Section --}}
            <div class="card profile-card mb-4">
                <div class="card-header">Sesi Akun</div>
                <div class="card-body p-4">
                    <p class="text-muted small">Keluar dari sesi akun Anda saat ini.</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-lg-4">
             {{-- Bagian ini dikosongkan atau bisa diisi dengan konten lain jika ada --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Jika ada script khusus untuk halaman profil --}}
@endpush
