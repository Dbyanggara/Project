<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Penjual - Admin KantinKu</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0; /* Height of navbar */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #343a40; /* Dark sidebar */
            color: #fff;
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
        }
        .nav-link {
            font-weight: 500;
            color: #adb5bd; /* Lighter text for nav links */
        }
        .nav-link .bi {
            margin-right: 8px;
            font-size: 1.1rem;
            color: #adb5bd;
        }
        .nav-link:hover,
        .nav-link:hover .bi {
            color: #fff;
        }
        .nav-link.active {
            color: #fff;
            background-color: #495057; /* Active link background */
        }
        .main-content {
            margin-left: 240px; /* Same as sidebar width */
            padding: 20px;
            padding-top: 70px; /* Space for top navbar */
        }
        .navbar-brand {
            font-weight: 700;
            color: #fff; /* Brand color on dark navbar */
        }
        .top-navbar {
            background-color: #6366f1; /* Primary color from welcome page */
            color: #fff;
        }
        .form-control:focus { /* Styling untuk konsistensi jika ada form di halaman ini */
            border-color: #818cf8;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }
        .table-hover tbody tr:hover {
            background-color: #f1f3f5; /* Warna hover yang lebih lembut untuk baris tabel */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark fixed-top top-navbar p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="{{ route('admin.dashboard') }}">KantinKu Admin</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="nav-link px-3" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-door-fill"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people-fill"></i>
                                Manajemen Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sellers.index') ? 'active' : '' }}" aria-current="page" href="{{ route('admin.sellers.index') }}">
                                <i class="bi bi-shop"></i>
                                Manajemen Penjual
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle"></i>
                                Profil Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manajemen Kantin</h1>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Kantin</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKantin">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah Kantin Baru
                        </button>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Modal Tambah Kantin -->
                        <div class="modal fade" id="modalTambahKantin" tabindex="-1" aria-labelledby="modalTambahKantinLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <form action="{{ route('admin.kantins.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                  <h5 class="modal-title" id="modalTambahKantinLabel">Tambah Kantin Baru</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label for="nama_kantin" class="form-label">Nama Kantin</label>
                                    <input type="text" class="form-control" id="nama_kantin" name="nama" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="penjual_id" class="form-label">Penanggung Jawab (User Penjual)</label>
                                    <select class="form-control" id="penjual_id" name="penjual_id" required>
                                      <option value="">-- Pilih Penjual --</option>
                                      @foreach($penjuals as $penjual)
                                        <option value="{{ $penjual->id }}">{{ $penjual->name }} ({{ $penjual->email }})</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                  <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center">ID</th>
                                        <th scope="col">Nama Kantin</th>
                                        <th scope="col">Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kantins as $kantin)
                                    <tr>
                                        <td class="text-center">{{ $kantin->id }}</td>
                                        <td>{{ $kantin->name }}</td>
                                        <td>{{ $kantin->location }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center fst-italic text-muted">Tidak ada data kantin ditemukan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($kantins->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $kantins->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
