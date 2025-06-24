@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        @if($seller)
            {{-- Antarmuka Chat dengan Penjual --}}
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Chat dengan {{ $sellerName ?? $seller->name }}</h4>
                            @if ($seller->kantin)
                                <a href="{{ route('user.kantin.menu', ['id' => $seller->kantin->id]) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Menu {{ $seller->kantin->name }}
                                </a>
                            @else
                                <a href="{{ route('user.chat.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Penjual
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body chat-messages-container" id="chatMessagesContainer" style="height: 450px; overflow-y: auto; background-color: var(--app-card-bg) !important;">
                        {{-- Pesan akan dimuat di sini oleh JavaScript --}}
                        <p class="text-center text-muted" id="chatLoadingMessage">Memuat pesan...</p>
                    </div>
                    <div class="card-footer">
                        <div class="input-group">
                            <input type="text" id="chatMessageInput" class="form-control" placeholder="Ketik pesan Anda..." aria-label="Pesan">
                            <button class="btn btn-primary" id="sendChatMessageButton" type="button">
                                <i class="bi bi-send"></i> Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Daftar Penjual untuk Dipilih --}}
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="pt-3 pb-2 px-3 border-bottom">
                        <h5 class="mb-0">Daftar Penjual</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($sellers->isEmpty())
                            <div class="p-3 text-center text-muted">
                                <i class="bi bi-info-circle"></i>
                                <p class="mb-0">Belum ada penjual yang tersedia.</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($sellers as $sellerItem)
                                    <a href="{{ route('user.chat.index', ['seller_id' => $sellerItem->id, 'seller_name' => $sellerItem->name]) }}"
                                       class="list-group-item list-group-item-action d-flex align-items-center py-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span class="text-white fw-bold">{{ strtoupper(substr($sellerItem->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">{{ $sellerItem->name }}</h6>
                                            <small class="text-muted">Klik untuk chat</small>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
