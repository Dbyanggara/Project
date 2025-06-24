// Chat functionality
class Chat {
    constructor() {
        this.currentReceiverId = null;
        this.messageContainer = document.querySelector('.chat-messages');
        this.setupEventListeners();
        this.setupEchoListeners();
        this.messageQueue = [];
        this.isSending = false;
        this.checkUrlParameters();
    }

    checkUrlParameters() {
        const urlParams = new URLSearchParams(window.location.search);
        const receiverId = urlParams.get('receiver_id');

        if (receiverId) {
            const sellerButton = document.querySelector(`.select-chat-user[data-user-id="${receiverId}"]`);
            if (sellerButton) {
                sellerButton.click();
            } else {
                this.fetchSellerAndOpenChat(receiverId);
            }
        }
    }

    async fetchSellerAndOpenChat(sellerId) {
        try {
            const response = await axios.get(`/api/chat/seller/${sellerId}`);
            const seller = response.data;
            this.selectReceiver(seller.id, seller.name);
        } catch (error) {
            console.error('Failed to fetch seller details:', error);
            this.showError('Tidak dapat memulai chat dengan penjual yang dipilih');
        }
    }

    setupEventListeners() {
        // Send message when clicking send button
        document.getElementById('sendMessage')?.addEventListener('click', () => this.sendMessage());

        // Send message when pressing Enter in message input
        document.getElementById('messageInput')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });

        // Handle seller selection
        document.querySelectorAll('.select-chat-user').forEach(element => {
            element.addEventListener('click', (e) => {
                const receiverId = e.currentTarget.dataset.userId;
                const receiverName = e.currentTarget.dataset.userName;
                this.selectReceiver(receiverId, receiverName);
            });
        });

        // Handle window focus for marking messages as read
        window.addEventListener('focus', () => {
            if (this.currentReceiverId) {
                this.markMessagesAsRead();
            }
        });
    }

    setupEchoListeners() {
        document.addEventListener('echo:ready', () => {
            if (window.Echo) {
                this.setupMessageListener();
            }
        });
    }

    setupMessageListener() {
        if (this.currentReceiverId) {
            const conversationId = this.generateConversationId(
                document.querySelector('meta[name="user-id"]').content,
                this.currentReceiverId
            );

            window.Echo.private(`chat.${conversationId}`)
                .listen('.message.sent', (e) => {
                    this.handleNewMessage(e);
                })
                .listen('.message.read', (e) => {
                    this.updateMessageStatus(e.message_id);
                });
        }
    }

    generateConversationId(userId1, userId2) {
        // Ensure consistent conversation ID regardless of who initiates
        return parseInt(userId1) < parseInt(userId2)
            ? `${userId1}-${userId2}`
            : `${userId2}-${userId1}`;
    }

    selectReceiver(receiverId, receiverName) {
        if (!receiverId) {
            console.error('No receiver ID provided');
            return;
        }

        // Unsubscribe from previous channel if exists
        if (this.currentReceiverId) {
            const oldConversationId = this.generateConversationId(
                document.querySelector('meta[name="user-id"]').content,
                this.currentReceiverId
            );
            window.Echo.leave(`chat.${oldConversationId}`);
        }

        this.currentReceiverId = receiverId;
        document.querySelector('.chat-header h3').textContent = `Chat with ${receiverName}`;
        document.querySelector('.chat-input-container').style.display = 'flex';

        this.loadMessages();
        this.setupMessageListener();
    }

    async loadMessages() {
        if (!this.currentReceiverId) {
            console.error('No receiver selected');
            return;
        }

        try {
            const response = await axios.get(`/api/chat/messages/${this.currentReceiverId}`);
            this.displayMessages(response.data);
            this.markMessagesAsRead();
        } catch (error) {
            console.error('Failed to load messages:', error);
            if (error.response?.status === 404) {
                this.showError('Tidak dapat memuat pesan: Pengguna tidak ditemukan');
            } else {
                this.showError('Gagal memuat pesan. Silakan coba lagi.');
            }
        }
    }

    displayMessages(messages) {
        if (!this.messageContainer) return;

        this.messageContainer.innerHTML = '';
        const currentUserId = document.querySelector('meta[name="user-id"]').content;

        messages.forEach(message => {
            const isOwn = message.sender_id.toString() === currentUserId;
            const messageElement = this.createMessageElement(message, isOwn);
            this.messageContainer.appendChild(messageElement);
        });

        this.scrollToBottom();
    }

    createMessageElement(message, isOwn) {
        const div = document.createElement('div');
        div.className = `message ${isOwn ? 'message-own' : 'message-other'}`;
        div.dataset.messageId = message.id;

        const content = document.createElement('div');
        content.className = 'message-content';
        content.textContent = message.message;

        const footer = document.createElement('div');
        footer.className = 'message-footer';

        const time = document.createElement('span');
        time.className = 'message-time';
        time.textContent = new Date(message.created_at).toLocaleTimeString();

        const status = document.createElement('span');
        status.className = 'message-status';
        status.innerHTML = this.getStatusIcon(message);

        footer.appendChild(time);
        if (isOwn) {
            footer.appendChild(status);
        }

        div.appendChild(content);
        div.appendChild(footer);

        return div;
    }

    getStatusIcon(message) {
        if (message.read_at) {
            return '<i class="bi bi-check2-all text-primary"></i>';
        } else {
            return '<i class="bi bi-check2"></i>';
        }
    }

    async sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();

        if (!message || !this.currentReceiverId) return;

        // Add message to queue
        this.messageQueue.push({
            receiver_id: this.currentReceiverId,
            message: message
        });

        // Clear input immediately for better UX
        input.value = '';

        // Process queue if not already processing
        if (!this.isSending) {
            this.processMessageQueue();
        }
    }

    async processMessageQueue() {
        if (this.messageQueue.length === 0) {
            this.isSending = false;
            return;
        }

        this.isSending = true;
        const messageData = this.messageQueue.shift();

        try {
            await axios.post('/api/chat/send', messageData);
            await this.loadMessages(); // Reload messages to show the new one
        } catch (error) {
            console.error('Failed to send message:', error);
            this.showError('Gagal mengirim pesan. Silakan coba lagi.');
            // Put the failed message back in the queue
            this.messageQueue.unshift(messageData);
        }

        // Process next message in queue
        this.processMessageQueue();
    }

    handleNewMessage(event) {
        const currentUserId = document.querySelector('meta[name="user-id"]').content;
        const isOwn = event.sender_id.toString() === currentUserId;

        // Add new message to display
        const messageElement = this.createMessageElement(event, isOwn);
        this.messageContainer?.appendChild(messageElement);
        this.scrollToBottom();

        // If window is focused and message is not our own, mark as read
        if (document.hasFocus() && !isOwn) {
            this.markMessagesAsRead();
        }
    }

    async markMessagesAsRead() {
        if (!this.currentReceiverId) return;

        try {
            await axios.post(`/api/chat/mark-as-read/${this.currentReceiverId}`);
        } catch (error) {
            console.error('Failed to mark messages as read:', error);
        }
    }

    updateMessageStatus(messageId) {
        const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageElement) {
            const statusElement = messageElement.querySelector('.message-status');
            if (statusElement) {
                statusElement.innerHTML = '<i class="bi bi-check2-all text-primary"></i>';
            }
        }
    }

    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.textContent = message;

        const container = document.querySelector('.chat-container');
        container.insertBefore(errorDiv, container.firstChild);

        setTimeout(() => errorDiv.remove(), 5000);
    }

    scrollToBottom() {
        if (this.messageContainer) {
            this.messageContainer.scrollTop = this.messageContainer.scrollHeight;
        }
    }
}

// Initialize chat when document is ready
document.addEventListener('DOMContentLoaded', () => {
    new Chat();
});
