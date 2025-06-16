<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light"> {{-- Default ke light, JS akan mengubahnya --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Penjual - KantinKu')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @stack('styles')

    <style>
        /* Variabel CSS untuk tema dari layouts.app.blade.php */
        :root {
            --app-bg: #f8f9fa;
            --app-text-color: #212529;
            --app-card-bg: #ffffff;
            --app-card-border-color: #dee2e6;
            --app-navbar-bg: #ffffff; /* Default Navbar light */
            --app-navbar-color: rgba(0, 0, 0, 0.7);
            --app-navbar-hover-color: rgba(0, 0, 0, 0.9);
            --app-link-color: #0d6efd;
            --app-input-bg: #fff;
            --app-input-border: #ced4da;
            --app-input-text: #495057;
            --app-secondary-bg: #e9ecef;
            --app-border-color-translucent: rgba(0, 0, 0, 0.175);
            --app-shadow-sm: 0 .125rem .25rem rgba(0,0,0,.075);
        }

        [data-bs-theme="dark"] {
            --app-bg: #212529;
            --app-text-color: #dee2e6;
            --app-card-bg: #2b3035;
            --app-card-border-color: #495057;
            --app-navbar-bg: #1c1f23; /* Default Navbar dark */
            --app-navbar-color: rgba(255, 255, 255, 0.75);
            --app-navbar-hover-color: rgba(255, 255, 255, 0.9);
            --app-link-color: #6ea8fe;
            --app-input-bg: #2b3035;
            --app-input-border: #495057;
            --app-input-text: #dee2e6;
            --app-secondary-bg: #343a40;
            --app-border-color-translucent: rgba(255, 255, 255, 0.15);
            --app-shadow-sm: 0 .125rem .25rem rgba(255,255,255,.075);
        }

        /* Aplikasi variabel tema */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--app-bg);
            color: var(--app-text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .navbar {
            margin-bottom: 2rem;
            background-color: var(--app-navbar-bg) !important;
            box-shadow: var(--app-shadow-sm) !important;
        }
        .navbar .nav-link, .navbar .navbar-brand, .navbar .dropdown-item { /* .navbar-toggler-icon diatur terpisah */
            color: var(--app-navbar-color) !important;
        }
        .navbar .nav-link:hover, .navbar .navbar-brand:hover, .navbar .dropdown-item:hover {
            color: var(--app-navbar-hover-color) !important;
        }
        [data-bs-theme="dark"] .navbar-toggler-icon {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .card {
            background-color: var(--app-card-bg);
            border-color: var(--app-card-border-color);
            color: var(--app-text-color);
        }
        .form-control, .form-select {
            background-color: var(--app-input-bg);
            border-color: var(--app-input-border);
            color: var(--app-input-text);
        }
        [data-bs-theme="dark"] .btn-close {
             filter: invert(1) grayscale(100%) brightness(200%);
        }
        .theme-toggle {
            cursor: pointer;
            font-size: 1.1rem;
            background: none;
            border: none;
            padding: 0.375rem 0.75rem; /* Samakan dengan padding nav-link */
            color: var(--app-navbar-color);
            transition: color 0.2s ease-in-out;
        }
        .theme-toggle:hover {
            color: var(--app-navbar-hover-color);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('seller.dashboard') }}">
                <i class="bi bi-shop"></i> KantinKu - Penjual <span class="badge bg-primary ms-1">Seller</span>
            </a>
            <div class="collapse navbar-collapse" id="sellerNavbarSupportedContent">
                {{-- Semua item menu di dalam hamburger telah dihapus --}}
                {{-- Mengganti dengan item menu tanpa ikon untuk seller --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}">Dasbor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}" href="{{ route('seller.orders.index') }}">Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seller.menus.*') ? 'active' : '' }}" href="{{ route('seller.menus.index') }}">Produk</a>
                    </li>
                    {{-- Tambahkan link seller lainnya di sini jika perlu, tanpa ikon --}}
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        {{-- Tombol ganti tema, ikonnya dikelola oleh JavaScript dan sebaiknya tidak dihapus dari HTML agar JS tetap berfungsi --}}
                        <button class="theme-toggle btn btn-link nav-link px-2" title="Ganti Tema">
                            <i class="bi bi-sun-fill"></i>
                            <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-flex">
                            @csrf
                            <button class="btn btn-link nav-link" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        (function() {
            const htmlElement = document.documentElement;
            const toggleButtons = document.querySelectorAll('.theme-toggle'); // Menargetkan semua tombol dengan kelas .theme-toggle

            function updateAllButtonIcons(theme) {
                toggleButtons.forEach(button => {
                    const sunIcon = button.querySelector('.bi-sun-fill');
                    const moonIcon = button.querySelector('.bi-moon-stars-fill');
                    if (sunIcon && moonIcon) {
                        sunIcon.style.display = theme === 'dark' ? 'none' : 'inline-block';
                        moonIcon.style.display = theme === 'dark' ? 'inline-block' : 'none';
                    }
                });
            }

            function setTheme(theme) {
                htmlElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);
                updateAllButtonIcons(theme);
            }

            function getPreferredTheme() {
                const storedTheme = localStorage.getItem('theme');
                if (storedTheme) {
                    return storedTheme;
                }
                return 'light'; // Default ke light
            }

            const initialTheme = getPreferredTheme();
            setTheme(initialTheme);

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const newTheme = htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            });
        })();
    </script>
</body>
</html>
