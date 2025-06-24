<div class="card order-card mb-4 shadow-sm" id="user-order-card-{{ $order->id }}">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Pesanan: <strong>{{ $order->id }}</strong></span>
        <span class="badge bg-{{ $order->status_color ?? 'secondary' }} order-status">{{ ucfirst($order->status) }}</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    @if($order->orderItems->first() && $order->orderItems->first()->menu && $order->orderItems->first()->menu->kantin)
                        @php
                            $kantin = $order->orderItems->first()->menu->kantin;
                        @endphp
                        <img src="{{ $kantin->image_url }}"
                             alt="{{ $kantin->name ?? 'Kantin' }}"
                             class="kantin-img me-3"
                             onerror="this.src='{{ asset('img/logo1.png') }}'">
                        <div>
                            <h5 class="mb-0">{{ $kantin->name ?? 'Kantin' }}</h5>
                            <small class="text-muted">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'Tanggal tidak tersedia' }}</small>
                        </div>
                    @else
                        <img src="{{ asset('img/icon-default.png') }}" alt="Kantin" class="kantin-img me-3">
                        <div>
                            <h5 class="mb-0">Kantin</h5>
                            <small class="text-muted">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'Tanggal tidak tersedia' }}</small>
                        </div>
                    @endif
                </div>
                <ul class="list-unstyled order-item-list mb-0">
                    @foreach($order->orderItems as $item)
                        @if($item->menu)
                        <li>{{ $item->quantity }}x {{ $item->menu->name }} <span class="text-muted">(@ Rp {{ number_format($item->price, 0, ',', '.') }})</span></li>
                        @else
                        <li>{{ $item->quantity }}x Menu (tidak tersedia) <span class="text-muted">(@ Rp {{ number_format($item->price, 0, ',', '.') }})</span></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <p class="mb-1 text-muted">Total Pembayaran:</p>
                <p class="order-total text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                <a href="{{ route('user.pesanan.show', $order->id) }}" class="btn btn-outline-primary btn-sm btn-detail-pesanan">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>
