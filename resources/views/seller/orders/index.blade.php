@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@push('styles')
<style>
    /* Navbar styles are now inherited from layouts.app.blade.php */

    .order-nav-filters .nav-link {
        color: var(--bs-secondary-color); /* Menggunakan variabel Bootstrap untuk teks muted */
        border: 1px solid transparent;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        font-weight: 500;
        padding: 0.75rem 1rem;
    }
    .order-nav-filters .nav-link.active,
    .order-nav-filters .nav-link:hover {
        color: var(--app-link-color);
        border-bottom-color: var(--app-link-color);
    }
    /* Style tambahan untuk teks pada filter aktif */
    .order-nav-filters .nav-link.active {
        background-color: var(--bs-primary-bg-subtle, #cfe2ff); /* Fallback jika var tidak ada */
        color: var(--bs-primary-text-emphasis, #084298) !important; /* Fallback jika var tidak ada */
        border-radius: 0.375rem 0.375rem 0 0; /* Sedikit rounded corner di atas */
    }
    .order-card-modern {
        background-color: var(--app-card-bg);
        border-radius: 0.75rem;
        box-shadow: var(--app-shadow-sm); /* Menggunakan shadow dari tema */
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .order-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .order-card-modern .card-header {
        background-color: var(--app-secondary-bg);
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        border-bottom: 1px solid var(--app-card-border-color);
    }
    .order-card-modern .card-body {
        padding: 1.25rem;
    }
    .order-card-modern .order-id {
        font-weight: 600;
        color: var(--app-text-color);
    }
    .order-card-modern .order-time {
        font-size: 0.85rem;
        color: var(--bs-secondary-color);
    }
    .order-card-modern .order-status-badge {
        font-size: 0.8rem;
        padding: 0.3em 0.7em;
    }
    .order-card-modern .items-summary {
        font-size: 0.9rem;
        color: var(--app-text-color);
    }
    .order-card-modern .total-amount {
        font-size: 1.1rem;
        font-weight: 700;
        color: #28a745;
    }
    .order-actions .btn {
        font-size: 0.85rem;
        padding: 0.375rem 0.75rem;
    }
    .empty-orders-container {
        text-align: center;
        padding: 3rem 1rem;
        background-color: var(--app-card-bg);
        border-radius: 0.75rem;
        box-shadow: var(--app-shadow-sm);
    }
    .empty-orders-container i {
        font-size: 3.5rem;
        color: var(--bs-secondary-color);
        margin-bottom: 1rem;
    }

    /* Disabled button styling */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn:disabled:hover {
        transform: none !important;
        box-shadow: var(--app-shadow-sm) !important;
    }

    /* Tooltip for disabled buttons */
    .btn:disabled[title] {
        position: relative;
    }

    .btn:disabled[title]:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        white-space: nowrap;
        z-index: 1000;
    }

    @keyframes highlight-and-fade {
        0% { background-color: rgba(0, 123, 255, 0.2); }
        100% { background-color: transparent; }
    }
    /* Custom styles for confirmation modal */
    .modal-header.bg-primary {
        background-color: var(--bs-primary) !important;
        color: white;
    }
    .modal-header .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%); /* Make close button white in dark header */
    }
    .modal-body .bi-question-circle-fill {
        font-size: 2.5rem;
        color: var(--bs-warning); /* Use Bootstrap warning color */
    }

    /* Ensure Bootstrap dropdowns work correctly */
    .dropdown {
        position: relative;
    }
    .dropdown-menu {
        z-index: 1050; /* Ensure it's above other content */
        position: absolute;
        top: 100%; /* Position below the toggle button */
        left: 0; /* Align to the left of the toggle button */
        min-width: 10rem; /* Standard Bootstrap dropdown width */
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 1rem;
        color: var(--app-text-color);
        text-align: left;
        list-style: none;
        background-color: var(--app-card-bg);
        background-clip: padding-box;
        border: 1px solid var(--app-card-border-color);
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.175);
    }
    .dropdown-menu.show {
        display: block !important;
    }
    .dropdown-item {
        color: var(--app-text-color);
    }
    .dropdown-item:hover {
        background-color: var(--app-secondary-bg);
    }
</style>
@endpush

@section('content')
{{-- Navbar Seller sekarang di-handle oleh layouts.app.blade.php --}}
<div class="container py-4"> {{-- style="margin-top: 80px;" dihapus --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Pesanan</h2>
    </div>
    <ul class="nav nav-pills order-nav-filters mb-4">
        @php
            $statuses = [
                'all' => 'Semua',
                'pending' => 'Tertunda',
                'processing' => 'Diproses',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];
        @endphp
        @foreach($statuses as $key => $displayName)
        <li class="nav-item">
            <a class="nav-link {{ (request('status', 'all') == $key) ? 'active' : '' }}"
               href="{{ route('seller.orders.index', ['status' => $key]) }}">
                {{ $displayName }}
            </a>
        </li>
        @endforeach
    </ul>
    @if($orders->isEmpty())
        <div class="empty-orders-container" id="empty-orders-container">
            <i class="bi bi-cart-x"></i>
            <h4 class="fw-semibold">Tidak Ada Pesanan</h4>
            <p class="text-muted">Belum ada pesanan dengan status "{{ $statuses[$statusFilter] ?? 'Semua' }}".</p>
        </div>
        <div class="row d-none" id="orders-list-container">
            {{-- Pesanan baru akan disisipkan di sini --}}
        </div>
    @else
        <div class="row" id="orders-list-container">
            @foreach($orders as $order)
                @include('seller.orders._order_card', ['order' => $order, 'statusTranslations' => $statuses])
            @endforeach
        </div>
    @endif
</div>
@include('components.confirm-status-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi semua dropdown Bootstrap yang ada di halaman saat pertama kali dimuat.
    try {
        const dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
        const dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    } catch (e) {
        console.error('Gagal menginisialisasi dropdown Bootstrap.', e);
    }

    const ordersContainer = document.getElementById('orders-list-container');
    const confirmStatusModalEl = document.getElementById('confirmStatusModal');
    const confirmStatusModal = new bootstrap.Modal(confirmStatusModalEl);
    const modalOrderIdSpan = document.getElementById('modalOrderId');
    const modalNewStatusSpan = document.getElementById('modalNewStatus');
    const confirmStatusBtn = document.getElementById('confirmStatusBtn');

    let currentOrderId = null;
    let currentNewStatus = null;

    if (ordersContainer) {
        // Menggunakan event delegation untuk menangani klik pada elemen yang dinamis
        ordersContainer.addEventListener('click', function(e) {
            const trigger = e.target.closest('.status-update-trigger');
            if (trigger) {
                e.preventDefault();
                currentOrderId = trigger.dataset.orderId;
                currentNewStatus = trigger.dataset.status;

                modalOrderIdSpan.textContent = `#${currentOrderId}`;
                modalNewStatusSpan.textContent = `"${trigger.textContent.trim()}"`;
                confirmStatusModal.show();
            }
        });

        confirmStatusBtn.addEventListener('click', function() {
            const form = document.getElementById(`status-update-form-${currentOrderId}`);
            if (form && currentNewStatus) {
                form.querySelector('input[name="status"]').value = currentNewStatus;

                const originalTrigger = ordersContainer.querySelector(`.status-update-trigger[data-order-id="${currentOrderId}"][data-status="${currentNewStatus}"]`);
                if (originalTrigger) {
                    originalTrigger.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                    originalTrigger.classList.add('disabled');
                }

                confirmStatusModal.hide();
                form.submit();
            }
        });
    }
});
</script>
@endpush
@endsection
