@extends('layouts.app')

@section('content')
<style>
    .user-sidebar {
        min-height: 100vh;
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.06);
        padding: 2rem 1.5rem;
    }
    .user-menu-link {
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
        display: block;
        margin-bottom: 1.2rem;
        font-size: 1.1rem;
    }
    .user-menu-link.active, .user-menu-link:hover {
        color: #fff;
        background: #6366f1;
        border-radius: .5rem;
        padding: .5rem 1rem;
    }
    .cart-badge {
        background: #f43f5e;
        color: #fff;
        border-radius: 50%;
        font-size: .9rem;
        padding: 0.3em 0.7em;
        position: absolute;
        top: -10px;
        right: -10px;
    }
    .product-card {
        border-radius: 1rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        margin-bottom: 2rem;
    }
    .product-img {
        border-radius: 1rem 1rem 0 0;
        object-fit: cover;
        height: 180px;
        width: 100%;
    }
</style>
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3 mb-4">
            <div class="user-sidebar">
                <div class="mb-4 text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" class="rounded-circle mb-2" width="70" height="70">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.95rem;">{{ Auth::user()->email }}</div>
                </div>
                <a href="#produk" class="user-menu-link active">Beranda</a>
                <a href="#riwayat" class="user-menu-link">Riwayat Pemesanan</a>
                <a href="#keranjang" class="user-menu-link">Keranjang <span id="cart-badge" class="cart-badge" style="display:none;">0</span></a>
                <a href="#profil" class="user-menu-link">Profil</a>
            </div>
        </div>
        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Produk Section -->
            <div id="produk">
                <h4 class="fw-bold mb-4">Daftar Produk</h4>
                <div class="row">
                    @for($i=1; $i<=4; $i++)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card product-card">
                            <img src="https://source.unsplash.com/300x180/?food,{{ $i }}" class="product-img" alt="Produk {{ $i }}">
                            <div class="card-body">
                                <h5 class="card-title">Produk {{ $i }}</h5>
                                <p class="card-text text-muted">Deskripsi singkat produk makanan/minuman ke-{{ $i }}.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">Rp{{ number_format(10000 * $i,0,',','.') }}</span>
                                    <div>
                                        <button class="btn btn-outline-primary btn-sm me-2" onclick="addToCart('Produk {{ $i }}')">Masukkan ke Keranjang</button>
                                        <button class="btn btn-success btn-sm" onclick="showCheckout('Produk {{ $i }}', {{ 10000 * $i }})">Beli</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <!-- Riwayat Section -->
            <div id="riwayat" style="display:none;">
                <h4 class="fw-bold mb-4">Riwayat Pemesanan</h4>
                <div class="alert alert-info">Belum ada riwayat pemesanan.</div>
            </div>
            <!-- Keranjang Section -->
            <div id="keranjang" style="display:none;">
                <h4 class="fw-bold mb-4">Keranjang</h4>
                <ul class="list-group mb-3" id="cart-list"></ul>
                <button class="btn btn-primary" id="checkout-btn" style="display:none;" onclick="showCheckout()">Checkout</button>
            </div>
            <!-- Profil Section -->
            <div id="profil" style="display:none;">
                <h4 class="fw-bold mb-4">Profil</h4>
                <div class="alert alert-secondary">Fitur profil coming soon.</div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="checkout-form">
            <div class="mb-3">
                <label for="checkout-nama" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="checkout-nama" name="nama" readonly>
            </div>
            <div class="mb-3">
                <label for="checkout-harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="checkout-harga" name="harga" readonly>
            </div>
            <div class="mb-3">
                <label for="checkout-jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="checkout-jumlah" name="jumlah" value="1" min="1">
            </div>
            <div class="mb-3">
                <label for="checkout-catatan" class="form-label">Catatan</label>
                <textarea class="form-control" id="checkout-catatan" name="catatan" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">Bayar Sekarang</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
// Navigasi menu sidebar
const menuLinks = document.querySelectorAll('.user-menu-link');
menuLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        menuLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        ['produk','riwayat','keranjang','profil'].forEach(id => {
            document.getElementById(id).style.display = (this.getAttribute('href').includes(id)) ? '' : 'none';
        });
    });
});
// Keranjang interaksi demo
let cart = [];
function addToCart(nama) {
    const idx = cart.findIndex(item => item.nama === nama);
    if(idx > -1) { cart[idx].jumlah += 1; } else { cart.push({nama, jumlah:1}); }
    updateCartUI();
}
function updateCartUI() {
    const badge = document.getElementById('cart-badge');
    const list = document.getElementById('cart-list');
    const btn = document.getElementById('checkout-btn');
    let total = cart.reduce((a,b) => a+b.jumlah, 0);
    badge.innerText = total;
    badge.style.display = total ? '' : 'none';
    if(list) {
        list.innerHTML = cart.length ? cart.map(item => `<li class='list-group-item d-flex justify-content-between align-items-center'>${item.nama}<span class='badge bg-primary rounded-pill'>${item.jumlah}</span></li>`).join('') : '<li class="list-group-item">Keranjang kosong.</li>';
    }
    if(btn) btn.style.display = cart.length ? '' : 'none';
}
// Checkout modal
function showCheckout(nama = '', harga = '') {
    const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    document.getElementById('checkout-nama').value = nama;
    document.getElementById('checkout-harga').value = harga ? 'Rp'+harga.toLocaleString('id-ID') : '';
    document.getElementById('checkout-jumlah').value = 1;
    document.getElementById('checkout-catatan').value = '';
    modal.show();
}
document.getElementById('checkout-form').onsubmit = function(e) {
    e.preventDefault();
    alert('Checkout berhasil! (demo)');
    bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
};
updateCartUI();
</script>
@endsection
