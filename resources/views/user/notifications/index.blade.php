@extends('layouts.app')

@section('title', 'Notifikasi Saya - KantinKu')

@push('styles')
<style>
    .notification-item {
        transition: background-color 0.3s ease;
        border-left-width: 4px;
        border-left-style: solid;
    }

    .notification-item:hover {
        background-color: #f8f9fa; /* Light gray on hover */
    }

    .notification-item.unread {
        background-color: #e9f5fe; /* Lighter blue for unread */
        border-left-color: #0d6efd; /* Bootstrap primary blue */
    }
    .notification-item.read {
        border-left-color: #adb5bd; /* Bootstrap secondary/gray */
    }

    .notification-icon {
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #e9ecef; /* Light background for icon */
    }

    .notification-content .title {
        font-weight: 600;
    }

    .notification-content .message {
        font-size: 0.9rem;
        color: #495057;
    }

    .notification-time {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .btn-mark-read {
        font-size: 0.8rem;
    }

    .empty-notifications {
        text-align: center;
        padding: 50px 20px;
        color: #6c757d;
    }
    .empty-notifications i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Notifikasi</h2>
        @if(isset($unreadNotificationsCountGlobal) && $unreadNotificationsCountGlobal > 0)
        <form action="{{ route('user.notifications.markallasread') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-check2-all"></i> Tandai Semua Sudah Dibaca
            </button>
        </form>
        @endif
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($notifications->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body empty-notifications">
                <i class="bi bi-bell-slash"></i>
                <p class="h5">Tidak ada notifikasi untukmu saat ini.</p>
                <p>Cek kembali nanti ya!</p>
            </div>
        </div>
    @else
        <div class="list-group shadow-sm">
            @foreach($notifications as $notification)
                <a href="{{ $notification->data['link'] ?? '#' }}"
                   class="list-group-item list-group-item-action notification-item {{ $notification->read_at ? 'read' : 'unread' }} p-3">
                    <div class="d-flex w-100">
                        <div class="me-3">
                            <span class="notification-icon {{ $notification->data['icon_color_class'] ?? 'text-secondary' }}">
                                <i class="bi {{ $notification->data['icon_class'] ?? 'bi-bell' }}"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 notification-content title">{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</h6>
                                <small class="notification-time">{{ $notification->data['time_ago'] ?? $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 notification-content message">{{ $notification->data['message'] ?? 'Anda memiliki notifikasi baru.' }}</p>

                            @if(!$notification->read_at)
                            <div class="mt-2 text-end">
                                <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-primary p-0 btn-mark-read">
                                        Tandai sudah dibaca
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Contoh Paginasi jika diperlukan --}}
        {{--
        @if ($notifications->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @endif
        --}}
    @endif
</div>
@endsection
