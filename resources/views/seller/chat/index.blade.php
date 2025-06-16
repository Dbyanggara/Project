@extends('layouts.app')

@section('title', 'Percakapan Saya - Seller')

@push('styles')
<style>
    .conversation-list-item {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .conversation-list-item:hover {
        background-color: var(--app-secondary-bg);
    }
    .conversation-list-item.active {
        background-color: var(--bs-primary-bg-subtle);
        border-left: 3px solid var(--bs-primary);
    }
    .unread-indicator {
        width: 10px;
        height: 10px;
        background-color: var(--bs-danger);
        border-radius: 50%;
        display: inline-block;
        margin-left: 5px;
    }
    #chatPopup {
        position: fixed;
        bottom: 0;
        right: 20px;
        width: 350px;
        max-height: 450px;
        border: 1px solid var(--app-card-border-color);
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        background-color: var(--app-card-bg);
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        display: none;
        flex-direction: column;
        z-index: 1100;
    }
    #chatHeader {
        padding: 10px 15px;
        background-color: var(--app-secondary-bg);
        border-bottom: 1px solid var(--app-card-border-color);
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 8px 8px 0 0;
    }
    #chatMessages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
    }
    .message-bubble {
        padding: 8px 12px;
        border-radius: 15px;
        margin-bottom: 8px;
        max-width: 75%;
        word-wrap: break-word;
    }
    .message-bubble.sent {
        background-color: var(--bs-primary);
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 5px;
    }
    .message-bubble.received {
        background-color: var(--app-secondary-bg);
        color: var(--app-text-color);
        align-self: flex-start;
        border-bottom-left-radius: 5px;
    }
    #chatInputForm {
        display: flex;
        padding: 10px;
        border-top: 1px solid var(--app-card-border-color);
    }
    #chatInput {
        flex-grow: 1;
        margin-right: 10px;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Percakapan Saya</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                @forelse($conversations as $conversation)
                    <a href="#" class="list-group-item list-group-item-action conversation-list-item"
                       data-conversation-id="{{ $conversation->id }}"
                       data-participant-name="{{ $conversation->customer->name }}">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $conversation->customer->name }}</h6>
                            <small class="text-muted">
                                {{ $conversation->lastMessage ? $conversation->lastMessage->created_at->diffForHumans() : $conversation->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <p class="mb-1 small text-muted text-truncate">
                            @if($conversation->lastMessage)
                                @if($conversation->lastMessage->sender_id == Auth::id())
                                    Anda:
                                @endif
                                {{ Str::limit($conversation->lastMessage->body, 30) }}
                            @else
                                Mulai percakapan...
                            @endif
                        </p>
                    </a>
                @empty
                    <p class="text-muted">Tidak ada percakapan.</p>
                @endforelse
            </div>
            <div class="mt-3">
                {{ $conversations->links() }}
            </div>
        </div>
        <div class="col-md-8">
            <div id="chatWindowPlaceholder" class="card" style="display: none;">
                <div id="chatHeaderPlaceholder" class="card-header">Pilih percakapan</div>
                <div id="chatMessagesPlaceholder" class="card-body" style="height: 400px; overflow-y: auto;"></div>
                <div class="card-footer">
                    <form id="chatInputFormPlaceholder">
                        <div class="input-group">
                            <input type="text" id="chatInputPlaceholder" class="form-control" placeholder="Ketik pesan...">
                            <button class="btn btn-primary" type="submit">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="chatPopup">
    <div id="chatHeader">
        <span id="chatParticipantName">Chat</span>
        <button type="button" class="btn-close btn-sm" id="closeChatPopup"></button>
    </div>
    <div id="chatMessages"></div>
    <form id="chatInputForm">
        <input type="text" id="chatInput" class="form-control" placeholder="Ketik pesan...">
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send"></i></button>
    </form>
</div>
@endsection

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

    function openChatPopup(conversationId, participantName) {
        currentConversationId = conversationId;
        chatHeaderName.textContent = 'Chat dengan ' + participantName;
        chatMessagesContainer.innerHTML = '<p class="text-center text-muted">Memuat pesan...</p>';
        chatPopup.style.display = 'flex';
        chatInput.focus();
        fetchMessages(conversationId);
    }

    if(closeChatPopupButton) {
        closeChatPopupButton.addEventListener('click', () => {
            chatPopup.style.display = 'none';
            currentConversationId = null;
        });
    }

    document.querySelectorAll('.conversation-list-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const convId = this.dataset.conversationId;
            const participantName = this.dataset.participantName;
            document.querySelectorAll('.conversation-list-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            openChatPopup(convId, participantName);
        });
    });

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
        messageDiv.classList.add('message-bubble');
        messageDiv.classList.add(message.sender_id === currentUserId ? 'sent' : 'received');
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
