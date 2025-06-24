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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Debug: pastikan Echo sudah tersedia di global scope
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.Echo === 'undefined') {
                console.error('DEBUG: window.Echo is undefined after app.js loaded!');
            } else {
                console.log('DEBUG: window.Echo is available globally.');
            }
        });
    </script>
    @stack('styles')

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        body.has-fixed-top-navbar {
            padding-top: 70px; /* Diterapkan hanya jika ada navbar atas */
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

        /* Enhanced Toast Notifications */
        .toast-container {
            z-index: 9999;
        }

        .toast {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-left: 4px solid #28a745;
            min-width: 350px;
            max-width: 400px;
        }

        [data-bs-theme="dark"] .toast {
            background: rgba(33, 37, 41, 0.95);
            border-left-color: #28a745;
        }

        .toast-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1rem 1rem 0.75rem 1rem;
            border-radius: 12px 12px 0 0;
        }

        [data-bs-theme="dark"] .toast-header {
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }

        .toast-header img {
            width: 24px;
            height: 24px;
            border-radius: 6px;
        }

        .toast-header strong {
            color: #28a745;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .toast-header small {
            color: #6c757d;
            font-size: 0.8rem;
        }

        .toast-body {
            padding: 0.75rem 1rem 1rem 1rem;
            font-size: 0.9rem;
            line-height: 1.4;
            color: #495057;
        }

        [data-bs-theme="dark"] .toast-body {
            color: #dee2e6;
        }

        .toast:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .toast-success {
            border-left-color: #28a745;
        }

        .toast-success .toast-header strong {
            color: #28a745;
        }

        .toast-info {
            border-left-color: #17a2b8;
        }

        .toast-info .toast-header strong {
            color: #17a2b8;
        }

        .toast-warning {
            border-left-color: #ffc107;
        }

        .toast-warning .toast-header strong {
            color: #ffc107;
        }

        .toast-danger {
            border-left-color: #dc3545;
        }

        .toast-danger .toast-header strong {
            color: #dc3545;
        }

        /* Animation for new notifications */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast.show {
            animation: slideInRight 0.3s ease-out;
        }

        /* Notification badge animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        {{-- Seller Navbar Styles - To be applied when seller is logged in --}}
        @if(Auth::check() && Auth::user()->hasRole('seller'))
        .seller-navbar {
            background-color: var(--app-navbar-bg);
            border-bottom: 1px solid var(--app-card-border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            z-index: 1050;
            height: 70px;
            transition: all 0.3s ease;
        }

        .seller-navbar .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--app-link-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .seller-navbar .navbar-brand img {
            height: 36px;
            transition: transform 0.3s ease;
        }

        .seller-navbar .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .seller-navbar .nav-link {
            color: var(--app-navbar-color);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .seller-navbar .nav-link:hover {
            color: var(--app-link-color);
            background-color: var(--app-secondary-bg);
        }

        .seller-navbar .nav-link.active {
            color: var(--app-link-color);
            background-color: var(--app-secondary-bg);
            font-weight: 600;
        }

        .seller-navbar .nav-link .bi {
            font-size: 1.2rem;
        }

        .seller-navbar .notif-badge {
            position: absolute;
            top: 0.2rem;
            right: 0.2rem;
            background: #ef4444;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            min-width: 1.5rem;
            text-align: center;
        }

        .seller-navbar .profile-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .seller-navbar .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--app-link-color);
            transition: transform 0.2s ease;
        }

        .seller-navbar .profile-avatar:hover {
            transform: scale(1.1);
        }

        .seller-navbar .theme-toggle {
            color: var(--app-navbar-color);
            font-size: 1.2rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .seller-navbar .theme-toggle:hover {
            color: var(--app-link-color);
            background-color: var(--app-secondary-bg);
        }

        @media (max-width: 991.98px) {
            .seller-navbar {
                display: block !important;
            }
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

        .desktop-navbar {
            display: flex;
            align-items: center;
            background-color: var(--app-navbar-bg);
            padding: 0 32px;
            height: 70px;
            box-shadow: var(--app-shadow-sm);
            position: fixed;
            top: 0; left: 0; width: 100%;
            z-index: 1000;
        }
        .desktop-navbar .nav-logo a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--app-navbar-color);
            font-size: 1.5em;
            font-weight: 700;
        }
        .desktop-navbar .nav-menu {
            list-style: none;
            display: flex;
            align-items: center;
            margin-left: 40px;
            gap: 8px;
        }
        /* Styles for .nav-link within .desktop-navbar */
        .desktop-navbar .nav-menu .nav-link,
        .desktop-navbar .nav-profile .nav-link {
            text-decoration: none;
            color: var(--app-navbar-color);
            font-size: 1em;
            font-weight: 500;
            padding: 12px 18px;
            border-radius: 6px;
            transition: background 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .desktop-navbar .nav-menu .nav-link:hover,
        .desktop-navbar .nav-profile .nav-link:hover {
            background: var(--app-secondary-bg);
            color: var(--app-link-hover-color);
        }
        .desktop-navbar .nav-menu .nav-link.active,
        .desktop-navbar .nav-profile .nav-link.active {
            background-color: transparent; /* Hapus latar belakang */
            color: var(--app-link-color); /* Gunakan warna link utama untuk teks */
            border-bottom: 2px solid var(--app-link-color); /* Tambahkan garis bawah */
            border-radius: 0; /* Hapus border-radius agar garis bawah rata */
            /* Padding bawah disesuaikan jika ingin tinggi elemen tetap sama persis,
               namun biasanya penambahan tinggi 2px untuk border tidak masalah.
               padding-bottom: calc(12px - 2px); */
        }
        .desktop-navbar .nav-menu .nav-link.active:hover,
        .desktop-navbar .nav-profile .nav-link.active:hover {
            background-color: var(--app-secondary-bg); /* Latar belakang hover seperti link non-aktif */
            color: var(--app-link-hover-color); /* Warna teks hover */
            border-bottom-color: var(--app-link-hover-color); /* Warna garis bawah hover */
        }
        .badge {
            background: #e74c3c;
            color: #fff;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 0.75em;
            font-weight: bold;
            margin-left: 8px;
            line-height: 1;
        }
        .desktop-navbar .nav-profile {
            margin-left: auto;
            display: flex;
            align-items: center;
        }
        .desktop-navbar .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
            border: 2px solid var(--app-card-border-color);
        }
        .desktop-navbar .profile-link span {
            font-weight: 500;
        }

        /* Mobile Top Navbar for User */
        .mobile-top-navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--app-navbar-bg);
            padding: 0 1rem; /* Horizontal padding */
            height: 70px; /* Consistent with desktop and body padding */
            box-shadow: var(--app-shadow-sm);
            position: fixed;
            top: 0; left: 0; width: 100%;
            z-index: 1030;
        }
        .mobile-top-navbar .nav-logo a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--app-navbar-color);
            flex-direction: column; /* Mengatur item secara vertikal */
            justify-content: center; /* Pusatkan item di dalam 'a' */
        }
        .mobile-top-navbar .nav-logo img {
            height: 30px;
            margin-bottom: 0.2rem; /* Jarak antara logo dan teks */
        }
        .mobile-top-navbar .nav-logo span {
            font-size: 0.75rem; /* Ukuran font untuk teks di bawah logo */
            font-weight: 500;
        }
        .mobile-top-navbar .nav-page-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--app-text-color); /* Menggunakan warna teks utama */
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex-grow: 1; /* Memungkinkan judul mengambil ruang yang tersedia */
            margin: 0 0.5rem; /* Jarak antara logo/tombol aksi */
        }
        .mobile-top-navbar .nav-page-title h5 {
            font-size: inherit; /* Mewarisi dari parent */
            font-weight: inherit; /* Mewarisi dari parent */
            color: inherit; /* Mewarisi dari parent */
            margin-bottom: 0; /* Menghilangkan margin bawah default dari h5 */
        }
        .mobile-top-navbar .nav-actions {
            display: flex;
            align-items: center;
        }
        /* Padding atas body akan diatur oleh class .has-fixed-top-navbar jika ada navbar atas */

        @media (max-width: 991.98px) {
            .desktop-navbar {
                display: none !important;
            }
        }
        .toast-container {
            z-index: 1100; /* Pastikan di atas navbar */
        }
    </style>
    @php
        $isLoggedIn = Auth::check();
        $isSeller = Auth::check() && Auth::user()->hasRole('seller');
        $isAdmin = $isLoggedIn && Auth::user()->hasRole('admin');
        $isPureUser = $isLoggedIn && Auth::user()->hasRole('user') && !$isSeller && !$isAdmin;
        $isUser = Auth::check() && Auth::user()->hasRole('user') && !Auth::user()->hasRole('admin') && !Auth::user()->hasRole('seller');

        // Variabel untuk kelas navbar (hanya jika navbar dirender)
        $navClasses = $isSeller ? 'seller-navbar' : 'navbar-light shadow-sm';
        $containerClasses = $isSeller ? 'container-fluid px-3' : 'container';
        $navbarId = $isSeller ? 'sellerAppNavbar' : ($isAdmin ? 'adminAppNavbar' : 'guestAppNavbar'); // ID unik untuk setiap navbar
        $brandClasses = $isSeller ? 'fw-bold text-primary' : '';

        // Kelas untuk body
        $bodyClasses = ['font-sans', 'antialiased'];
        $hasFixedTopNavbar = false;

        if ($isSeller) { // Seller akan memiliki navbar atas di mobile dan desktop
            $hasFixedTopNavbar = true;
        } elseif ($isUser) { // $isUser (termasuk $isPureUser) memiliki desktop-navbar atas
            $hasFixedTopNavbar = true;
        } elseif ($isAdmin) { // Admin memiliki navbar atas generik
            $hasFixedTopNavbar = true;
        } elseif (!$isLoggedIn) { // Tamu memiliki navbar atas generik
            $hasFixedTopNavbar = true;
        }

        if ($hasFixedTopNavbar) {
            $bodyClasses[] = 'has-fixed-top-navbar';
        }
    @endphp
    {{-- Tambahkan deklarasi authUserId agar selalu tersedia di semua halaman --}}
    <script>
        window.authUserId = {{ Auth::check() ? Auth::id() : 'null' }};
    </script>
