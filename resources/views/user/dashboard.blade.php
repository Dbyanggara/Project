        @extends('layouts.app') {{-- Menggunakan layout app yang baru dibuat atau yang sudah ada --}}

    @section('title', 'Beranda Menu')

    @section('content')
    <style>
        .filter-btn.active, .filter-btn:focus {
            background: #2563eb;
            color: #fff;
        }
        .kantin-card {
            display: flex;
            flex-direction: column;
            height: 100%; /* Untuk membantu menyamakan tinggi kartu dalam satu baris */
            border-radius: 1.25rem;
            box-shadow: var(--app-shadow-sm, 0 4px 16px rgba(99,102,241,0.08)); /* Gunakan variabel tema */
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
            background-color: var(--app-card-bg); /* Pastikan background card mengikuti tema */
        }
        .kantin-card:hover {
            box-shadow: 0 8px 32px rgba(99,102,241,0.16);
            transform: translateY(-2px) scale(1.02);
        }
        .kantin-image { /* Style untuk div pembungkus gambar */
            height: 180px; /* Tinggi gambar konsisten */
            position: relative;
            overflow: hidden;
        }
        .kantin-image img { /* Style untuk tag img di dalam .kantin-image */
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .kantin-card:hover .kantin-image img {
            transform: scale(1.05);
        }
        .status-badge { /* Mengganti .kantin-status agar sesuai HTML */
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            color: #fff;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 0.375rem;
            padding: 0.3rem 0.75rem;
            z-index: 1;
        }
        .status-badge.open {
            background-color: #198754; /* Bootstrap success green */
        }
        .status-badge.closed {
            background-color: #dc3545; /* Bootstrap danger red */
        }
        .kantin-info {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* Membuat bagian info ini mengisi sisa ruang */
        }
        .kantin-info h3 {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--app-text-color); /* Menggunakan variabel tema */
        }
        .kantin-info p {
            font-size: 0.875rem;
            color: var(--app-text-muted-color, #6c757d); /* Menggunakan variabel tema */
            margin-bottom: 0.4rem; /* Sedikit mengurangi margin bawah paragraf */
            display: flex;
            align-items: center;
        }
        .kantin-info p .bi {
            margin-right: 0.5rem; /* Jarak ikon dan teks */
            font-size: 1rem; /* Ukuran ikon */
            flex-shrink: 0; /* Mencegah ikon menyusut */
        }
        .kantin-info .description {
            margin-bottom: 0.75rem;
            /* Batasi deskripsi menjadi 2 baris */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: calc(2 * 1.2em * 0.875rem); /* Perkiraan tinggi untuk 2 baris */
        }
        .kantin-actions {
            margin-top: auto; /* Mendorong tombol ke bawah */
            text-align: right; /* Jika tombol tidak full-width */
        }
        .chat-btn {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
            z-index: 2;
        }
        .chat-btn:hover {
            background: #2563eb;
            color: white;
            transform: scale(1.1);
        }
        .chat-with-seller-btn {
            position: absolute;
            bottom: 1rem; /* Sesuaikan dengan padding kartu jika perlu */
            left: 1rem;   /* Sesuaikan dengan padding kartu jika perlu */
            background-color: var(--app-primary-color, #0d6efd);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem; /* Ukuran ikon sedikit lebih kecil dari .chat-btn */
            box-shadow: var(--app-shadow-sm, 0 2px 8px rgba(0,0,0,0.1));
            transition: all 0.2s ease;
            z-index: 2;
            text-decoration: none;
        }
        .chat-with-seller-btn:hover {
            background-color: var(--app-link-hover-color, #0a58ca);
            transform: scale(1.1);
        }
        /* Real-time Notification Pop-up */
        #realTimeNotification {
            position: fixed;
            bottom: 20px; /* Adjust if you have a persistent bottom bar on this page for mobile */
            right: 20px;
            z-index: 1050;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            width: 300px;
            max-width: 90%; /* Ensure it looks good on small screens */
            cursor: pointer;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        #realTimeNotification.d-none { /* Bootstrap handles display:none, this is for transition */
            opacity: 0;
            transform: translateY(20px);
            pointer-events: none;
        }
        #realTimeNotification .btn-close {
            font-size: 0.75rem; /* Smaller close button for notification */
        }
        @media (min-width: 992px) {
            .bottom-nav { display: none; }
        }
        /* New User Registration Notification Pop-up */
        #newUserRegistrationNotification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
            background-color: #d1e7dd;
            border: 1px solid #badbcc;
            color: #0f5132;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            width: 350px;
            max-width: 90%;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        #newUserRegistrationNotification.d-none {
            opacity: 0;
            transform: translateY(-20px);
            pointer-events: none;
        }
    </style>
    <div class="container py-3 mb-5">
        <div class="mb-3">
            {{-- Sapaan dengan nama pengguna yang login --}}
            <h2 class="fw-bold mb-1">Halo, {{ Auth::user()->name ?? 'Mahasiswa' }}!</h2>
            <div class="text-muted mb-3">Mau makan apa hari ini?</div>
            <div class="input-group mb-3">
                <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari makanan atau kantin...">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#filterKantinModal" title="Filter Kantin">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
            <div class="mb-4">
                <button class="btn filter-btn active me-2 mb-2">Semua</button>
                <button class="btn filter-btn btn-outline-secondary me-2 mb-2">Makanan Utama</button>
                <button class="btn filter-btn btn-outline-secondary me-2 mb-2">Minuman</button>
                <button class="btn filter-btn btn-outline-secondary mb-2">Snack</button>
            </div>
        </div>
        <div class="mb-4">
            <h4 class="fw-bold mb-3">Kantin Kampus</h4>
            <div class="row g-4" id="kantinListContainer">
                {{-- Loop untuk menampilkan setiap kantin dengan kolom responsif yang diupdate --}}
                @forelse ($kantins as $kantin)
                    <div class="col-12 col-md-6 col-lg-3 d-flex kantin-item-col"> {{-- Tambahkan d-flex agar kartu dalam baris bisa sama tinggi --}}
                        <div class="kantin-card w-100" data-status="{{ $kantin->is_open ? 'open' : 'closed' }}" data-name="{{ $kantin->name }}"> {{-- Tambahkan w-100 agar kartu mengisi kolom --}}
                            <div class="kantin-image">
                                <img src="{{ $kantin->image_url }}" alt="{{ $kantin->name }}" class=""> {{-- img-fluid bisa dipertimbangkan dihapus jika .kantin-image img sudah mengatur width/height 100% --}}
                                @if($kantin->is_open)
                                    <span class="status-badge open">Buka</span>
                                @else
                                    <span class="status-badge closed">Tutup</span>
                                @endif
                            </div>
                            <div class="kantin-info">
                                <h3>{{ $kantin->name }}</h3>
                                <p class="description">{{ Str::limit($kantin->description, 100) }}</p>
                                <div class="kantin-actions">
                                    <a href="{{ route('user.kantin.menu', $kantin->id) }}" class="btn btn-primary">
                                        <i class="bi bi-basket"></i> Lihat Menu
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada kantin yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Menu Populer section can be added here if needed -->
</div>

