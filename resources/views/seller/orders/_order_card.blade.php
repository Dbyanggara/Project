<div class="col-lg-6" id="order-card-{{ $order->id }}">
    <div class="order-card-modern">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="order-id">Pesanan #{{ $order->id }}</span>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill bg-primary order-status-badge">{{ $statusTranslations[$order->status] ?? ucfirst($order->status) }}</span>
                <div class="dropdown">
                    <button onclick="toggleDropdown('dropdown-content-{{ $order->id }}')" class="dropbtn">
                        <i class="bi bi-pencil-square"></i> Ubah Status
                        <i class="bi bi-caret-down-fill" style="font-size: 0.9em;"></i>
                    </button>
                    <div id="dropdown-content-{{ $order->id }}" class="dropdown-content">
                        @php
                            $statuses = ['pending', 'processing', 'completed', 'cancelled'];
                            $otherStatuses = array_filter($statuses, fn($s) => $s !== $order->status);
                        @endphp
                        @forelse($otherStatuses as $newStatus)
                            <a href="#" class="status-update-trigger" data-order-id="{{ $order->id }}" data-status="{{ $newStatus }}">
                                {{ $statusTranslations[$newStatus] ?? ucfirst($newStatus) }}
                            </a>
                        @empty
                            <span class="text-muted">Tidak ada status lain</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <div class="mb-2">
                        <strong>Pelanggan:</strong> {{ $order->user->name ?? 'N/A' }}
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong> {{ $order->user->email ?? '-' }}
                    </div>
                    <div class="mb-2">
                        <strong>No. Meja:</strong> {{ $order->shipping_address['nomor_meja'] ?? '' }}
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="mb-2 order-time text-md-end">
                        <i class="bi bi-clock"></i> {{ $order->created_at->format('d M Y, H:i') }}
                        <div class="text-muted" style="font-size: 0.8em;">({{ $order->created_at->diffForHumans() }})</div>
                    </div>
                </div>
            </div>

            <hr class="my-2">

            <div class="mb-2 items-summary">
                <strong>Item:</strong>
                {{ $order->orderItems->map(fn($item) => $item->quantity . 'x ' . $item->menu->name)->implode(', ') }}
            </div>

            @if($order->notes)
            <div class="mb-2 items-summary">
                <strong>Catatan:</strong>
                <em class="text-muted">"{{ $order->notes }}"</em>
            </div>
            @endif

            <div class="mb-3">
                <strong>Pembayaran:</strong>
                <span class="badge bg-{{ $order->payment_method == 'cod' ? 'warning-subtle text-warning-emphasis' : 'success-subtle text-success-emphasis' }}">{{ strtoupper($order->payment_method) }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="total-amount">Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                <div class="order-actions d-flex align-items-center gap-2">
                    <div class="btn-group" role="group" aria-label="Order Actions">
                        <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <form action="{{ route('seller.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus pesanan #{{ $order->id }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" {{ !in_array($order->status, ['pending', 'cancelled']) ? 'disabled' : '' }} title="{{ !in_array($order->status, ['pending', 'cancelled']) ? 'Hanya pesanan pending atau cancelled yang bisa dihapus' : 'Hapus Pesanan' }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form tersembunyi untuk update status -->
        <form id="status-update-form-{{ $order->id }}" action="{{ route('seller.orders.update-status', $order) }}" method="POST" class="d-none">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="">
        </form>
    </div>
</div>

<style>
.dropbtn {
  background-color: #3498DB;
  color: white;
  padding: 8px 18px;
  font-size: 15px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s;
  display: flex;
  align-items: center;
  gap: 0.5em;
}
.dropbtn:hover, .dropbtn:focus {
  background-color: #2980B9;
}
.dropdown {
  position: relative;
  display: inline-block;
}
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  overflow: auto;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 2000;
  border-radius: 0.5em;
  right: 0;
  margin-top: 4px;
}
.dropdown-content a, .dropdown-content span {
  color: #222;
  padding: 10px 18px;
  text-decoration: none;
  display: block;
  font-size: 15px;
  border-radius: 0.3em;
  transition: background 0.2s;
}
.dropdown-content a:hover {
  background-color: #e3eaf3;
}
.show {
  display: block;
}
</style>

<script>
function toggleDropdown(id) {
    // Close all other dropdowns
    document.querySelectorAll('.dropdown-content').forEach(function(el) {
        if (el.id !== id) el.classList.remove('show');
    });
    document.getElementById(id).classList.toggle('show');
}
// Close the dropdown if the user clicks outside of it
window.addEventListener('click', function(event) {
    if (!event.target.matches('.dropbtn') && !event.target.closest('.dropbtn')) {
        document.querySelectorAll('.dropdown-content').forEach(function(el) {
            el.classList.remove('show');
        });
    }
});
</script>
