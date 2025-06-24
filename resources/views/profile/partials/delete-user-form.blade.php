<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Sesuaikan action route berdasarkan apakah user adalah seller atau bukan --}}
            @php
                $destroyRoute = Auth::user()->hasRole('seller') ? route('seller.profile.destroy') : route('profile.destroy');
            @endphp
            <form method="post" action="{{ $destroyRoute }}">
                @csrf
                @method('delete')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="confirmUserDeletionModalLabel">{{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                    </p>
                    <div class="mt-3">
                        <label for="password_delete" class="form-label visually-hidden">{{ __('Kata Sandi') }}</label>
                        <input
                            id="password_delete"
                            name="password"
                            type="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="{{ __('Kata Sandi') }}"
                        />
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Hapus Akun') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
