<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pengguna - Admin KantinKu</title>
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
        .nav-link:hover .bi,
        .nav-link:hover {
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
                        Logout
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
                            {{-- Pastikan 'admin.users.index' adalah nama route yang benar --}}
                            <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" aria-current="page" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people-fill"></i>
                                Manajemen Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                             {{-- Pastikan 'admin.sellers.index' adalah nama route yang benar --}}
                            <a class="nav-link {{ request()->routeIs('admin.sellers.index') ? 'active' : '' }}" href="{{ route('admin.sellers.index') }}">
                                <i class="bi bi-shop"></i>
                                Manajemen Penjual
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- Pastikan 'admin.profile.edit' atau 'profile.edit' adalah nama route yang benar --}}
                            <a class="nav-link {{ request()->routeIs('admin.profile.edit') || request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle"></i>
                                Profil Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manajemen Pengguna</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        {{-- Ganti '#' dengan route untuk menambah pengguna baru, contoh: route('admin.users.create') --}}
                        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-circle"></i>
                            Tambah Pengguna Baru
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Pengguna</h5>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah Pengguna Baru
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center">ID</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" class="text-center">Peran</th>
                                        <th scope="col">Tgl Verifikasi Email</th>
                                        <th scope="col">Tgl Bergabung</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                    <tr>
                                        <td class="text-center">{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">
                                            @foreach ($user->roles as $role)
                                                <span class="badge rounded-pill
                                                    @if($role->name == 'admin') bg-primary
                                                    @elseif($role->name == 'seller') bg-success
                                                    @else bg-secondary @endif">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>{{ $user->email_verified_at ? $user->email_verified_at->format('d M Y, H:i') : 'Belum diverifikasi' }}</td>
                                        <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit Pengguna">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Ini tidak dapat diurungkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Pengguna">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center fst-italic text-muted">Tidak ada data pengguna ditemukan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($users->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $users->links() }}
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
