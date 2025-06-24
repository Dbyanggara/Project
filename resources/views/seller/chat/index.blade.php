@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="container py-4">
    <div class="row">
        @if($chatWithUser)
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Chat dengan {{ $chatWithUserName ?? $chatWithUser->name }}</h4>
                            <a href="{{ route('seller.chat.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                    <div class="card-body chat-messages-container" id="chatMessagesContainer" style="height: 450px; overflow-y: auto; background-color: var(--app-card-bg) !important;">
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
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="pt-3 pb-2 px-3 border-bottom">
                        <h5 class="mb-0">Daftar Chat Pengguna</h5>
                    </div>
                    <div class="card-body p-0" id="userListContainer" style="max-height: 600px; overflow-y: auto;">
                        <div class="p-3 text-center text-muted" id="userListLoadingMessage">
                            Memuat daftar pengguna...
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userListContainer = document.getElementById('userListContainer');
    const chatHeader = document.getElementById('chatHeader');
    const chatMessagesContainer = document.getElementById('chatMessagesContainer');
    const chatFooter = document.getElementById('chatFooter');
    const chatMessageInput = document.getElementById('chatMessageInput');
    const backToUserListButton = document.getElementById('backToUserListButton');
    const sendChatMessageButton = document.getElementById('sendChatMessageButton');

    const currentSellerId = {{ Auth::id() }};
    let currentChatUserId = null;
    let currentChatUserName = null;
    let displayedMessageIds = new Set();
    let currentEchoChannel = null; // Untuk melacak channel yang sedang didengarkan

    // Inisialisasi currentChatUserId dan currentChatUserName jika sedang di tampilan chat
    @if($chatWithUser)
        currentChatUserId = {{ $chatWithUser->id }};
        currentChatUserName = @json($chatWithUserName ?? $chatWithUser->name);
    @endif

    console.log('Seller Chat initialized with seller ID:', currentSellerId);

    // Fungsi untuk kembali ke daftar user
    function goBackToUserList() {
        chatHeader.style.display = 'none';
        chatFooter.style.display = 'none';
        // Kosongkan area chat atau tampilkan pesan minimal
        chatMessagesContainer.innerHTML = '';

        document.querySelectorAll('.user-item.active').forEach(item => {
            item.classList.remove('active');
        });

        currentChatUserId = null;
        currentChatUserName = null;

        if (currentEchoChannel && window.Echo) {
            window.Echo.leave(currentEchoChannel);
            console.log(`Left Echo channel: ${currentEchoChannel} on going back to list`);
            currentEchoChannel = null;
        }
    }

    // Load list of users who have chatted with this seller
    async function loadUserList() {
        if (!userListContainer) return;
        try {
            const response = await fetch('/api/chat/users-for-seller', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load users');
            }

            const users = await response.json();

            if (users.length === 0) {
                userListContainer.innerHTML = `
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-info-circle"></i>
                        <p class="mb-0">Belum ada user yang chat dengan Anda.</p>
                    </div>
                `;
                return;
            }

            userListContainer.innerHTML = '';
            users.forEach(user => {
                const userItem = document.createElement('div');
                userItem.className = 'list-group-item list-group-item-action d-flex align-items-center py-3 user-item';
                userItem.dataset.userId = user.id;
                userItem.dataset.userName = user.name;

                userItem.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <span class="text-white fw-bold">${user.name.charAt(0).toUpperCase()}</span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">${user.name}</h6>
                        <small class="text-muted">Klik untuk chat</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                `;

                userItem.addEventListener('click', () => {
                    window.location.href = `{{ route('seller.chat.index') }}?user_id=${user.id}&user_name=${encodeURIComponent(user.name)}`;
                });

                userListContainer.appendChild(userItem);
            });
        } catch (error) {
            console.error('Error loading users:', error);
            userListContainer.innerHTML = `
                <div class="p-3 text-center text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <p class="mb-0">Gagal memuat daftar user.</p>
                </div>
            `;
        }
    }

    function selectUser(userId, userName) {
        currentChatUserId = userId;
        currentChatUserName = userName;

        const chatUserNameElement = document.getElementById('chatUserName');
        if(chatUserNameElement) chatUserNameElement.textContent = `Chat dengan ${userName}`;

        // Update UI
        if (chatHeader) chatHeader.style.display = 'block'; // Tampilkan header
        if (chatFooter) chatFooter.style.display = 'block';

        // Update active state di user list
        document.querySelectorAll('.user-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.userId == userId) {
                item.classList.add('active');
            }
        });

        // Load messages
        if (typeof loadMessages === 'function') {
            loadMessages(userId);
        }

        // Subscribe to the new conversation channel
        if (typeof subscribeToChannel === 'function') {
            subscribeToChannel(userId);
        }

        // Focus on message input
        if (chatMessageInput) chatMessageInput.focus();
    }

    function displayMessage(message, isSentBySeller) {
        if (!chatMessagesContainer) return;

        const messageWrapper = document.createElement('div');
        messageWrapper.classList.add('d-flex', 'mb-3');
        messageWrapper.style.flexDirection = 'column';
        messageWrapper.style.alignItems = isSentBySeller ? 'flex-end' : 'flex-start';

        const messageDiv = document.createElement('div');
        messageDiv.style.padding = '10px 15px';
        messageDiv.style.borderRadius = '15px';
        messageDiv.style.maxWidth = '75%';
        messageDiv.style.wordWrap = 'break-word';
        messageDiv.classList.add('shadow-sm');

        if (isSentBySeller) {
            messageDiv.classList.add('bg-success-subtle');
        } else {
            messageDiv.classList.add('bg-secondary-subtle');
        }

        const senderNameText = isSentBySeller ? 'Anda' : currentChatUserName;
        const senderNameColorClass = isSentBySeller ? 'text-success' : 'text-primary';

        messageDiv.innerHTML = `
            <div style="font-weight: bold; font-size: 0.85em; margin-bottom: 3px;" class="${senderNameColorClass}">${senderNameText}</div>
            <div style="font-size: 0.95em;">${message.message.replace(/\n/g, '<br>')}</div>
            <div style="font-size: 0.75em; margin-top: 4px; text-align: right;" class="text-muted">${new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
        `;
        messageWrapper.appendChild(messageDiv);

        chatMessagesContainer.appendChild(messageWrapper);
        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
    }

    async function loadMessages(userId) {
        if (!chatMessagesContainer) return;

        // Clear previous messages
        chatMessagesContainer.innerHTML = '<p class="text-center text-muted">Memuat pesan...</p>';
        displayedMessageIds.clear();

        try {
            const response = await fetch(`/api/chat/messages/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`Failed to load messages: ${response.statusText}`);
            }

            const messages = await response.json();
            chatMessagesContainer.innerHTML = '';

            if (messages.length === 0) {
                const noMessageP = document.createElement('p');
                noMessageP.classList.add('text-center', 'text-muted', 'py-3');
                noMessageP.textContent = 'Belum ada pesan. Mulai percakapan!';
                chatMessagesContainer.appendChild(noMessageP);
            } else {
                messages.forEach(msg => {
                    if (!displayedMessageIds.has(msg.id)) {
                        displayedMessageIds.add(msg.id);
                        displayMessage(msg, msg.sender_id == currentSellerId);
                    }
                });
            }
        } catch (error) {
            console.error('Error loading messages:', error);
            chatMessagesContainer.innerHTML = `
                <p class="text-center text-danger py-3">Gagal memuat pesan. Coba lagi nanti.</p>
            `;
        }
    }

    async function sendMessage() {
        if (!currentChatUserId) {
            alert('Pilih user terlebih dahulu untuk mengirim pesan.');
            return;
        }

        const messageText = chatMessageInput.value.trim();
        if (messageText === '') return;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/api/chat/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    receiver_id: currentChatUserId,
                    message: messageText
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || errorData.error || 'Gagal mengirim pesan.');
            }

            // Clear input - message will appear via Echo
            chatMessageInput.value = '';
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Terjadi kesalahan saat mengirim pesan: ' + error.message);
        }
    }

    // Event listeners
    if (sendChatMessageButton) {
        sendChatMessageButton.addEventListener('click', sendMessage);
    }

    if (chatMessageInput) {
        chatMessageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    // Event listener untuk tombol kembali
    if (backToUserListButton) {
        backToUserListButton.addEventListener('click', goBackToUserList);
    }

    // Fungsi untuk subscribe ke channel Echo
    function subscribeToChannel(userId) {
        if (window.Echo) {
            // Tinggalkan channel sebelumnya jika ada
            if (currentEchoChannel) {
                window.Echo.leave(currentEchoChannel);
                console.log(`Left Echo channel: ${currentEchoChannel}`);
            }

            const conversationId = generateConversationId(currentSellerId, userId);
            currentEchoChannel = `chat.${conversationId}`;
            console.log(`Subscribing to Echo channel: ${currentEchoChannel}`);

            window.Echo.private(currentEchoChannel)
                .listen('.message.sent', (event) => {
                    console.log('Message received via conversation Echo:', event);

                    if (event.sender_id == currentChatUserId && event.receiver_id == currentSellerId) {
                        if (!displayedMessageIds.has(event.id)) {
                            displayedMessageIds.add(event.id);
                            displayMessage(event, false); // Pesan dari user
                        }
                    } else if (event.sender_id == currentSellerId && event.receiver_id == currentChatUserId) {
                        if (!displayedMessageIds.has(event.id)) {
                            displayedMessageIds.add(event.id);
                            displayMessage(event, true); // Pesan dari seller (diri sendiri)
                        }
                    }
                });
        } else {
            console.error("Laravel Echo tidak terdefinisi.");
        }
    }

    // Fungsi untuk membuat ID percakapan yang konsisten
    function generateConversationId(userId1, userId2) {
        const id1 = parseInt(userId1, 10);
        const id2 = parseInt(userId2, 10);
        return id1 < id2 ? `${id1}-${id2}` : `${id2}-${id1}`;
    }

    // Initialize
    if (userListContainer) {
        loadUserList();
    }
    // Kosongkan area chat awal, akan diisi saat user dipilih atau pesan "Memuat pesan..."
    if (chatMessagesContainer) {
        chatMessagesContainer.innerHTML = '';
    }

    // Jika sedang di tampilan chat, langsung load pesan dan subscribe channel
    @if($chatWithUser)
        if (typeof loadMessages === 'function') {
            loadMessages({{ $chatWithUser->id }});
        }
        if (typeof subscribeToChannel === 'function') {
            subscribeToChannel({{ $chatWithUser->id }});
        }
    @endif

    // Listener global untuk notifikasi (misalnya, untuk user baru yang chat)
    if(window.Echo) {
        window.Echo.private(`App.Models.User.${currentSellerId}`)
            .listen('.message.sent', (event) => {
                console.log('Notification received on personal channel:', event);
                // Cek jika user yang mengirim belum ada di daftar, atau untuk update notif
                const existingUser = document.querySelector(`.user-item[data-user-id="${event.sender_id}"]`);
                if (!existingUser && userListContainer) {
                    console.log('New user detected, reloading user list.');
                    loadUserList();
                } else {
                    // TODO: Tambahkan indikator pesan baru (misal: badge)
                    console.log(`New message from ${event.sender.name}, but user is already in the list.`);
                }
            });
    }
});
</script>
@endpush

@push('styles')
<style>
    .chat-messages-container {
        background-color: #f8f9fa;
    }

    .user-item:hover {
        background-color: #f8f9fa;
    }

    .user-item.active {
        background-color: #0d6efd;
        color: white;
    }

    .user-item.active small {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .user-item.active i {
        color: white !important;
    }

    @media (max-width: 767px) {
        .card-body[style] {
            height: 350px !important;
        }
    }
</style>
@endpush
