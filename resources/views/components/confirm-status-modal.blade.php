<div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmStatusModalLabel">Konfirmasi Perubahan Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-question-circle-fill text-warning me-3" style="font-size: 2.5rem;"></i>
                    <div>
                        <p class="mb-0">Anda yakin ingin mengubah status pesanan <strong id="modalOrderId">#</strong> menjadi <strong id="modalNewStatus"></strong>?</p>
                        <small class="text-muted">Aksi ini akan memperbarui status pesanan secara permanen.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmStatusBtn">Ya, Ubah Status</button>
            </div>
        </div>
    </div>
</div>
