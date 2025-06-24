<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light"> {{-- Default ke light, JS akan mengubahnya --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Penjual')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            font-family: 'Instrument Sans', sans-serif;
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

        .app-navbar .nav-link {
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease-in-out;
        }
        .app-navbar .nav-link .nav-text {
            font-size: 0.8rem;
            font-weight: 500;
        }
        .app-navbar .nav-link.active,
        .app-navbar .nav-link:hover {
            border-bottom-color: var(--bs-primary);
        }
        .app-navbar .nav-link.active {
            color: var(--bs-primary) !important;
        }
        [data-bs-theme="dark"] .app-navbar .nav-link.active {
            color: var(--bs-primary) !important;
        }
        .app-navbar .navbar-brand img {
            transition: transform 0.3s ease;
        }
        .app-navbar .navbar-brand:hover img {
            transform: rotate(-10deg) scale(1.1);
        }
        .dropdown-menu {
            background-color: var(--app-card-bg);
            border-color: var(--app-card-border-color);
        }
        .dropdown-item {
            color: var(--app-text-color) !important;
        }
        .dropdown-item:hover {
            background-color: var(--app-secondary-bg);
        }
        [data-bs-theme="dark"] .dropdown-divider {
            border-top-color: var(--app-card-border-color);
        }

        /* Seller Mobile Bottom Navigation Bar */
        .seller-mobile-bottom-nav {
            background-color: var(--app-navbar-bg);
            border-top: 1px solid var(--app-card-border-color);
            box-shadow: 0 -1px 3px var(--app-border-color-translucent);
            padding: 0.25rem 0;
            z-index: 1030;
        }
        .seller-mobile-bottom-nav .nav-item-bottom {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            padding: 0.5rem 0.25rem;
            text-decoration: none;
            color: var(--app-navbar-color);
            transition: color 0.2s ease-in-out;
        }
        .seller-mobile-bottom-nav .nav-item-bottom .nav-icon {
            font-size: 1.25rem;
            margin-bottom: 0.2rem;
        }
        .seller-mobile-bottom-nav .nav-item-bottom .nav-text {
            font-size: 0.7rem;
            line-height: 1;
        }
        .seller-mobile-bottom-nav .nav-item-bottom.active {
            color: var(--app-link-color);
        }

        @media (max-width: 991.98px) {
            body.has-bottom-nav {
                padding-bottom: 60px; /* Adjust based on bottom nav height */
            }
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
    </style>
</head>
<body class="font-sans antialiased @if(Auth::check() && Auth::user()->hasRole('seller')) has-bottom-nav @endif">
    <nav class="navbar navbar-expand-lg shadow-sm app-navbar py-0">
        <div class="container-fluid px-4">
            {{-- Modified navbar-brand to stack logo and text vertically --}}
            <a class="navbar-brand d-flex flex-column align-items-center" href="{{ route('seller.dashboard') }}" style="padding-top: 0.3rem; padding-bottom: 0.3rem;">
                <img src="{{ asset('img/logo1.png') }}" alt="KantinKu Logo" style="height: 30px; margin-bottom: 3px;">
                <div style="line-height: 1; text-align: center;">
                    <span class="fw-semibold" style="font-size: 0.8rem;">KantinKu</span>
                    <span class="badge bg-primary" style="font-size: 0.65rem; vertical-align: baseline; margin-left: 2px;"></span>
                </div>
            </a>

            {{-- Navbar Title - Centered --}}
            <div class="mx-auto"> {{-- mx-auto will push it to the center if space allows --}}
                <span class="navbar-text fw-semibold fs-5">@yield('navbar_title', 'Tambah Menu Baru')</span>
            </div>

            <div class="d-flex align-items-center gap-3 ms-lg-auto">
                <button class="theme-toggle btn btn-link nav-link px-2" title="Ganti Tema">
                    <i class="bi bi-sun-fill"></i>
                    <i class="bi bi-moon-stars-fill" style="display: none;"></i>
                </button>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <!-- Toast Container for Notifications -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <!-- Toast notifications will be added here by JavaScript -->
    </div>

    @if(Auth::check() && Auth::user()->hasRole('seller'))
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    @auth
        @if(Auth::user()->hasRole('seller'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sellerId = {{ Auth::id() }};

                if (typeof Echo !== 'undefined') {
                    console.log(`Echo is ready, listening for orders on seller.${sellerId}`);
                    Echo.private(`seller.${sellerId}`)
                        .listen('.new-order', (e) => {
                            console.log('New Order Event Received:', e);

                            showToast(e.message, e.url);

                            if (window.location.pathname.includes('/seller/orders')) {
                                const urlParams = new URLSearchParams(window.location.search);
                                const currentStatusFilter = urlParams.get('status') || 'all';
                                const newOrderStatus = 'pending';

                                if (currentStatusFilter === 'all' || currentStatusFilter === newOrderStatus) {
                                    fetchAndPrependOrder(e.order_id);
                                }
                            }
                        });
                } else {
                    console.error('Laravel Echo not configured!');
                }

                // Gunakan event delegation untuk tombol update status
                document.body.addEventListener('click', function(event) {
                    if (event.target.matches('.status-update-trigger')) {
                        const button = event.target;
                        const orderId = button.dataset.orderId;
                        const newStatus = button.dataset.status;
                        const form = document.getElementById(`status-update-form-${orderId}`);

                        if (form) {
                            if (confirm(`Ubah status pesanan #${orderId} menjadi ${newStatus}?`)) {
                                form.querySelector('input[name="status"]').value = newStatus;
                                button.disabled = true;
                                button.innerHTML = '<i class="bi bi-hourglass-split"></i>...';
                                form.submit();
                            }
                        }
                    }
                });

                function showToast(message, url) {
                    const toastContainer = document.querySelector('.toast-container');
                    if (!toastContainer) return;

                    const toastId = 'toast-' + Date.now();
                    const currentTime = new Date().toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

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

                    // Hapus elemen toast dari DOM setelah ditutup
                    newToastEl.addEventListener('hidden.bs.toast', () => {
                        newToastEl.remove();
                    });

                    // Tambahkan efek suara notifikasi (opsional)
                    try {
                        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
                        audio.volume = 0.3;
                        audio.play().catch(() => {}); // Ignore errors if audio fails
                    } catch (e) {
                        // Ignore audio errors
                    }
                }

                async function fetchAndPrependOrder(orderId) {
                    try {
                        const response = await fetch(`/seller/orders/${orderId}/card`);
                        if (!response.ok) throw new Error('Failed to fetch order card');

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
                                    const cardBody = newCard.querySelector('.order-card-modern');
                                    if(cardBody) cardBody.style.animation = 'highlight-and-fade 1.5s ease-out';
                                }

                                // Inisialisasi dropdown pada kartu baru
                                const newDropdownToggle = newCard.querySelector('[data-bs-toggle="dropdown"]');
                                if (newDropdownToggle && typeof bootstrap !== 'undefined') {
                                    new bootstrap.Dropdown(newDropdownToggle);
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Failed to fetch and prepend order:', error);
                    }
                }
            });
        </script>
        @endif
    @endauth

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