@if($seller)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessagesContainer = document.getElementById('chatMessagesContainer');
    const chatMessageInput = document.getElementById('chatMessageInput');
    const sendChatMessageButton = document.getElementById('sendChatMessageButton');
    const chatLoadingMessage = document.getElementById('chatLoadingMessage');

    const currentAuthUserId = {{ Auth::id() }};
    const currentSellerId = {{ $seller->id }};
    const currentSellerName = "{{ Str::limit(e($sellerName ?? $seller->name), 50) }}";

    console.log('Chat initialized with:', {
        currentAuthUserId,
        currentSellerId,
        currentSellerName
    });

    if (!currentAuthUserId) {
        console.error("User tidak terautentikasi. Chat tidak dapat berfungsi.");
        if(chatLoadingMessage) chatLoadingMessage.textContent = "Autentikasi gagal. Tidak bisa memuat chat.";
        if(chatMessageInput) chatMessageInput.disabled = true;
        if(sendChatMessageButton) sendChatMessageButton.disabled = true;
        return;
    }

    function displayMessage(message, isSentByCurrentUser) {
        if (!chatMessagesContainer) return;

        const messageWrapper = document.createElement('div');
        messageWrapper.classList.add('d-flex', 'mb-3');
        messageWrapper.style.flexDirection = 'column';
        messageWrapper.style.alignItems = isSentByCurrentUser ? 'flex-end' : 'flex-start';

        const messageDiv = document.createElement('div');
        messageDiv.style.padding = '10px 15px';
        messageDiv.style.borderRadius = '15px';
        messageDiv.style.maxWidth = '75%';
        messageDiv.style.wordWrap = 'break-word';
        // messageDiv.classList.add(isSentByCurrentUser ? 'ms-auto' : 'me-auto', 'shadow-sm'); // ms-auto/me-auto redundant due to parent's align-items
        messageDiv.classList.add('shadow-sm');

        if (isSentByCurrentUser) {
            messageDiv.classList.add('bg-success-subtle'); // Menggunakan kelas Bootstrap untuk tema
        } else {
            messageDiv.classList.add('bg-secondary-subtle'); // Menggunakan kelas Bootstrap untuk tema yang beradaptasi
        }
        // Warna teks akan diwariskan atau diatur oleh kelas Bootstrap di atas, atau default body text color.
        // Jika text-dark diperlukan secara eksplisit: messageDiv.classList.add('text-dark');

        const senderNameText = isSentByCurrentUser ? 'Anda' : currentSellerName;
        const senderNameColorClass = isSentByCurrentUser ? 'text-success' : 'text-primary';

        messageDiv.innerHTML = `
            <div style="font-weight: bold; font-size: 0.85em; margin-bottom: 3px;" class="${senderNameColorClass}">${senderNameText}</div>
            <div style="font-size: 0.95em;">${message.message.replace(/\n/g, '<br>')}</div>
            <div style="font-size: 0.75em; margin-top: 4px; text-align: right;" class="text-muted">${new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
        `;
        messageWrapper.appendChild(messageDiv);

        if (chatLoadingMessage && chatLoadingMessage.parentNode === chatMessagesContainer) {
            chatMessagesContainer.removeChild(chatLoadingMessage);
        }

        chatMessagesContainer.appendChild(messageWrapper);
        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
    }

    async function fetchAndDisplayMessages() {
        if (!chatMessagesContainer) return;
        try {
            console.log('Fetching messages for seller:', currentSellerId);
            const response = await fetch(`/api/chat/messages/${currentSellerId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error response:', errorText);
                throw new Error(`Gagal memuat pesan: ${response.statusText} (${response.status})`);
            }

            const messages = await response.json();
            console.log('Messages received:', messages);

            if(chatLoadingMessage && chatLoadingMessage.parentNode === chatMessagesContainer) {
                chatMessagesContainer.innerHTML = ''; // Clear loading/error
            }

            if (messages.length === 0) {
                const noMessageP = document.createElement('p');
                noMessageP.classList.add('text-center', 'text-muted', 'py-3');
                noMessageP.textContent = 'Belum ada pesan. Mulai percakapan!';
                chatMessagesContainer.appendChild(noMessageP);
            } else {
                // Initialize displayedMessageIds if not already done
                if (typeof displayedMessageIds === 'undefined') {
                    window.displayedMessageIds = new Set();
                }

                messages.forEach(msg => {
                    // Add message ID to prevent duplicates
                    if (!window.displayedMessageIds.has(msg.id)) {
                        window.displayedMessageIds.add(msg.id);
                        displayMessage(msg, msg.sender_id == currentAuthUserId);
                    }
                });
            }
        } catch (error) {
            console.error('Error mengambil pesan:', error);
            const errorText = 'Gagal memuat riwayat pesan. Coba lagi nanti.';
            if(chatLoadingMessage && chatLoadingMessage.parentNode === chatMessagesContainer) {
                chatLoadingMessage.textContent = errorText;
            } else if (chatMessagesContainer) {
                chatMessagesContainer.innerHTML = `<p class="text-center text-danger py-3">${errorText}</p>`;
            }
        }
    }

    async function sendMessage() {
        const messageText = chatMessageInput.value.trim();
        if (messageText === '') return;

        console.log('Sending message:', {
            receiver_id: currentSellerId,
            message: messageText
        });

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken);

            const response = await fetch(`/api/chat/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    receiver_id: currentSellerId,
                    message: messageText
                })
            });

            console.log('Send response status:', response.status);

            if (!response.ok) {
                const errorData = await response.json();
                console.error('Send error response:', errorData);
                throw new Error(errorData.message || errorData.error || 'Gagal mengirim pesan.');
            }

            // Jangan tampilkan pesan di sini, biarkan Echo yang handle
            // Pesan akan muncul otomatis melalui event broadcast
            chatMessageInput.value = '';
        } catch (error) {
            console.error('Error mengirim pesan:', error);
            alert('Terjadi kesalahan saat mengirim pesan: ' + error.message);
        }
    }

    if (sendChatMessageButton) {
        sendChatMessageButton.addEventListener('click', sendMessage);
        console.log('Send button event listener added');
    }

    if (chatMessageInput) {
        chatMessageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        console.log('Message input event listener added');
    }

    if (window.Echo) {
        const conversationId = generateConversationId(currentAuthUserId, currentSellerId);
        const channelName = `chat.${conversationId}`;
        console.log(`Mendengarkan di Echo channel: ${channelName}`);

        // Initialize displayedMessageIds globally if not already done
        if (typeof window.displayedMessageIds === 'undefined') {
            window.displayedMessageIds = new Set();
        }

        window.Echo.private(channelName)
            .listen('.message.sent', (event) => {
                console.log('Pesan baru diterima via Echo:', event);

                // Check if this message is for the current conversation
                if ((event.sender_id == currentAuthUserId && event.receiver_id == currentSellerId) ||
                    (event.sender_id == currentSellerId && event.receiver_id == currentAuthUserId)) {

                    // Prevent duplicate messages by checking message ID
                    if (!window.displayedMessageIds.has(event.id)) {
                        window.displayedMessageIds.add(event.id);
                        displayMessage(event, event.sender_id == currentAuthUserId);
                    } else {
                        console.log('Message already displayed, skipping:', event.id);
                    }
                }
            });
    } else {
        console.error("Laravel Echo tidak terdefinisi. Real-time chat tidak akan berfungsi.");
        if(chatLoadingMessage) chatLoadingMessage.textContent += " (Fitur real-time tidak aktif)";
    }

    function generateConversationId(userId1, userId2) {
        const id1 = parseInt(userId1, 10);
        const id2 = parseInt(userId2, 10);
        return id1 < id2 ? `${id1}-${id2}` : `${id2}-${id1}`;
    }

    fetchAndDisplayMessages();
    if (chatMessageInput) chatMessageInput.focus();
});
</script>
@endif
@endpush

@push('styles')
<style>
    .chat-messages-container {
        background-color: #f8f9fa;
    }

    @media (max-width: 767px) {
        .card-body[style] {
            height: 350px !important;
        }
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endpush