<!-- Real-time Notification Pop-up -->
<div id="realTimeNotification" class="d-none">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong id="notificationSender">Nama Pengirim</strong>
            <button type="button" class="btn-close btn-sm" id="closeNotificationBtn" aria-label="Close"></button>
        </div>
        <p id="notificationMessage" class="mb-0 small text-muted">Isi pesan singkat...</p>
    </div>
</div>

<!-- New User Registration Notification Pop-up -->
<div id="newUserRegistrationNotification" class="d-none">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong id="newUserRegistrationTitle">Pendaftaran Baru!</strong>
        <button type="button" class="btn-close btn-sm" id="closeNewUserRegistrationNotificationBtn" aria-label="Close"></button>
    </div>
    <p id="newUserRegistrationMessage" class="mb-0 small">Seorang pengguna baru telah mendaftar.</p>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterKantinModal" tabindex="-1" aria-labelledby="filterKantinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterKantinModalLabel">Filter Kantin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Kantin:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kantinStatusFilter" id="kantinStatusSemua" value="semua" checked>
                        <label class="form-check-label" for="kantinStatusSemua">Semua</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kantinStatusFilter" id="kantinStatusBuka" value="open">
                        <label class="form-check-label" for="kantinStatusBuka">Buka</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kantinStatusFilter" id="kantinStatusTutup" value="closed">
                        <label class="form-check-label" for="kantinStatusTutup">Tutup</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="kantinSortByFilter" class="form-label fw-semibold">Urutkan Berdasarkan:</label>
                    <select class="form-select" id="kantinSortByFilter">
                        <option value="name-asc" selected>Nama (A-Z)</option>
                        <option value="name-desc">Nama (Z-A)</option>
                        {{-- Tambahkan opsi urutan lain jika ada, misal berdasarkan rating --}}
                        {{-- <option value="rating-desc">Rating Tertinggi</option> --}}
                        {{-- <option value="rating-asc">Rating Terendah</option> --}}
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="resetKantinFiltersBtn">Reset Filter</button>
                <button type="button" class="btn btn-primary" id="applyKantinFiltersBtn">Terapkan Filter</button>
            </div>
        </div>
    </div>
