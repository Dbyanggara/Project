<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KantinKu - Web Kantin Modern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
        }
        .brand {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            font-weight: 700;
            color: #6366f1;
            letter-spacing: 1px;
        }
        .hero {
            background: linear-gradient(120deg, #f8fafc 60%, #c7d2fe 100%);
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        }
        .feature-icon {
            font-size: 3rem;
            color: #6366f1;
        }
        .step-icon {
            font-size: 2.2rem;
            color: #4f46e5;
        }
        .testimonial {
            font-style: italic;
            color: #374151;
        }
        .footer {
            background: #fff;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand brand" href="#">KantinKu</a>
            <div class="d-flex gap-2 ms-auto">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Daftar</a>
                @endguest
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <!-- HERO -->
        <div class="row align-items-center mb-5 hero p-4">
            <div class="col-lg-6 text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-3">Selamat Datang di <span class="brand">KantinKu</span></h1>
                <p class="lead mb-4">Solusi modern untuk kantin sekolah, kampus, dan kantor. Pesan makanan, pantau status pesanan, dan nikmati fitur realtime serta chat langsung dengan penjual!</p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start mb-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">Mulai Pesan</a>
                    <a href="#fitur" class="btn btn-outline-primary btn-lg px-4">Lihat Fitur</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=600&q=80" alt="Kantin Modern" class="img-fluid rounded-4 shadow-lg" style="max-height: 340px; object-fit: cover;">
            </div>
        </div>
        <!-- FITUR -->
        <div id="fitur" class="row text-center mb-5">
            <h2 class="mb-4 fw-bold">Fitur Unggulan</h2>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <h5 class="fw-bold">Pesan & Bayar Online</h5>
                    <p class="text-muted">Pesan makanan/minuman favoritmu langsung dari HP atau komputer, tanpa antri!</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h5 class="fw-bold">Realtime Notifikasi</h5>
                    <p class="text-muted">Dapatkan update status pesanan, stok, dan chat secara realtime.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="fw-bold">Chat Penjual</h5>
                    <p class="text-muted">Langsung tanya atau konfirmasi ke penjual lewat fitur chat.</p>
                </div>
            </div>
        </div>
        <!-- CARA KERJA -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-10">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <h4 class="fw-bold mb-4 text-center">Cara Kerja KantinKu</h4>
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="step-icon mb-2"><i class="bi bi-search"></i></div>
                            <div class="fw-semibold">1. Pilih Menu</div>
                            <div class="text-muted small">Lihat & pilih makanan/minuman favoritmu.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="step-icon mb-2"><i class="bi bi-cart-plus"></i></div>
                            <div class="fw-semibold">2. Pesan & Bayar</div>
                            <div class="text-muted small">Pesan lewat aplikasi, bayar online/cashless.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="step-icon mb-2"><i class="bi bi-bell"></i></div>
                            <div class="fw-semibold">3. Tunggu Notifikasi</div>
                            <div class="text-muted small">Pantau status pesanan secara realtime.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="step-icon mb-2"><i class="bi bi-emoji-smile"></i></div>
                            <div class="fw-semibold">4. Ambil & Nikmati</div>
                            <div class="text-muted small">Ambil pesanan di kantin, tanpa antri!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TESTIMONI -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="bg-white rounded-4 shadow-sm p-4 text-center">
                    <h4 class="fw-bold mb-3">Apa Kata Mereka?</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="testimonial">"Pesan makanan jadi super cepat, nggak perlu antri lagi!"</div>
                            <div class="fw-semibold mt-2">- Siswa SMA</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="testimonial">"Sangat membantu untuk kantin kampus, transaksi jadi rapi dan mudah dipantau."</div>
                            <div class="fw-semibold mt-2">- Pengelola Kantin</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="testimonial">"Fitur chat-nya memudahkan komunikasi dengan penjual."</div>
                            <div class="fw-semibold mt-2">- Mahasiswa</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
        <div class="row text-center footer py-3 mt-4">
            <div class="col-12">
                <div class="mb-2">
                    <span class="brand">KantinKu</span> &copy; {{ date('Y') }}. All rights reserved.
                </div>
                <div class="small text-muted">
                    Made with <i class="bi bi-heart-fill text-danger"></i> for digital canteens.<br>
                    <a href="mailto:support@kantinku.com" class="text-decoration-none">support@kantinku.com</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
