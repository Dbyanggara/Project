<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light"> {{-- Default ke light, JS akan mengubahnya --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'KantinKu'))</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo1.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @stack('styles')

    <style>
        body {
            font-family: 'Figtree', sans-serif; /* Menggunakan Figtree dari template Anda */
            /* background-color: #f8f9fa; Dihapus karena akan diatur oleh variabel CSS */
            padding-top: 70px; /* Adjusted for fixed-top navbar */
        }

        /* Variabel CSS untuk tema */
        :root {
            --app-bg: #f8f9fa; /* Latar belakang default light */
            --app-text-color: #212529; /* Teks default light */
            --app-card-bg: #ffffff;
            --app-card-border-color: #dee2e6;
            --app-navbar-bg: #ffffff; /* Navbar light */
            --app-navbar-color: rgba(0, 0, 0, 0.7);
            --app-navbar-hover-color: rgba(0, 0, 0, 0.9);
            --app-link-color: #0d6efd;
            --app-link-hover-color: #0a58ca;
            --app-input-bg: #fff;
            --app-input-border: #ced4da;
            --app-input-text: #495057;
            --app-secondary-bg: #e9ecef;
            --app-border-color-translucent: rgba(0, 0, 0, 0.175);
            --app-shadow-sm: 0 .125rem .25rem rgba(0,0,0,.075);
        }

        [data-bs-theme="dark"] {
            --app-bg: #212529; /* Latar belakang dark */
            --app-text-color: #dee2e6; /* Teks dark */
            --app-card-bg: #2b3035;
            --app-card-border-color: #495057;
            --app-navbar-bg: #1c1f23; /* Navbar dark */
            --app-navbar-color: rgba(255, 255, 255, 0.75);
            --app-navbar-hover-color: rgba(255, 255, 255, 0.9);
            --app-link-color: #6ea8fe;
            --app-link-hover-color: #8bb9fe;
            --app-input-bg: #2b3035;
            --app-input-border: #495057;
            --app-input-text: #dee2e6;
            --app-secondary-bg: #343a40;
            --app-border-color-translucent: rgba(255, 255, 255, 0.15);
            --app-shadow-sm: 0 .125rem .25rem rgba(255,255,255,.075); /* Shadow untuk dark mode */
        }

        /* Aplikasi variabel tema */
        body {
            background-color: var(--app-bg);
            color: var(--app-text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .navbar { /* Termasuk .seller-navbar jika menggunakan variabel */
            background-color: var(--app-navbar-bg) !important;
            box-shadow: var(--app-shadow-sm) !important;
        }
        .navbar .nav-link, .navbar .navbar-brand, .navbar .dropdown-item, .navbar .navbar-toggler-icon {
            color: var(--app-navbar-color) !important;
        }
        [data-bs-theme="dark"] .navbar-toggler-icon {
            filter: invert(1) grayscale(100%) brightness(200%); /* Agar ikon toggler terlihat di dark mode */
        }
        .navbar .nav-link:hover, .navbar .navbar-brand:hover, .navbar .dropdown-item:hover {
            color: var(--app-navbar-hover-color) !important;
        }
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: var(--app-card-bg);
            border-color: var(--app-card-border-color);
        }
        .card {
            background-color: var(--app-card-bg);
            border-color: var(--app-card-border-color);
            color: var(--app-text-color);
        }
        .list-group-item {
            background-color: var(--app-card-bg);
            border-color: var(--app-card-border-color);
            color: var(--app-text-color);
        }
        [data-bs-theme="dark"] .list-group-item-action:hover {
            background-color: var(--app-secondary-bg);
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
            font-size: 1.1rem; /* Sesuaikan ukuran ikon */
            background: none;
            border: none;
            padding: 0.375rem 0.75rem; /* Samakan dengan padding nav-link */
            color: var(--app-navbar-color);
            transition: color 0.2s ease-in-out;
        }
        .theme-toggle:hover {
            color: var(--app-navbar-hover-color);
        }

        .navbar-nav .nav-item .nav-link .badge {
            font-size: 0.65em;
            padding: 0.3em 0.5em;
            position: relative;
            top: -0.1em;
        }

        {{-- Seller Navbar Styles - To be applied when seller is logged in --}}
        @if(Auth::check() && Auth::user()->hasRole('seller'))
        .seller-navbar {
            /* background and box-shadow are handled by general .navbar rules using CSS variables */
            border-bottom: 1px solid var(--app-card-border-color); /* Use theme variable for consistency */
            z-index: 1050;
        }
        .seller-navbar .nav-link {
            /* color: #374151; */ /* Removed: Will inherit from .navbar .nav-link which uses var(--app-navbar-color) */
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: color 0.2s;
            position: relative;
        } /* Color will be handled by general .navbar .nav-link rule using var(--app-navbar-color) */
        .seller-navbar .nav-link.active, .seller-navbar .nav-link:focus {
            color: #2563eb; /* Specific active color for seller, can be kept */
        }
        .seller-navbar .nav-link .bi {
            font-size: 1.3rem;
            margin-right: 0.5rem;
        }
        .seller-navbar .notif-badge {
            position: absolute;
            top: 0.2rem;
            left: 1.2rem;
            background: #ef4444;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 999px;
            padding: 0.1rem 0.5rem;
            z-index: 2;
        }
        @media (max-width: 991.98px) {
            .seller-navbar .nav-link span { display: none; }
            .seller-navbar .nav-link .bi { margin-right: 0; }
        }
        @endif

        /* Bottom Navigation Bar for User on Mobile */
        .user-mobile-bottom-nav, .seller-mobile-bottom-nav {
            background-color: var(--app-navbar-bg);
            border-top: 1px solid var(--app-card-border-color);
            box-shadow: 0 -1px 3px var(--app-border-color-translucent);
            padding: 0.25rem 0; /* Minimal vertical padding for the bar itself */
            z-index: 1030; /* Below fixed-top navbar (1030) but above most content */
        }
        .user-mobile-bottom-nav .nav-item-bottom,
        .seller-mobile-bottom-nav .nav-item-bottom {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1; /* Each item takes equal width */
            padding: 0.5rem 0.25rem; /* Padding for each item */
            text-decoration: none;
            color: var(--app-navbar-color); /* Default color for icon and text */
            transition: color 0.2s ease-in-out;
        }
        .user-mobile-bottom-nav .nav-item-bottom .nav-icon,
        .seller-mobile-bottom-nav .nav-item-bottom .nav-icon {
            font-size: 1.25rem; /* Icon size */
            margin-bottom: 0.2rem; /* Increased space between icon and text */
        }
        .user-mobile-bottom-nav .nav-item-bottom .nav-text,
        .seller-mobile-bottom-nav .nav-item-bottom .nav-text {
            font-size: 0.7rem; /* Smaller text */
            line-height: 1;
        }
        .user-mobile-bottom-nav .nav-item-bottom.active {
            color: var(--app-link-color); /* Active color */
        }
    </style>
    @php
        $isSeller = Auth::check() && Auth::user()->hasRole('seller');
        $navClasses = $isSeller ? 'seller-navbar' : 'navbar-light shadow-sm'; // bg-white dihapus, akan diatur oleh variabel CSS
        $containerClasses = $isSeller ? 'container-fluid px-3' : 'container';
        $navbarId = $isSeller ? 'sellerAppNavbar' : 'navbarSupportedContent';
        $brandClasses = $isSeller ? 'fw-bold text-primary' : '';
        // Logic for $homeRoute is already present and correct below
    @endphp
</head>
<body class="font-sans antialiased @if( (Auth::check() && Auth::user()->hasRole('user') && !$isSeller && !Auth::user()->hasRole('admin')) || ($isSeller) ) has-bottom-nav @endif">
    <nav class="navbar navbar-expand-lg {{ $navClasses }} fixed-top">
        <div class="{{ $containerClasses }}">
            @php
                $homeRoute = route('user.dashboard'); // Default
                if (Auth::check()) {
                    if (Auth::user()->hasRole('admin')) {
                        $homeRoute = route('admin.dashboard');
                    } elseif (Auth::user()->hasRole('seller')) {
                        $homeRoute = route('seller.dashboard');
                    }
                }
            @endphp
             <a class="navbar-brand {{ $brandClasses }}" href="{{ $homeRoute }}">
                {{-- Selalu tampilkan logo gambar --}}
                <img src="{{ asset('img/logo1.png') }}" alt="KantinKu" style="height: 35px; vertical-align: middle; margin-right: 0.3rem;">
                KantinKu
                @auth
                @if(Auth::user()->hasRole('admin') && !$isSeller) <span class="badge bg-danger ms-1">Admin</span> @endif
                @endauth
            </a>

            @if(Auth::check() && Auth::user()->hasRole('user') && !$isSeller && !Auth::user()->hasRole('admin'))
                {{-- Untuk Pengguna 'User' di Mobile: Tombol Tema menggantikan Hamburger --}}
                <button class="theme-toggle btn btn-link nav-link px-2 d-lg-none" id="theme-toggle-button-mobile-header" title="Ganti Tema" type="button">
                    <i class="bi bi-sun-fill"></i>
                    <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                </button>
                {{-- Tombol hamburger untuk 'user' di desktop dihilangkan karena menu sudah expanded --}}
            @else {{-- Untuk Seller, Admin, dan Guest --}}
                {{-- Mengganti Tombol Hamburger dengan Tombol Tema di Mobile --}}
                <button class="theme-toggle btn btn-link nav-link px-2 d-lg-none" title="Ganti Tema" type="button">
                    <i class="bi bi-sun-fill"></i>
                    <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                </button>
            @endif

            <div class="collapse navbar-collapse" id="{{$navbarId}}">
                <!-- Left Side Of Navbar -->
                @auth
                @if(Auth::user()->hasRole('user'))
                 <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="bi bi-house-door"></i> <span>Beranda</span></a>
                    </li>
                      <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('user.notifications.index') ? 'active' : '' }}" href="{{ route('user.notifications.index') }}">
                           <i class="bi bi-bell"></i> <span>Notifikasi</span>
                            @if(isset($unreadNotificationsCountGlobal) && $unreadNotificationsCountGlobal > 0)
                                <span class="badge rounded-pill bg-danger">{{ $unreadNotificationsCountGlobal > 99 ? '99+' : $unreadNotificationsCountGlobal }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link {{ request()->routeIs('user.pesanan.index') ? 'active' : '' }}" href="{{ route('user.pesanan.index') }}"><i class="bi bi-receipt"></i> <span>Pesanan</span></a>

                    </li>
                </ul>
@elseif($isSeller)
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}"><i class="bi bi-house-door"></i> <span>Beranda</span></a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}" href="{{ route('seller.orders.index') }}"><i class="bi bi-receipt"></i> <span>Pesanan</span>
                            @if(isset($sellerNewOrdersCountGlobal) && $sellerNewOrdersCountGlobal > 0)
                                <span class="notif-badge">{{ $sellerNewOrdersCountGlobal > 99 ? '99+' : $sellerNewOrdersCountGlobal }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seller.menus.*') ? 'active' : '' }}" href="{{ route('seller.menus.index') }}"><i class="bi bi-box-seam"></i> <span>Produk</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seller.reports.index') ? 'active' : '' }}" href="{{ route('seller.reports.index') }}"><i class="bi bi-bar-chart-line"></i> <span>Laporan</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seller.chat.index') ? 'active' : '' }}" href="{{ route('seller.chat.index') }}"><i class="bi bi-chat-dots"></i> <span>Chat</span></a>
                    </li>
                </ul>
                @elseif(Auth::user()->hasRole('admin'))
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock"></i> <span>Dashboard Admin</span></a>
                    </li>
                    {{-- Tambahkan menu admin lainnya di sini jika perlu --}}
                </ul>
                @endif
                @endauth
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto align-items-center"> {{-- align-items-center ditambahkan untuk desktop toggle --}}
                    {{-- Tombol Tema Universal (Desktop untuk semua, Mobile di dalam collapse untuk seller/admin/guest) --}}
                    <li class="nav-item">
                        {{-- Untuk 'user', tombol ini hanya tampil di desktop karena di mobile sudah ada di header --}}
                        {{-- Untuk peran lain, tombol ini akan ada di collapse menu di mobile dan langsung di navbar desktop --}}
                        <button class="theme-toggle btn btn-link nav-link px-2 @if(Auth::check() && Auth::user()->hasRole('user') && !$isSeller && !Auth::user()->hasRole('admin')) d-none d-lg-inline-flex @else d-inline-flex @endif" id="theme-toggle-button-general" title="Ganti Tema">
                            <i class="bi bi-sun-fill"></i>
                            <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                        </button>
                    </li>
                    @auth
                    @if ($isSeller)
                        <!-- Seller: Profile Link -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('seller.profile.edit') ? 'active' : '' }}" href="{{ route('seller.profile.edit') }}">
                                <i class="bi bi-person"></i> <span>Profil</span>
                            </a>
                        </li>
                        <!-- Seller: Logout Form -->
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center mb-0">
                                @csrf
                                <button type="submit" class="nav-link bg-transparent border-0 p-0" style="cursor:pointer;">
                                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    @elseif (Auth::user()->hasRole('user') && !Auth::user()->hasRole('admin')) {{-- Regular User --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> <span>Profil</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center mb-0">
                                @csrf
                                <button type="submit" class="nav-link bg-transparent border-0 p-0" style="cursor:pointer;">
                                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    @elseif (Auth::user()->hasRole('admin')) {{-- Admin --}}
                         <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}"> {{-- Admin uses general profile.edit --}}
                                <i class="bi bi-person-gear"></i> <span>Profil Admin</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center mb-0">
                                @csrf
                                <button type="submit" class="nav-link bg-transparent border-0 p-0" style="cursor:pointer;">
                                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    @endif
                @else
                    {{-- Tampilkan link Login & Daftar jika pengguna belum login --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
                </ul>
                {{-- Tombol tema mobile yang lama di dalam collapse sudah dihapus --}}
            </div>
        </div>
    </nav>

    <main> {{-- Removed container and mt-4. Child views will handle their own container. Body padding-top handles navbar spacing. --}}
        @yield('content')
    </main>

    @auth
        @if(Auth::user()->hasRole('user') && !$isSeller && !Auth::user()->hasRole('admin'))
            {{-- Bottom Navigation Bar for User (Mobile Only) --}}
            <nav class="fixed-bottom d-lg-none user-mobile-bottom-nav">
                <div class="d-flex justify-content-around">
                    <a href="{{ route('user.dashboard') }}" class="nav-item-bottom {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door nav-icon"></i>
                        <span class="nav-text">Beranda</span>
                    </a>
                    <a href="{{ route('user.notifications.index') }}" class="nav-item-bottom {{ request()->routeIs('user.notifications.index') ? 'active' : '' }}">
                        <i class="bi bi-bell nav-icon"></i>
                        <span class="nav-text">Notifikasi</span>
                    </a>
                    <a href="{{ route('user.pesanan.index') }}" class="nav-item-bottom {{ request()->routeIs('user.pesanan.index') ? 'active' : '' }}">
                        <i class="bi bi-receipt nav-icon"></i>
                        <span class="nav-text">Pesanan</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-item-bottom {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="bi bi-person nav-icon"></i>
                        <span class="nav-text">Profil</span>
                    </a>
                </div>
            </nav>
            <style>
                @media (max-width: 991.98px) { body.has-bottom-nav { padding-bottom: 58px; /* Height of bottom nav */ } }
            </style>
        @elseif($isSeller)
            {{-- Seller Bottom Navigation Bar (Mobile Only) --}}
            <nav class="fixed-bottom d-lg-none seller-mobile-bottom-nav">
                <div class="d-flex justify-content-around">
                    <a href="{{ route('seller.dashboard') }}" class="nav-item-bottom {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door nav-icon"></i>
                        <span class="nav-text">Beranda</span>
                    </a>
                    <a href="{{ route('seller.orders.index') }}" class="nav-item-bottom {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}">
                        <i class="bi bi-receipt nav-icon"></i>
                        <span class="nav-text">Pesanan</span>
                    </a>
                    <a href="{{ route('seller.menus.index') }}" class="nav-item-bottom {{ request()->routeIs('seller.menus.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam nav-icon"></i>
                        <span class="nav-text">Produk</span>
                    </a>
                    <a href="{{ route('seller.reports.index') }}" class="nav-item-bottom {{ request()->routeIs('seller.reports.index') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line nav-icon"></i>
                        <span class="nav-text">Laporan</span>
                    </a>
                    <a href="{{ route('seller.chat.index') }}" class="nav-item-bottom {{ request()->routeIs('seller.chat.index') ? 'active' : '' }}">
                        <i class="bi bi-chat-dots nav-icon"></i>
                        <span class="nav-text">Chat</span>
                    </a>
                    <a href="{{ route('seller.profile.edit') }}" class="nav-item-bottom {{ request()->routeIs('seller.profile.edit') ? 'active' : '' }}">
                        <i class="bi bi-person nav-icon"></i>
                        <span class="nav-text">Profil</span>
                    </a>
                </div>
            </nav>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        (function() {
            const htmlElement = document.documentElement;
            // Memastikan semua tombol tema teridentifikasi dengan benar
            const toggleButtons = document.querySelectorAll('#theme-toggle-button-mobile-header, #theme-toggle-button-general');

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
                updateAllButtonIcons(theme); // Memperbarui ikon di semua tombol
            }

            function getPreferredTheme() {
                const storedTheme = localStorage.getItem('theme');
                if (storedTheme) {
                    return storedTheme;
                }
                // Default ke 'light' jika tidak ada preferensi atau tema sistem
                // Anda bisa menambahkan deteksi tema sistem jika diinginkan:
                // return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                return 'light';
            }

            // Set tema awal saat halaman dimuat
            const initialTheme = getPreferredTheme();
            setTheme(initialTheme);

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentTheme = htmlElement.getAttribute('data-bs-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            });
        })();
    </script>
</body>
</html>
