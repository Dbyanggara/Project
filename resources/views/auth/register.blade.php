@extends('layouts.guest')

@section('content')
<style>
    .register-card {
        max-width: 400px;
        margin: 0 auto;
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        background: #fff;
        padding: 2.5rem 2rem 2rem 2rem;
    }
    .register-logo {
        width: 150px; /* Ukuran diperbesar dari 60px menjadi 80px */
        margin-bottom: 1rem;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    .brand {
        font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        font-weight: 700;
        color: #6366f1;
        letter-spacing: 1px;
        text-align: center;
    }
    .register-link {
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
    }
    .register-link:hover { text-decoration: underline; }
    .register-card form .form-label {
        font-weight: 700;
        margin-bottom: 0.50rem;
    }
    .register-card form .form-control {
        border-radius: 1rem;
        font-size: 1rem;
        padding: 0.75rem 1rem;
        width: 100%;
        box-sizing: border-box;
        display: block;
    }
    .register-card form .mb-3 {
        margin-bottom: 1.2rem !important;
    }
    .btn-kantin {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 5rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.10);
        font-weight: 600;
        letter-spacing: 1px;
        transition: background 0.3s, box-shadow 0.3s, transform 0.2s;
    }
    .btn-kantin:hover, .btn-kantin:focus {
        background: linear-gradient(90deg, #60a5fa 0%, #6366f1 100%);
        box-shadow: 0 8px 24px rgba(99,102,241,0.18);
        color: #fff;
        transform: translateY(-2px) scale(1.03);
    }
</style>
<div class="register-card">
    <img src="{{ asset('img/logo1.png') }}" alt="Logo KantinKu" class="register-logo">
    <p class="text-center text-muted mb-4">Buat akun untuk mulai memesan makanan di KantinKu.</p>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control w-100 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap Anda">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control w-100 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan alamat email Anda">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control w-100 @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Buat kata sandi Anda">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control w-100 @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi Anda">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-kantin px-5">Daftar</button>
        </div>
    </form>
    <div class="text-center mt-4">
        <span class="text-muted">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="register-link">Masuk</a>
    </div>
</div>
@endsection
