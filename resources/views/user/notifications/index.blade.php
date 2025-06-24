@extends('layouts.app')

@section('title', 'Notifikasi Saya')

@push('styles')
<style>
    .notification-item {
        transition: all 0.3s ease;
        border-left-width: 4px;
        border-left-style: solid;
        border-radius: 12px;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        overflow: hidden; /* Ensure content doesn't overflow rounded corners */
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        background-color: #f8f9fa;
    }

    .notification-item.unread {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-left-color: #2196f3;
        border-left-width: 6px;
        position: relative;
        animation: slideInFromRight 0.5s ease-out;
    }

    @keyframes slideInFromRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(33, 150, 243, 0.1) 50%, transparent 70%);
        animation: shimmer 2s infinite;
        pointer-events: none;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .notification-item.read {
        border-left-color: #e0e0e0;
        background-color: #ffffff;
    }

    .notification-icon {
        font-size: 1.5rem;
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        flex-shrink: 0; /* Prevent icon from shrinking */
    }

    .notification-item:hover .notification-icon {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .notification-content .title {
        font-weight: 700;
        font-size: 1rem;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .notification-item.unread .notification-content .title {
        color: #1565c0;
    }

    .notification-content .message {
        font-size: 0.9rem;
        color: #546e7a;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        word-wrap: break-word;
    }

    .notification-time {
        font-size: 0.8rem;
        color: #78909c;
        font-weight: 500;
        background-color: rgba(0, 0, 0, 0.05);
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        display: inline-block;
        white-space: nowrap;
    }

    .btn-mark-read {
        font-size: 0.8rem;
        font-weight: 600;
        color: #2196f3 !important;
        text-decoration: none;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        background-color: rgba(33, 150, 243, 0.1);
        transition: all 0.3s ease;
    }

    .btn-mark-read:hover {
        background-color: rgba(33, 150, 243, 0.2);
        color: #1976d2 !important;
        transform: translateY(-1px);
    }

    .empty-notifications {
        text-align: center;
        padding: 60px 20px;
        color: #78909c;
    }

    .empty-notifications i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        color: #bdbdbd;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #f44336;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Dark mode support */
    [data-bs-theme="dark"] .notification-item {
        background-color: #2b3035;
        border-color: #495057;
    }

    [data-bs-theme="dark"] .notification-item:hover {
        background-color: #343a40;
    }

    [data-bs-theme="dark"] .notification-item.unread {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d1b69 100%);
        border-left-color: #4fc3f7;
    }

    [data-bs-theme="dark"] .notification-content .title {
        color: #e3f2fd;
    }

    [data-bs-theme="dark"] .notification-content .message {
        color: #b0bec5;
    }

    [data-bs-theme="dark"] .notification-time {
        background-color: rgba(255, 255, 255, 0.1);
        color: #90a4ae;
    }

    .notification-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }

    .notification-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.75rem;
    }

    .notification-header .subtitle {
        opacity: 0.9;
        margin-top: 0.5rem;
        font-size: 0.95rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="notification-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-bell-fill me-2"></i>Notifikasi Saya</h2>
                <div class="subtitle">
                    <i class="bi bi-info-circle me-1"></i>
                    Tetap terhubung dengan aktivitas terbaru dari pesanan Anda
                    @if($unreadCount > 0)
                        <span class="badge bg-warning ms-2">{{ $unreadCount }} belum dibaca</span>
                    @endif
                </div>
            </div>
            @if($unreadCount > 0)
            <form action="{{ route('user.notifications.markallasread') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-light btn-sm">
                    <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($notifications->isEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body empty-notifications">
                <i class="bi bi-bell-slash"></i>
                <h5 class="mb-2">Tidak ada notifikasi untukmu saat ini</h5>
                <p class="text-muted mb-0">Cek kembali nanti ya! Notifikasi akan muncul di sini ketika ada aktivitas baru.</p>
            </div>
        </div>
    @else
        {{-- Notification counter --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan {{ $notifications->count() }} dari {{ $notifications->total() }} notifikasi
            </small>
            @if($notifications->hasPages())
                <small class="text-muted">
                    Halaman {{ $notifications->currentPage() }} dari {{ $notifications->lastPage() }}
                </small>
            @endif
        </div>

        <div class="list-group list-group-flush">
            @foreach($notifications as $notification)
                <div class="list-group-item list-group-item-action notification-item {{ $notification->read_at ? 'read' : 'unread' }} p-0 border-0 mb-2">
                    <div class="p-3">
                        <div class="d-flex w-100 position-relative">
                            <div class="me-3 position-relative">
                                <span class="notification-icon {{ $notification->data['icon_color_class'] ?? 'text-secondary' }}">
                                    <i class="bi {{ $notification->data['icon_class'] ?? 'bi-bell' }}"></i>
                                </span>
                                @if(!$notification->read_at)
                                    <span class="notification-badge">!</span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-1 notification-content title">{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</h6>
                                    <small class="notification-time">{{ $notification->data['time_ago'] ?? $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-2 notification-content message">{{ $notification->data['message'] ?? 'Anda memiliki notifikasi baru.' }}</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    @if(!$notification->read_at)
                                    <div class="mt-2">
                                        <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-mark-read">
                                                <i class="bi bi-check me-1"></i>
                                                Tandai sudah dibaca
                                            </button>
                                        </form>
                                    </div>
                                    @endif

                                    @if($notification->data['link'] && $notification->data['link'] !== '#')
                                    <div class="mt-2">
                                        <a href="{{ $notification->data['link'] }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>
                                            Lihat Detail
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginasi jika diperlukan --}}
        @if ($notifications->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for better UX
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // If clicking on buttons, don't add click effect
            if (e.target.closest('.btn-mark-read') || e.target.closest('.btn-outline-primary')) {
                return;
            }

            // Add a subtle click effect
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Highlight new notifications that arrive via real-time
    if (typeof window.Echo !== 'undefined') {
        const userId = {{ Auth::id() }};
        window.Echo.private(`user.${userId}`)
            .listen('.new-order-for-user', (e) => {
                // Refresh the page to show new notification
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .listen('.order.completed', (e) => {
                // Refresh the page to show new notification
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .listen('.order.status-changed', (e) => {
                // Refresh the page to show new notification
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });
    }

    // Add loading state for mark as read buttons
    const markAsReadButtons = document.querySelectorAll('.btn-mark-read');
    markAsReadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Memproses...';
            this.disabled = true;

            // Submit the form
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    // Add click handler for detail buttons
    const detailButtons = document.querySelectorAll('.btn-outline-primary');
    detailButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            // Let the default link behavior happen
        });
    });
});
</script>
@endpush