</head>
<body class="{{ implode(' ', $bodyClasses) }}">
    <div class="toast-container position-fixed top-0 end-0 p-3">
      <!-- Toast akan ditambahkan di sini oleh JS -->
    </div>

    @if($isPureUser)
        {{-- Tidak ada navbar atas untuk peran pengguna murni --}}
    @else
        {{-- Navbar Atas hanya untuk Admin dan Tamu --}}
        {{-- Navbar ini sekarang akan kosong. --}}
    @endif

    <main> {{-- Removed container and mt-4. Child views will handle their own container. Body padding-top handles navbar spacing. --}}
        @yield('content')
    </main>

    {{-- Pastikan authUserId didefinisikan sebelum skrip lain yang mungkin membutuhkannya --}}
    <script>
        window.authUserId = {{ Auth::check() ? Auth::id() : 'null' }};
    </script>


    @auth
        @if($isPureUser)
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
                        @if(isset($unreadNotificationsCountGlobal) && $unreadNotificationsCountGlobal > 0)
                            <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $unreadNotificationsCountGlobal > 99 ? '99+' : $unreadNotificationsCountGlobal }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('user.pesanan.index') }}" class="nav-item-bottom {{ request()->routeIs('user.pesanan.index') ? 'active' : '' }}">
                        <i class="bi bi-receipt nav-icon"></i>
                        <span class="nav-text">Pesanan</span>
                    </a>
                    <a href="{{ route('user.chat.index') }}" class="nav-item-bottom {{ request()->routeIs('user.chat.index') ? 'active' : '' }}">
                        <i class="bi bi-chat-dots nav-icon"></i>
                        <span class="nav-text">Chat</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-item-bottom {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="bi bi-person nav-icon"></i>
                        <span class="nav-text">Profil</span>
                    </a>
                </div>
            </nav>
            <style>
                @media (max-width: 991.98px) {
                    body.has-bottom-nav {
                        padding-bottom: 58px; /* Height of bottom nav */
                    }
                }

                /* Enhanced styles for navigation items */
                .nav-link {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.5rem 1rem;
                    transition: all 0.3s ease;
                }

                .nav-link:hover {
                    background-color: var(--app-secondary-bg);
                    border-radius: 0.375rem;
                }

                .nav-link.active {
                    color: var(--app-link-color);
                    font-weight: 500;
                }

                .nav-link i {
                    font-size: 1.1rem;
                }

                /* Mobile bottom navigation enhancements */
                .user-mobile-bottom-nav {
                    background-color: var(--app-navbar-bg);
                    border-top: 1px solid var(--app-card-border-color);
                    box-shadow: 0 -1px 3px var(--app-border-color-translucent);
                    padding: 0.5rem 0;
                }

                .nav-item-bottom {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 0.5rem;
                    color: var(--app-navbar-color);
                    text-decoration: none;
                    transition: all 0.3s ease;
                }

                .nav-item-bottom:hover {
                    color: var(--app-link-color);
                }

                .nav-item-bottom.active {
                    color: var(--app-link-color);
                }

                .nav-item-bottom .nav-icon {
                    font-size: 1.25rem;
                    margin-bottom: 0.25rem;
                }

                .nav-item-bottom .nav-text {
                    font-size: 0.75rem;
                    font-weight: 500;
                }

                .badge {
                    font-size: 0.65rem;
                    padding: 0.25em 0.5em;
                }
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
            {{-- Seller Desktop Navbar --}}
            <nav class="seller-navbar desktop-navbar d-none d-lg-flex">
                <div class="nav-logo">
                    <a href="{{ route('seller.dashboard') }}">
                        <img src="{{ asset('img/logo1.png') }}" alt="KantinKu" style="height: 36px; vertical-align: middle; margin-right: 0.5rem;">
                        <span>KantinKu Seller</span>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ route('seller.dashboard') }}" class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.orders.index') }}" class="nav-link {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}">
                            <i class="bi bi-receipt"></i> Pesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.menus.index') }}" class="nav-link {{ request()->routeIs('seller.menus.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i> Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.reports.index') }}" class="nav-link {{ request()->routeIs('seller.reports.index') ? 'active' : '' }}">
                            <i class="bi bi-bar-chart-line"></i> Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.chat.index') }}" class="nav-link {{ request()->routeIs('seller.chat.index') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots"></i> Chat
                        </a>
                    </li>
                </ul>
                <div class="nav-profile">
                    <button class="theme-toggle btn btn-link nav-link px-2 me-2" title="Ganti Tema" type="button">
                        <i class="bi bi-sun-fill"></i>
                        <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                    </button>
                    <a href="{{ route('seller.profile.edit') }}" class="nav-link profile-link {{ request()->routeIs('seller.profile.edit') ? 'active' : '' }}">
                        <img src="{{ Auth::user()->profile_photo_url ?? asset('img/icon-default.png') }}" alt="Avatar" class="profile-avatar">
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                </div>
            </nav>
        @endif
    @endauth

    {{-- Desktop Navbar for Pure User & Mobile Top Navbar for Pure User and Seller --}}
    @if(Auth::check())
        @if(Auth::user()->hasRole('user') && !Auth::user()->hasRole('seller') && !Auth::user()->hasRole('admin')) {{-- isPureUser --}}
            <nav class="desktop-navbar d-none d-lg-flex">
                <div class="nav-logo">
                    <a href="{{ route('user.dashboard') }}">
                        <img src="{{ asset('img/logo1.png') }}" alt="KantinKu" style="height: 36px; vertical-align: middle; margin-right: 0.5rem;">
                        <span>KantinKu</span>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.notifications.index') }}" class="nav-link {{ request()->routeIs('user.notifications.index') ? 'active' : '' }}">
                            <i class="bi bi-bell"></i> Notifikasi
                            @if(isset($unreadNotificationsCountGlobal) && $unreadNotificationsCountGlobal > 0)
                                <span class="badge">{{ $unreadNotificationsCountGlobal > 99 ? '99+' : $unreadNotificationsCountGlobal }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.pesanan.index') }}" class="nav-link {{ request()->routeIs('user.pesanan.index') ? 'active' : '' }}">
                            <i class="bi bi-receipt"></i> Pesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.chat.index') }}" class="nav-link {{ request()->routeIs('user.chat.index') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots"></i> Chat
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.cart.index') }}" class="nav-link {{ request()->routeIs('user.cart.index') ? 'active' : '' }}">
                            <i class="bi bi-cart-fill"></i> Keranjang
                        </a>
                    </li>
                </ul>
                <div class="nav-profile">
                    <button class="theme-toggle btn btn-link nav-link px-2 me-2" title="Ganti Tema" type="button">
                        <i class="bi bi-sun-fill"></i>
                        <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                    </button>
                    <a href="{{ route('profile.edit') }}" class="nav-link profile-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <img src="{{ Auth::user()->profile_photo_url ?? asset('img/icon-default.png') }}" alt="Avatar" class="profile-avatar">
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                </div>
            </nav>

            <nav class="mobile-top-navbar d-lg-none">
                <div class="nav-logo">
                    <a href="{{ route('user.dashboard') }}">
                        <img src="{{ asset('img/logo1.png') }}" alt="KantinKu Logo" style="height: 28px;"> {{-- Sedikit penyesuaian tinggi jika diperlukan --}}
                        <span>KantinKu</span>
                    </a>
                </div>
                <div class="nav-page-title">
                    <h5 class="mb-0">@yield('title', 'Chat')</h5>
                </div>
                <div class="nav-actions">
                    <button class="theme-toggle btn btn-link nav-link px-2" title="Ganti Tema" type="button">
                        <i class="bi bi-sun-fill"></i>
                        <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                    </button>
                    {{-- Tombol aksi mobile lainnya bisa ditambahkan di sini jika perlu --}}
                </div>
            </nav>
        @elseif(Auth::user()->hasRole('seller')) {{-- Seller Mobile Top Navbar --}}
            <nav class="mobile-top-navbar d-lg-none"> {{-- Reuse .mobile-top-navbar class for styling and fixed position --}}
                <div class="nav-logo">
                    <a href="{{ route('seller.dashboard') }}">
                        <img src="{{ asset('img/logo1.png') }}" alt="KantinKu Logo" style="height: 28px;">
                        <span>KantinKu Seller</span>
                    </a>
                </div>
                <div class="nav-page-title">
                    <h5 class="mb-0">@yield('title', 'Dasbor Penjual')</h5>
                </div>
                <div class="nav-actions">
                    <button class="theme-toggle btn btn-link nav-link px-2" title="Ganti Tema" type="button">
                        <i class="bi bi-sun-fill"></i>
                        <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                    </button>
                </div>
            </nav>
        @endif
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(btn) {
            btn.addEventListener('show.bs.dropdown', function (event) {
                var menu = btn.nextElementSibling;
                if (menu && menu.classList.contains('dropdown-menu')) {
                    menu.style.position = 'fixed';
                    var rect = btn.getBoundingClientRect();
                    menu.style.top = (rect.bottom + window.scrollY) + 'px';
                    menu.style.left = (rect.left + window.scrollX) + 'px';
                    menu.style.zIndex = 3000;
                    menu.style.minWidth = rect.width + 'px';
                }
            });
            btn.addEventListener('hide.bs.dropdown', function (event) {
                var menu = btn.nextElementSibling;
                if (menu && menu.classList.contains('dropdown-menu')) {
                    menu.style.position = '';
                    menu.style.top = '';
                    menu.style.left = '';
                    menu.style.zIndex = '';
                    menu.style.minWidth = '';
                }
            });
        });
    });
    </script>
    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        @auth
            const userId = {{ Auth::id() }};

            // Check if Echo is available
            if (typeof window.Echo === 'undefined') {
                console.error('Laravel Echo is not available. Ensure bootstrap.js is loaded and configured correctly.');
                return;
            }

            // --- Listener for User-specific events ---
            console.log(`Listening for user events on private channel: user.${userId}`);
            window.Echo.private(`user.${userId}`)
                .listen('.new-order-for-user', (e) => {
                    console.log('NewOrderForUserEvent received:', e);

                    // Show toast notification for the user
                    showUserToast(e.message, `/user/pesanan/${e.order_id}`);

                    // Check if we are on the user's order list page
                    const userOrdersList = document.getElementById('user-orders-list');
                    if (userOrdersList) {
                        fetch(`/user/pesanan/${e.order_id}/card`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.html) {
                                    const newCard = document.createElement('div');
                                    newCard.innerHTML = data.html.trim();
                                    const firstChild = newCard.firstChild;

                                    const emptyMessage = userOrdersList.querySelector('.empty-orders');
                                    if(emptyMessage) {
                                        emptyMessage.parentElement.parentElement.remove();
                                    }

                                    userOrdersList.prepend(firstChild);

                                    firstChild.style.transition = 'background-color 0.5s ease-out';
                                    firstChild.style.backgroundColor = '#e0f2fe'; // Light blue
                                    setTimeout(() => {
                                        firstChild.style.backgroundColor = '';
                                    }, 2000);
                                }
                            })
                            .catch(error => console.error('Error fetching new order card:', error));
                    }
                })
                .listen('.order.completed', (e) => {
                    // Show toast notification for order completed
                    showUserToast(`Pesanan #${e.order.id} telah <b>selesai</b>! Silakan ambil pesanan Anda di kantin.`, `/user/pesanan/${e.order.id}`);
                })
                .listen('.order.status-changed', (e) => {
                    // Show toast notification for order status change
                    const statusMessages = {
                        'pending': 'Pesanan sedang menunggu konfirmasi',
                        'processing': 'Pesanan sedang diproses',
                        'cancelled': 'Pesanan telah dibatalkan'
                    };
                    const message = statusMessages[e.new_status] || `Status pesanan berubah menjadi ${e.new_status}`;
                    showUserToast(`Pesanan #${e.order_id}: ${message}`, `/user/pesanan/${e.order_id}`);
                });

            // --- Listener for Seller-specific events ---
            @if(Auth::user()->hasRole('seller'))
                const sellerId = userId; // Same as user ID for a seller
                console.log(`Listening for seller events on private channel: seller.${sellerId}`);

                window.Echo.private(`seller.${sellerId}`)
                    .listen('.new-order', (e) => {
                        console.log('New Order Event Received for Seller:', e);

                        showSellerToast(e.message, e.url);

                        if (window.location.pathname.includes('/seller/orders')) {
                            const urlParams = new URLSearchParams(window.location.search);
                            const currentStatusFilter = urlParams.get('status') || 'all';
                            const newOrderStatus = 'pending';

                            if (currentStatusFilter === 'all' || currentStatusFilter === newOrderStatus) {
                                fetchAndPrependOrder(e.order_id);
                            }
                        }
                    });
            @endif

            // Fungsi untuk mengambil dan menambahkan kartu pesanan baru secara dinamis
            async function fetchAndPrependOrder(orderId) {
                try {
                    const response = await fetch(`/seller/orders/${orderId}/card`);
                    if (!response.ok) {
                        throw new Error(`Gagal mengambil data pesanan: ${response.statusText}`);
                    }

                    const data = await response.json();

                    if (data.success && data.html) {
                        const emptyContainer = document.getElementById('empty-orders-container');
                        const ordersContainer = document.getElementById('orders-list-container');

                        if (emptyContainer) {
                            emptyContainer.classList.add('d-none');
                        }

                        if (ordersContainer) {
                            ordersContainer.classList.remove('d-none');
                            ordersContainer.insertAdjacentHTML('afterbegin', data.html);

                            const newCard = document.getElementById(`order-card-${orderId}`);
                            if(newCard) {
                                newCard.style.animation = 'highlight-and-fade 1.5s ease-out';
                                // Inisialisasi dropdown pada kartu baru
                                const newDropdownToggle = newCard.querySelector('[data-bs-toggle="dropdown"]');
                                if (newDropdownToggle && typeof bootstrap !== 'undefined') {
                                    new bootstrap.Dropdown(newDropdownToggle);
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Gagal memuat pesanan baru secara real-time:', error);
                }
            }
        @endauth
    });

    function showUserToast(message, url) {
        const toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) return;

        const toastId = 'toast-' + Date.now();
        const currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        const toastHTML = `
            <div id="${toastId}" class="toast toast-info" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="toast-header">
                    <div class="d-flex align-items-center me-2">
                        <i class="bi bi-check-circle-fill text-info me-2" style="font-size: 1.1rem;"></i>
                        <img src="{{ asset('img/logo1.png') }}" class="rounded" alt="Logo" style="width: 20px; height: 20px;">
                    </div>
                    <strong class="me-auto">Pesanan Berhasil!</strong>
                    <small>${currentTime}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <a href="${url}" class="text-decoration-none">
                    <div class="toast-body">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-receipt text-info me-2 mt-1" style="font-size: 1rem;"></i>
                            <div>
                                <div class="fw-semibold mb-1">${message}</div>
                                <small class="text-muted">
                                    <i class="bi bi-eye me-1"></i>
                                    Klik untuk melihat detail pesanan
                                </small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        const newToastEl = document.getElementById(toastId);
        const newToast = new bootstrap.Toast(newToastEl);
        newToast.show();
        newToastEl.addEventListener('hidden.bs.toast', () => newToastEl.remove());
    }

    function showSellerToast(message, url) {
        const toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) return;

        const toastId = 'toast-' + Date.now();
        const currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        const toastHTML = `
            <div id="${toastId}" class="toast toast-success" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="toast-header">
                    <div class="d-flex align-items-center me-2">
                        <i class="bi bi-bell-fill text-success me-2" style="font-size: 1.1rem;"></i>
                        <img src="{{ asset('img/logo1.png') }}" class="rounded" alt="Logo" style="width: 20px; height: 20px;">
                    </div>
                    <strong class="me-auto">Pesanan Baru!</strong>
                    <small>${currentTime}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <a href="${url}" class="text-decoration-none">
                    <div class="toast-body">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-cart-check text-success me-2 mt-1" style="font-size: 1rem;"></i>
                            <div>
                                <div class="fw-semibold mb-1">${message}</div>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    Klik untuk melihat detail pesanan
                                </small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        const newToastEl = document.getElementById(toastId);
        const newToast = new bootstrap.Toast(newToastEl);
        newToast.show();
        newToastEl.addEventListener('hidden.bs.toast', () => newToastEl.remove());
    }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time notification badge update
        function updateNotifBadge(count) {
            // Mobile
            const mobileBadge = document.querySelector('.user-mobile-bottom-nav .nav-item-bottom .bi-bell')?.parentElement?.querySelector('.badge');
            // Desktop
            const desktopBadge = document.querySelector('.desktop-navbar .nav-link .bi-bell')?.parentElement?.querySelector('.badge');

            if (mobileBadge) {
                if (count > 0) {
                    mobileBadge.textContent = count > 99 ? '99+' : count;
                    mobileBadge.style.display = '';
                } else {
                    mobileBadge.style.display = 'none';
                }
            }
            if (desktopBadge) {
                if (count > 0) {
                    desktopBadge.textContent = count > 99 ? '99+' : count;
                    desktopBadge.style.display = '';
                } else {
                    desktopBadge.style.display = 'none';
                }
            }
        }

        function fetchNotifCount() {
            fetch('/user/notifications/unread-count')
                .then(res => res.json())
                .then(data => {
                    updateNotifBadge(data.count);
                });
        }

        // Initial fetch
        fetchNotifCount();
        // Poll every 10 seconds
        setInterval(fetchNotifCount, 10000);

        // Also update badge on Echo events
        if (window.Echo && window.authUserId) {
            window.Echo.private(`user.${window.authUserId}`)
                .listen('.new-order-for-user', fetchNotifCount)
                .listen('.order.completed', fetchNotifCount)
                .listen('.order.status-changed', fetchNotifCount);
        }
    });
    </script>
</body>
</html>
