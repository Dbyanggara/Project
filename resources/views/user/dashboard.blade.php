    @extends('layouts.app') {{-- Menggunakan layout app yang baru dibuat atau yang sudah ada --}}

    @section('title', 'Beranda Menu')

    @section('content')
    <style>
        .filter-btn.active, .filter-btn:focus {
            background: #2563eb;
            color: #fff;
        }
        .kantin-card {
            border-radius: 1.25rem;
            box-shadow: 0 4px 16px rgba(99,102,241,0.08);
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
        }
        .kantin-card:hover {
            box-shadow: 0 8px 32px rgba(99,102,241,0.16);
            transform: translateY(-2px) scale(1.02);
        }
        .kantin-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }
        .kantin-status {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #22c55e;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.2rem 0.8rem;
            z-index: 2;
        }
        .kantin-status.closed {
            background: #ef4444;
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
        @media (min-width: 992px) {
            .bottom-nav { display: none; }
        }
    </style>
    <div class="container py-3 mb-5">
        <div class="mb-3">
            {{-- Sapaan dengan nama pengguna yang login --}}
            <h2 class="fw-bold mb-1">Halo, {{ Auth::user()->name ?? 'Mahasiswa' }}!</h2>
            <div class="text-muted mb-3">Mau makan apa hari ini?</div>
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Cari makanan atau kantin...">
                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-funnel"></i></button>
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
            <div class="row g-4">
                {{-- Loop untuk menampilkan setiap kantin dengan kolom responsif yang diupdate --}}
                @forelse ($kantins as $kantin)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card kantin-card h-100">
                            {{-- Asumsikan $kantin->status bisa 'Buka' atau 'Tutup' --}}
                            <span class="kantin-status {{ strtolower($kantin->status ?? 'tutup') === 'tutup' ? 'closed' : '' }}">
                                {{ $kantin->status ?? 'Tutup' }}
                            </span>
                            {{-- Ganti 'image_url' dengan atribut gambar kantin Anda --}}
                            <img src="{{ $kantin->image_url ?? 'https://via.placeholder.com/400x160.png?text=Gambar+Kantin' }}" class="kantin-img" alt="{{ $kantin->name ?? 'Nama Kantin' }}">
                            <button class="chat-btn chat-with-seller-btn" data-seller-id="{{ $kantin->seller_id }}" title="Chat dengan Penjual">
                                <i class="bi bi-chat-dots"></i>
                            </button>
                            <div class="card-body">
                                <h5 class="card-title mb-1">{{ $kantin->name ?? 'Nama Kantin' }}</h5>
                                {{-- Ganti 'location' dengan atribut lokasi kantin Anda --}}
                                <div class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> {{ $kantin->location ?? 'Lokasi Kantin' }}</div>
                                {{-- Ganti 'hours' dengan atribut jam buka kantin Anda --}}
                                <div class="small text-muted mb-1"><i class="bi bi-clock me-1"></i> {{ $kantin->hours ?? 'Jam Operasional' }}</div>
                                <div class="d-flex align-items-center mt-2">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    {{-- Ganti 'rating' dengan atribut rating kantin Anda --}}
                                    <span class="fw-semibold">{{ $kantin->rating ?? 'N/A' }}</span>
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

<!-- Chat Popup -->
<div id="chatPopup" class="d-none">
    <div id="chatHeader" class="d-flex justify-content-between align-items-center p-3 bg-primary text-white">
        <span id="chatParticipantName">Chat</span>
        <button type="button" class="btn-close btn-close-white" id="closeChatPopup"></button>
    </div>
    <div id="chatMessages" class="p-3" style="height: 300px; overflow-y: auto;"></div>
    <form id="chatInputForm" class="p-3 border-top">
        <div class="input-group">
            <input type="text" id="chatInput" class="form-control" placeholder="Ketik pesan...">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatPopup = document.getElementById('chatPopup');
    const chatHeaderName = document.getElementById('chatParticipantName');
    const chatMessagesContainer = document.getElementById('chatMessages');
    const chatInputForm = document.getElementById('chatInputForm');
    const chatInput = document.getElementById('chatInput');
    const closeChatPopupButton = document.getElementById('closeChatPopup');

    let currentConversationId = null;
    let currentUserId = {{ Auth::id() }};

    // Chat button click handler
    document.querySelectorAll('.chat-with-seller-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const sellerId = this.dataset.sellerId;
            try {
                const response = await fetch(`/chat/with/${sellerId}`);
                if (!response.ok) throw new Error('Tidak dapat memulai percakapan');
                const conversation = await response.json();
                openChatPopup(conversation.id, conversation.seller.name);
            } catch (error) {
                alert(error.message);
            }
        });
    });

    function openChatPopup(conversationId, participantName) {
        currentConversationId = conversationId;
        chatHeaderName.textContent = 'Chat dengan ' + participantName;
        chatMessagesContainer.innerHTML = '<p class="text-center text-muted">Memuat pesan...</p>';
        chatPopup.classList.remove('d-none');
        chatInput.focus();
        fetchMessages(conversationId);
    }

    if(closeChatPopupButton) {
        closeChatPopupButton.addEventListener('click', () => {
            chatPopup.classList.add('d-none');
            currentConversationId = null;
        });
    }

    async function fetchMessages(conversationId) {
        try {
            const response = await fetch(`/chat/conversation/${conversationId}/messages`);
            if (!response.ok) throw new Error('Gagal memuat pesan');
            const messages = await response.json();
            renderMessages(messages);
        } catch (error) {
            chatMessagesContainer.innerHTML = `<p class="text-center text-danger">${error.message}</p>`;
        }
    }

    function renderMessages(messages) {
        chatMessagesContainer.innerHTML = '';
        messages.forEach(message => {
            appendMessage(message);
        });
        scrollToBottom();
    }

    function appendMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message-bubble', 'mb-2', 'p-2', 'rounded');
        messageDiv.classList.add(message.sender_id === currentUserId ? 'bg-primary text-white ms-auto' : 'bg-light');
        messageDiv.style.maxWidth = '75%';
        messageDiv.textContent = message.body;
        chatMessagesContainer.appendChild(messageDiv);
    }

    function scrollToBottom() {
        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
    }

    if(chatInputForm) {
        chatInputForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const body = chatInput.value.trim();
            if (body === '' || !currentConversationId) return;

            chatInput.value = '';

            try {
                const response = await fetch(`/chat/conversation/${currentConversationId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ body })
                });
                if (!response.ok) throw new Error('Gagal mengirim pesan');
                const newMessage = await response.json();
                appendMessage(newMessage);
                scrollToBottom();
            } catch (error) {
                console.error('Error sending message:', error);
                chatInput.value = body;
            }
        });
    }

    // Initialize Laravel Echo for real-time updates
    if (typeof Echo !== 'undefined' && currentConversationId) {
        Echo.private(`chat.conversation.${currentConversationId}`)
            .listen('NewMessageSent', (e) => {
                if (e.message.sender_id !== currentUserId) {
                    appendMessage(e.message);
                    scrollToBottom();
                }
            });
    }
});
</script>
@endpush

@push('styles')
<style>
    .display-3 { font-size: 3.5rem; }
    .display-4 { font-size: 2.5rem; }
</style>
@endpush
