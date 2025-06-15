@extends('layouts.guest')

@section('content')
<style>
    .login-card {
        max-width: 400px;
        margin: 0 auto;
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        background: #fff;
        padding: 2.5rem 2rem 2rem 2rem;
    }
    .login-logo {
        width: 60px;
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
    .login-link {
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
    }
    .login-link:hover { text-decoration: underline; }
    .login-card form .form-label {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .login-card form .form-control {
        border-radius: 0.5rem;
        font-size: 1rem;
        padding: 0.75rem 1rem;
        width: 100%;
        box-sizing: border-box;
        display: block;
    }
    .login-card form .mb-3 {
        margin-bottom: 1.2rem !important;
    }
    .login-card button[type="submit"] {
        margin-top: 0.5rem;
        padding: 0.75rem;
        font-size: 1.1rem;
        border-radius: 0.5rem;
    }
    .btn-kantin {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 2rem;
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
<div class="login-card">
    <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo KantinKu" class="login-logo">
    <h2 class="brand mb-1">KantinKu</h2>
    <p class="text-center text-muted mb-4">Masuk untuk mulai memesan makanan.</p>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control w-100" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control w-100" id="password" name="password" required>
        </div>
        <div class="mb-2">
            <a href="{{ route('password.request') }}" class="login-link small">Lupa password?</a>
        </div>
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <div class="form-check m-0">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="btn btn-kantin px-4">Masuk</button>
        </div>
    </form>
    <div class="text-center mt-4">
        <span class="text-muted">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="login-link">Daftar</a>
    </div>
</div>
@endsection
