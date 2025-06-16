<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KantinKu - Solusi Kantin Digital Modern</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
            padding-top: 70px; /* Adjusted for sticky navbar */
        }
        .navbar {
            transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .brand {
            font-weight: 700;
            color: #6366f1;
            letter-spacing: 1px;
        }
        .hero {
            background: linear-gradient(120deg, #f8fafc 60%, #c7d2fe 100%);
            border-radius: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .hero .display-4 .brand {
            color: #4f46e5; /* Indigo-600 */
        }
        .hero-image-animated {
            animation: floatImage 4s ease-in-out infinite;
            border-radius: 1.5rem;
        }
        @keyframes floatImage {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .btn {
            transition: all 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #6366f1;
            border-color: #6366f1;
        }
        .btn-primary:hover {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        .btn-outline-primary {
            color: #6366f1;
            border-color: #6366f1;
        }
        .btn-outline-primary:hover {
            background-color: #6366f1;
            color: #fff;
        }
        .feature-icon {
            font-size: 2.8rem;
            color: #6366f1;
            margin-bottom: 1rem;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .step-icon {
            font-size: 2.2rem;
            color: #4f46e5;
        }
        .testimonial {
            font-style: italic;
            color: #374151;
        }
        .section-title {
            font-weight: 700;
            color: #1f2937; /* Gray-800 */
            margin-bottom: 3rem; /* Increased margin */
        }
        .feature-card {
            transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        }
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
        .how-it-works-card {
            background-color: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.07);
            padding: 2.5rem;
        }
        .step-item:hover .step-icon {
            transform: scale(1.15) rotate(-5deg);
        }
        .testimonial-card {
            background-color: #fff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .testimonial-card blockquote {
            font-size: 1rem;
            color: #4b5563; /* Gray-600 */
            border-left: 4px solid #818cf8; /* Indigo-400 */
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .testimonial-card .blockquote-footer {
            font-size: 0.9rem;
            color: #6366f1; /* Indigo-500 */
            font-weight: 600;
        }
        .footer {
            background: #fff;
            border-top: 1px solid #e5e7eb;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        .footer .brand {
            color: #4f46e5;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 fixed-top">
        <div class="container">
            <a class="navbar-brand brand" href="{{ url('/') }}">KantinKu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto d-flex gap-2 align-items-center">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-3">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Daftar</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                            this.closest('form').submit();"
                               class="btn btn-danger btn-sm rounded-pill px-3">
                                {{ __('Logout') }}
                            </a>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5 mt-4">
        <!-- HERO SECTION -->
        <section class="row align-items-center mb-5 hero p-lg-5 p-4">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">Selamat Datang di <span class="brand">KantinKu</span></h1>
                <p class="lead text-muted mb-4">Solusi modern untuk kantin sekolah, kampus, dan kantor. Pesan makanan, pantau status pesanan, dan nikmati fitur realtime serta chat langsung dengan penjual!</p>
                <div class="d-grid gap-2 d-sm-flex justify-content-center justify-content-lg-start">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 gap-3"><i class="bi bi-arrow-right-circle me-2"></i>Mulai Pesan</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 gap-3"><i class="bi bi-speedometer2 me-2"></i>Ke Dashboard</a>
                    @endguest
                    <a href="#fitur" class="btn btn-outline-primary btn-lg px-4 gap-3"><i class="bi bi-eye me-2"></i>Lihat Fitur</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=600&q=80" alt="Kantin Modern" class="img-fluid hero-image-animated shadow-lg" style="max-height: 350px; object-fit: cover;">
            </div>
        </section>

        <!-- FITUR UNGGULAN -->
        <section id="fitur" class="py-5">
            <h2 class="text-center section-title">Fitur Unggulan Kami</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100 feature-card">
                        <div class="feature-icon"><i class="bi bi-bag-check-fill"></i></div>
                        <h5 class="fw-bold mb-2">Pesan & Bayar Online</h5>
                        <p class="text-muted small">Pesan makanan/minuman favoritmu langsung dari HP atau komputer, tanpa antri!</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100 feature-card">
                        <div class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                        <h5 class="fw-bold mb-2">Notifikasi Realtime</h5>
                        <p class="text-muted small">Dapatkan update status pesanan, stok, dan chat secara realtime.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100 feature-card">
                        <div class="feature-icon"><i class="bi bi-chat-dots-fill"></i></div>
                        <h5 class="fw-bold mb-2">Chat Langsung Penjual</h5>
                        <p class="text-muted small">Langsung tanya atau konfirmasi ke penjual lewat fitur chat terintegrasi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CARA KERJA -->
        <section class="py-5 bg-light rounded-4 my-5">
            <div class="container">
                <h2 class="text-center section-title">Cara Kerja KantinKu</h2>
                <div class="row text-center">
                    <div class="col-md-3 mb-4 step-item">
                        <div class="step-icon mb-2"><i class="bi bi-search"></i></div>
                        <h6 class="fw-semibold">1. Pilih Menu</h6>
                        <p class="text-muted small">Lihat & pilih makanan/minuman favoritmu dari berbagai tenant.</p>
                    </div>
                    <div class="col-md-3 mb-4 step-item">
                        <div class="step-icon mb-2"><i class="bi bi-cart-plus"></i></div>
                        <h6 class="fw-semibold">2. Pesan & Bayar</h6>
                        <p class="text-muted small">Pesan lewat aplikasi, bayar online dengan mudah dan aman.</p>
                    </div>
                    <div class="col-md-3 mb-4 step-item">
                        <div class="step-icon mb-2"><i class="bi bi-bell-fill"></i></div>
                        <h6 class="fw-semibold">3. Tunggu Notifikasi</h6>
                        <p class="text-muted small">Pantau status pesananmu secara realtime hingga siap diambil.</p>
                    </div>
                    <div class="col-md-3 mb-4 step-item">
                        <div class="step-icon mb-2"><i class="bi bi-emoji-smile-fill"></i></div>
                        <h6 class="fw-semibold">4. Ambil & Nikmati</h6>
                        <p class="text-muted small">Ambil pesanan di kantin saat sudah siap, tanpa perlu antri!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONI -->
        <section class="py-5">
            <h2 class="text-center section-title">Apa Kata Mereka?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Pesan makanan jadi super cepat, nggak perlu antri lagi! Sangat praktis untuk istirahat yang singkat."</p>
                        </blockquote>
                        <figcaption class="blockquote-footer mt-3">
                            Siswa SMA
                        </figcaption>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Sangat membantu untuk kantin kampus, transaksi jadi rapi dan mudah dipantau. Pengelolaan jadi lebih efisien."</p>
                        </blockquote>
                        <figcaption class="blockquote-footer mt-3">
                            Pengelola Kantin
                        </figcaption>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Fitur chat-nya memudahkan komunikasi dengan penjual jika ada pertanyaan soal menu atau pesanan."</p>
                        </blockquote>
                        <figcaption class="blockquote-footer mt-3">
                            Mahasiswa
                        </figcaption>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- FOOTER -->
    <footer class="footer text-center py-4 mt-5">
        <div class="container">
            <div class="mb-2">
                <span class="brand">KantinKu</span> &copy; {{ date('Y') }}. All rights reserved.
            </div>
            <div class="small text-muted">
                Made with <i class="bi bi-heart-fill text-danger"></i> for modern digital canteens.<br>
                <a href="mailto:support@kantinku.com" class="text-decoration-none text-primary">support@kantinku.com</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
