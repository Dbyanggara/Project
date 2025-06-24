@extends('layouts.app') {{-- Atau layouts.seller jika Anda punya layout khusus seller --}}

@section('title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<style>
    /* Anda bisa menyalin style dari profile.edit.blade.php atau membuat style khusus */
    .profile-card {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .profile-card .card-header {
        font-weight: 600;
        background-color: var(--app-secondary-bg); /* Menggunakan variabel CSS untuk tema */
        border-bottom: 1px solid var(--app-card-border-color); /* Menggunakan variabel CSS untuk tema */
    }
    .form-label {
        font-weight: 500;
    }
    .btn-danger-soft {
        color: #dc3545;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }
    .btn-danger-soft:hover {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }
    /* Jika Anda menggunakan navbar manual di sini, salin CSS .seller-navbar */
</style>
@endpush

@section('content')
{{-- Jika Anda ingin navbar seller yang sama persis seperti di seller.dashboard.blade.php,
     Anda perlu menyalin blok HTML <nav class="navbar ... seller-navbar ..."> ke sini.
     Namun, lebih baik mengandalkan navbar dari layouts.app.blade.php untuk konsistensi. --}}

{{-- The main container for content. Relies on body padding-top from layouts.app.blade.php --}}
<div class="container py-4">
    <h2 class="fw-bold mb-4">Pengaturan Akun Saya (Seller)</h2>

    @if (session('status') && session('message'))
        <div class="alert alert-{{ session('status') === 'kantin-updated' || session('status') === 'profile-updated' ? 'success' : 'info' }} alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Update Profile Information --}}
            <div class="card profile-card mb-4">
                <div class="card-header">
                    Informasi Profil
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">
                        Perbarui informasi profil akun Anda dan alamat email.
                    </p>
                    {{-- Pastikan $user tersedia dari controller --}}
                    <form method="post" action="{{ route('seller.profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Nama') }}</label>
                            <input id="name" name="name" type="text" class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name', 'updateProfileInformation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email', 'updateProfileInformation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="small text-muted">
                                        {{ __('Alamat email Anda belum diverifikasi.') }}
                                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">{{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}</button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 small text-success">
                                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-4">
                            <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
                            @if (session('status') === 'profile-updated')
                                <p class="mb-0 small text-success">{{ __('Tersimpan.') }}</p>
                            @endif
                        </div>
                    </form>
                    {{-- Form untuk mengirim ulang email verifikasi --}}
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

            {{-- Update Password --}}
            <div class="card profile-card mb-4">
                <div class="card-header">
                    Ubah Kata Sandi
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">
                        Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.
                    </p>
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Kata Sandi Saat Ini') }}</label>
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Kata Sandi Baru') }}</label>
                            <input id="password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Kata Sandi Baru') }}</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                             @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-4">
                            <button type="submit" class="btn btn-primary">{{ __('Simpan Kata Sandi') }}</button>
                            @if (session('status') === 'password-updated')
                                <p class="mb-0 small text-success">{{ __('Tersimpan.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Edit Kantin Details --}}
            <div class="card profile-card mb-4">
                <div class="card-header">
                    Detail Kantin Saya
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">
                        Atur informasi kantin Anda yang akan ditampilkan di beranda pengguna.
                    </p>
                    <form method="post" action="{{ route('seller.kantin.update') }}" enctype="multipart/form-data" id="kantinForm">
                        @csrf
                        @method('patch')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kantin_name" class="form-label">Nama Kantin</label>
                                    <input type="text" class="form-control @error('kantin_name') is-invalid @enderror"
                                           id="kantin_name" name="kantin_name"
                                           value="{{ old('kantin_name', $kantin->name ?? '') }}" required>
                                    @error('kantin_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="kantin_description" class="form-label">Deskripsi Kantin</label>
                                    <textarea class="form-control @error('kantin_description') is-invalid @enderror"
                                              id="kantin_description" name="kantin_description"
                                              rows="3">{{ old('kantin_description', $kantin->description ?? '') }}</textarea>
                                    @error('kantin_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="kantin_image" class="form-label">Gambar Kantin</label>
                                    @if($kantin && $kantin->image)
                                        <div class="mb-2">
                                            <img src="{{ $kantin->image_url }}"
                                                 alt="Gambar {{ $kantin->name }}"
                                                 class="img-thumbnail"
                                                 style="max-width: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('kantin_image') is-invalid @enderror"
                                           id="kantin_image" name="kantin_image"
                                           accept="image/jpeg,image/png,image/jpg,image/gif">
                                    @error('kantin_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB.</small>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="kantin_is_open"
                                           name="kantin_is_open" value="1"
                                           {{ old('kantin_is_open', ($kantin && $kantin->is_open) ? 'checked' : '') }}>
                                    <label class="form-check-label" for="kantin_is_open">Kantin Sedang Buka</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Edit Payment Methods --}}
            <div class="card profile-card mb-4">
                <div class="card-header">
                    <i class="bi bi-credit-card me-2"></i>Metode Pembayaran
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">
                        Atur metode pembayaran yang tersedia untuk pelanggan Anda.
                    </p>
                    <form method="post" action="{{ route('seller.payment.update') }}" id="paymentForm">
                        @csrf
                        @method('patch')

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">E-Wallet</h6>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="enable_gopay"
                                           name="payment_methods[]" value="gopay"
                                           {{ in_array('gopay', old('payment_methods', $paymentMethods ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="enable_gopay">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" alt="GoPay" class="me-2" style="height: 20px;">
                                        GoPay
                                    </label>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="enable_ovo"
                                           name="payment_methods[]" value="ovo"
                                           {{ in_array('ovo', old('payment_methods', $paymentMethods ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="enable_ovo">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" alt="OVO" class="me-2" style="height: 20px;">
                                        OVO
                                    </label>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="enable_dana"
                                           name="payment_methods[]" value="dana"
                                           {{ in_array('dana', old('payment_methods', $paymentMethods ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="enable_dana">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="DANA" class="me-2" style="height: 20px;">
                                        DANA
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3 text-success">Bayar di Tempat</h6>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="enable_cod"
                                           name="payment_methods[]" value="cod"
                                           {{ in_array('cod', old('payment_methods', $paymentMethods ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="enable_cod">
                                        <i class="bi bi-cash-coin me-2 text-success"></i>
                                        Bayar di Tempat (COD)
                                    </label>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-3 text-info">Pengaturan Tambahan</h6>

                                    <div class="mb-3">
                                        <label for="payment_instructions" class="form-label">Instruksi Pembayaran</label>
                                        <textarea class="form-control @error('payment_instructions') is-invalid @enderror"
                                                  id="payment_instructions" name="payment_instructions"
                                                  rows="3" placeholder="Contoh: Pembayaran dilakukan setelah pesanan diantar ke meja">{{ old('payment_instructions', $paymentSettings->instructions ?? '') }}</textarea>
                                        @error('payment_instructions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Instruksi khusus untuk pelanggan saat melakukan pembayaran</small>
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="auto_confirm_payment"
                                               name="auto_confirm_payment" value="1"
                                               {{ old('auto_confirm_payment', $paymentSettings->auto_confirm ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auto_confirm_payment">
                                            Konfirmasi Otomatis Pembayaran E-Wallet
                                        </label>
                                        <small class="text-muted d-block">Pesanan akan otomatis dikonfirmasi setelah pembayaran e-wallet berhasil</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Pengaturan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Logout Section --}}
            <div class="card profile-card mb-4">
                <div class="card-header">
                    Keluar dari Akun
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">
                        Klik tombol di bawah untuk keluar dari akun Anda.
                    </p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('Keluar') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kantinForm = document.getElementById('kantinForm');
    const paymentForm = document.getElementById('paymentForm');

    // Handle Kantin Form
    kantinForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Tampilkan loading state
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Terjadi kesalahan saat memperbarui detail kantin');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Detail kantin berhasil diperbarui',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat memperbarui detail kantin');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message
            });
        })
        .finally(() => {
            // Reset button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });

    // Handle Payment Form
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validasi minimal satu metode pembayaran dipilih
        const selectedMethods = this.querySelectorAll('input[name="payment_methods[]"]:checked');
        if (selectedMethods.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal satu metode pembayaran'
            });
            return;
        }

        const formData = new FormData(this);

        // Tampilkan loading state
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Terjadi kesalahan saat memperbarui pengaturan pembayaran');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pengaturan pembayaran berhasil diperbarui',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat memperbarui pengaturan pembayaran');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message
            });
        })
        .finally(() => {
            // Reset button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });

    // Toggle auto confirm based on payment methods
    const paymentMethodCheckboxes = document.querySelectorAll('input[name="payment_methods[]"]');
    const autoConfirmCheckbox = document.getElementById('auto_confirm_payment');

    function updateAutoConfirmVisibility() {
        const hasEWallet = Array.from(paymentMethodCheckboxes).some(checkbox =>
            checkbox.checked && ['gopay', 'ovo', 'dana'].includes(checkbox.value)
        );

        const autoConfirmContainer = autoConfirmCheckbox.closest('.mb-3');
        if (hasEWallet) {
            autoConfirmContainer.style.display = 'block';
        } else {
            autoConfirmContainer.style.display = 'none';
            autoConfirmCheckbox.checked = false;
        }
    }

    paymentMethodCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateAutoConfirmVisibility);
    });

    // Initialize visibility
    updateAutoConfirmVisibility();
});
</script>
@endpush