</div>

{{-- Sertakan komponen chat widget --}}
@include('components.chat-widget')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const kantinListContainer = document.getElementById('kantinListContainer');
    const initialKantinColElements = Array.from(kantinListContainer.querySelectorAll('.kantin-item-col'));

    // Modal Filter Elements
    const filterModalElement = document.getElementById('filterKantinModal');
    const filterModal = filterModalElement ? new bootstrap.Modal(filterModalElement) : null;
    const applyFiltersBtn = document.getElementById('applyKantinFiltersBtn');
    const resetFiltersBtn = document.getElementById('resetKantinFiltersBtn');

    function updateKantinList() {
        if (!kantinListContainer) return;

        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = document.querySelector('input[name="kantinStatusFilter"]:checked')?.value || 'semua';
        const selectedSortBy = document.getElementById('kantinSortByFilter')?.value || 'name-asc';

        let filteredAndSortedCols = initialKantinColElements.filter(colDiv => {
            const card = colDiv.querySelector('.kantin-card');
            if (!card) return false;

            // Status filter
            const kantinStatus = card.dataset.status;
            const statusMatch = (selectedStatus === 'semua') || (kantinStatus === selectedStatus);
            if (!statusMatch) return false;

            // Search filter (if search term exists)
            if (searchTerm) {
                const kantinName = card.dataset.name.toLowerCase();
                const kantinLocation = card.querySelector('.location').textContent.toLowerCase();
                // const kantinDescription = card.querySelector('.description').textContent.toLowerCase(); // Bisa ditambahkan jika perlu
                const searchMatch = kantinName.includes(searchTerm) || kantinLocation.includes(searchTerm);
                if (!searchMatch) return false;
            }
            return true;
        });

        // Sorting
        filteredAndSortedCols.sort((aCol, bCol) => {
            const cardA = aCol.querySelector('.kantin-card');
            const cardB = bCol.querySelector('.kantin-card');
            if (!cardA || !cardB) return 0;

            const nameA = cardA.dataset.name.toLowerCase();
            const nameB = cardB.dataset.name.toLowerCase();
            // const ratingA = parseFloat(cardA.dataset.rating || 0); // Jika ada rating
            // const ratingB = parseFloat(cardB.dataset.rating || 0);

            switch (selectedSortBy) {
                case 'name-asc':
                    return nameA.localeCompare(nameB);
                case 'name-desc':
                    return nameB.localeCompare(nameA);
                // case 'rating-desc':
                //     return ratingB - ratingA;
                // case 'rating-asc':
                //     return ratingA - ratingB;
                default:
                    return 0;
            }
        });

        // Re-render
        kantinListContainer.innerHTML = ''; // Clear current cards
        if (filteredAndSortedCols.length > 0) {
            filteredAndSortedCols.forEach(colDiv => {
                kantinListContainer.appendChild(colDiv);
            });
        } else {
            kantinListContainer.innerHTML = '<div class="col-12"><p class="text-center text-muted mt-4">Tidak ada kantin yang sesuai dengan filter dan pencarian Anda.</p></div>';
        }
    }

    // Event Listeners
    searchInput.addEventListener('input', updateKantinList);

    if (applyFiltersBtn && filterModal) {
        applyFiltersBtn.addEventListener('click', () => {
            updateKantinList();
            filterModal.hide();
        });
    }

    if (resetFiltersBtn && filterModal) {
        resetFiltersBtn.addEventListener('click', () => {
            const statusSemuaRadio = document.getElementById('kantinStatusSemua');
            if (statusSemuaRadio) statusSemuaRadio.checked = true;

            const sortBySelect = document.getElementById('kantinSortByFilter');
            if (sortBySelect) sortBySelect.value = 'name-asc';

            // Kosongkan search input juga jika diinginkan
            // searchInput.value = '';

            updateKantinList();
            filterModal.hide();
        });
    }

    // Chat Pop-up Logic
    // const openChatButton = document.getElementById('openChatButton'); // Tombol chat support umum sudah dihapus
    const chatPopupWidget = document.getElementById('chatPopupWidget'); // ID dari komponen chat-widget.blade.php
    const closeChatWidgetButton = document.getElementById('closeChatWidget'); // ID tombol close di dalam komponen
    const chatPopupHeaderTitle = chatPopupWidget ? chatPopupWidget.querySelector('.chat-popup-header h4') : null;
    const chatPopupBody = chatPopupWidget ? chatPopupWidget.querySelector('.chat-popup-body') : null;
    const chatMessageInput = document.getElementById('chatMessageInput'); // dari chat-widget
    const sendChatMessageButton = document.getElementById('sendChatMessage'); // dari chat-widget

    let currentChatTargetSellerId = null; // Menyimpan ID seller yang sedang dichat via pop-up
    let currentEchoChannel = null; // Menyimpan channel Echo yang sedang aktif

    console.log('Dashboard Chat script: chatPopupWidget ->', chatPopupWidget);
    console.log('Dashboard Chat script: closeChatWidgetButton ->', closeChatWidgetButton);
    if (!window.authUserId) {
        console.error("Dashboard Chat: authUserId tidak ditemukan. Fitur chat mungkin tidak berfungsi.");
    }

    // Event listeners untuk tombol "Chat dengan Penjual" pada setiap kartu kantin
    const chatWithSellerButtons = document.querySelectorAll('.chat-with-seller-btn');
    chatWithSellerButtons.forEach(button => {
        button.addEventListener('click', function() {
            const sellerId = this.dataset.sellerId;
            const sellerName = this.dataset.sellerName;

            if (chatPopupWidget && chatPopupHeaderTitle && chatPopupBody && window.authUserId) {
                currentChatTargetSellerId = sellerId; // Set target untuk pengiriman pesan
                chatPopupHeaderTitle.textContent = `Chat dengan ${sellerName}`;
                chatPopupBody.innerHTML = `<p style="text-align:center; color:#888;">Memuat pesan dengan ${sellerName}...</p>`; // Konten awal

                // Tinggalkan channel Echo sebelumnya jika ada
                if (currentEchoChannel) {
                    window.Echo.leave(currentEchoChannel);
                    console.log(`Left Echo channel: ${currentEchoChannel}`);
                    currentEchoChannel = null;
                }

                // Buat ID percakapan dan subscribe ke channel Echo
                const conversationId = generateConversationId(window.authUserId, sellerId);
                currentEchoChannel = `chat.${conversationId}`;

                console.log(`Attempting to listen on Echo channel: ${currentEchoChannel}`);
                if (window.Echo) {
                    window.Echo.private(currentEchoChannel)
                        .listen('.message.sent', (event) => {
                            console.log('Pesan diterima di pop-up via Echo:', event);
                            // Pastikan pesan untuk percakapan yang aktif di pop-up
                            if ( (event.sender_id == currentChatTargetSellerId && event.receiver_id == window.authUserId) ||
                                 (event.receiver_id == currentChatTargetSellerId && event.sender_id == window.authUserId) ) {
                                displayMessageInPopup(event, event.sender_id == window.authUserId);
                            }
                        });
                    console.log(`Subscribed to Echo channel: ${currentEchoChannel}`);
                } else {
                    console.error("Laravel Echo tidak terdefinisi.");
                }

                fetchAndDisplayMessagesForSeller(sellerId); // Ambil riwayat pesan

                chatPopupWidget.style.display = 'block';
                if (chatMessageInput) chatMessageInput.focus();
            } else {
                console.error('Elemen pop-up chat tidak ditemukan atau authUserId tidak ada.');
            }
        });
    });

    // Tombol tutup untuk pop-up chat utama
    if (closeChatWidgetButton && chatPopupWidget) {
        closeChatWidgetButton.addEventListener('click', function () {
            chatPopupWidget.style.display = 'none';
            currentChatTargetSellerId = null; // Reset target
            if (currentEchoChannel && window.Echo) { // Tinggalkan channel saat ditutup
                window.Echo.leave(currentEchoChannel);
                console.log(`Left Echo channel: ${currentEchoChannel} on close`);
                currentEchoChannel = null;
            }
            // Opsional: reset judul header dan body ke default jika diperlukan
            if (chatPopupHeaderTitle) chatPopupHeaderTitle.textContent = 'Chat Support';
            if (chatPopupBody) chatPopupBody.innerHTML = '<p>Welcome to chat! How can I help you today?</p>';
        });
    }

    // Kirim pesan dari pop-up
    if (sendChatMessageButton && chatMessageInput && chatPopupWidget) {
        const processSendMessage = async function() {
            const messageText = chatMessageInput.value.trim();
            if (messageText === '' || !currentChatTargetSellerId) {
                if (!currentChatTargetSellerId) alert('Pilih penjual untuk diajak chat terlebih dahulu.');
                return;
            }

            try {
                const response = await fetch(`/api/chat/send`, { // Pastikan route API ini ada
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        receiver_id: currentChatTargetSellerId,
                        message: messageText
                    })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Gagal mengirim pesan:', errorData);
                    alert('Gagal mengirim pesan: ' + (errorData.message || JSON.stringify(errorData.errors || errorData)));
                    return;
                }
                const sentMessageData = await response.json();
                displayMessageInPopup(sentMessageData, true); // Tampilkan pesan yang baru dikirim
                chatMessageInput.value = '';
            } catch (error) {
                console.error('Error mengirim pesan:', error);
                alert('Terjadi kesalahan saat mengirim pesan.');
            }
        };
        sendChatMessageButton.addEventListener('click', processSendMessage);
        chatMessageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) { // Kirim dengan Enter, Shift+Enter untuk baris baru
                e.preventDefault();
                processSendMessage();
            }
        });
    }

    // Fungsi untuk menampilkan pesan di pop-up
    function displayMessageInPopup(message, isSentByCurrentUser) {
        if (!chatPopupBody) return;

        const messageWrapper = document.createElement('div');
        messageWrapper.style.display = 'flex';
        messageWrapper.style.flexDirection = 'column';
        messageWrapper.style.alignItems = isSentByCurrentUser ? 'flex-end' : 'flex-start';
        messageWrapper.style.marginBottom = '10px';

        const messageDiv = document.createElement('div');
        messageDiv.style.padding = '8px 12px';
        messageDiv.style.borderRadius = '15px';
        messageDiv.style.maxWidth = '80%';
        messageDiv.style.wordWrap = 'break-word';
        messageDiv.style.backgroundColor = isSentByCurrentUser ? '#dcf8c6' : '#f1f0f0';
        messageDiv.style.color = '#333';

        const senderName = isSentByCurrentUser ? 'Anda' : (message.sender ? message.sender.name : 'Penjual');
        messageDiv.innerHTML = `
            <div style="font-weight: bold; font-size: 0.8em; margin-bottom: 2px; color: ${isSentByCurrentUser ? '#4CAF50' : '#007bff'};">${senderName}</div>
            <div style="font-size: 0.9em;">${message.message.replace(/\n/g, '<br>')}</div>
            <div style="font-size: 0.7em; color: #777; margin-top: 3px; text-align: right;">${new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
        `;
        messageWrapper.appendChild(messageDiv);

        // Hapus placeholder jika ada
        const placeholder = chatPopupBody.querySelector('p');
        if (placeholder && (placeholder.textContent.startsWith('Memuat pesan dengan') || placeholder.textContent.startsWith('Belum ada pesan') || placeholder.textContent.startsWith('Memuat riwayat pesan'))) {
            placeholder.remove();
        }

        chatPopupBody.appendChild(messageWrapper);
        chatPopupBody.scrollTop = chatPopupBody.scrollHeight; // Scroll ke bawah
    }

    // Fungsi untuk mengambil dan menampilkan riwayat pesan
    async function fetchAndDisplayMessagesForSeller(sellerId) {
        if (!chatPopupBody || !window.authUserId) return;
        try {
            const response = null;
            if (!response.ok) {
                let errorMsg = `Gagal memuat pesan: ${response.statusText}`;
                try {
                    const errorData = await response.json();
                    errorMsg = errorData.error || errorMsg;
                } catch (e) {}
                chatPopupBody.innerHTML = `<p style="text-align:center; color:red;">${errorMsg}</p>`;
                return;
            }
            const messages = await response.json();
            chatPopupBody.innerHTML = '';
            if (messages.length === 0) {
                chatPopupBody.innerHTML = '<p style="text-align:center; color:#888;">Belum ada pesan. Mulai percakapan!</p>';
            } else {
                messages.forEach(msg => {
                    displayMessageInPopup(msg, msg.sender_id == window.authUserId);
                });
            }
        } catch (error) {
            console.error('Error mengambil pesan untuk penjual:', error);
            chatPopupBody.innerHTML = '<p style="text-align:center; color:red;">Gagal memuat riwayat pesan.</p>';
        }
    }

    // Fungsi bantuan untuk generate ID percakapan (harus sama dengan backend)
    function generateConversationId(userId1, userId2) {
        const id1 = parseInt(userId1, 10);
        const id2 = parseInt(userId2, 10);
        return id1 < id2 ? `${id1}-${id2}` : `${id2}-${id1}`;
    }

    // Logika untuk notifikasi pengguna baru dan notifikasi real-time lainnya tetap di sini jika diperlukan.
    // ... (kode notifikasi yang sudah ada sebelumnya) ...

});
</script>
@endpush

@push('styles')
<style>
    .display-3 { font-size: 3.5rem; }
    .display-4 { font-size: 2.5rem; }
</style>
@endpush
