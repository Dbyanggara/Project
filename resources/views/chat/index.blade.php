<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat dengan {{ $receiver->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; display: flex; flex-direction: column; height: 100vh; }
        .chat-container { display: flex; flex-direction: column; flex-grow: 1; max-width: 600px; margin: auto; border: 1px solid #ccc; }
        .messages { flex-grow: 1; padding: 10px; overflow-y: auto; border-bottom: 1px solid #ccc; }
        .message { margin-bottom: 10px; padding: 8px; border-radius: 5px; max-width: 70%; }
        .message.sent { background-color: #dcf8c6; align-self: flex-end; margin-left: auto; }
        .message.received { background-color: #f1f0f0; align-self: flex-start; margin-right: auto; }
        .message .sender-name { font-weight: bold; font-size: 0.8em; margin-bottom: 3px; }
        .message .content { font-size: 0.9em; }
        .message .timestamp { font-size: 0.7em; color: #888; text-align: right; margin-top: 2px; }
        .input-area { display: flex; padding: 10px; }
        .input-area input { flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .input-area button { padding: 10px 15px; margin-left: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div style="display: flex; height: 100vh;">
        <div style="width: 220px; border-right: 1px solid #ccc; padding: 10px; overflow-y: auto;">
            <h4>Seller</h4>
            <ul id="sellerList"></ul>
            <h4>User</h4>
            <ul id="userList"></ul>
        </div>
        <div class="chat-container" style="flex: 1;">
            <h2>Chat dengan: <span id="receiverName">{{ $receiver->name }}</span></h2>
            <div class="messages" id="messagesContainer"></div>
            <div class="input-area">
                <input type="text" id="messageInput" placeholder="Ketik pesan...">
                <button id="sendMessageBtn">Kirim</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const messageInput = document.getElementById('messageInput');
        const sendMessageBtn = document.getElementById('sendMessageBtn');
        const currentUserId = {{ Auth::id() }};
        const receiverId = {{ $receiver->id }};
        function generateConversationId(userId1, userId2) {
            return userId1 < userId2 ? `${userId1}-${userId2}` : `${userId2}-${userId1}`;
        }
        const conversationId = generateConversationId(currentUserId, receiverId);
        function displayMessage(message, isSentByCurrentUser) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.classList.add(isSentByCurrentUser ? 'sent' : 'received');
            const senderNameDiv = document.createElement('div');
            senderNameDiv.classList.add('sender-name');
            senderNameDiv.textContent = isSentByCurrentUser ? 'Anda' : message.sender.name;
            const contentDiv = document.createElement('div');
            contentDiv.classList.add('content');
            contentDiv.textContent = message.message;
            const timestampDiv = document.createElement('div');
            timestampDiv.classList.add('timestamp');
            timestampDiv.textContent = new Date(message.created_at).toLocaleTimeString();
            messageDiv.appendChild(senderNameDiv);
            messageDiv.appendChild(contentDiv);
            messageDiv.appendChild(timestampDiv);
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        async function fetchMessages() {
            try {
                const response = await fetch(`/api/chat/messages/${receiverId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (!response.ok) throw new Error('Gagal memuat pesan');
                const messages = await response.json();
                messagesContainer.innerHTML = '';
                messages.forEach(msg => {
                    displayMessage(msg, msg.sender_id === currentUserId);
                });
            } catch (error) {
                console.error('Error fetching messages:', error);
                messagesContainer.innerHTML = '<p>Gagal memuat pesan.</p>';
            }
        }
        async function sendMessage() {
            const messageText = messageInput.value.trim();
            if (messageText === '') return;
            try {
                const response = await fetch(`/api/chat/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        receiver_id: receiverId,
                        message: messageText
                    })
                });
                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Gagal mengirim pesan: ' + (errorData.message || JSON.stringify(errorData.errors)));
                    return;
                }
                messageInput.value = '';
            } catch (error) {
                alert('Terjadi kesalahan saat mengirim pesan.');
            }
        }
        sendMessageBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
        if (window.Echo) {
            window.Echo.private(`chat.${conversationId}`)
                .listen('.message.sent', (event) => {
                    if (event.sender_id !== currentUserId) {
                        displayMessage({ ...event, sender: event.sender }, false);
                    } else {
                        displayMessage({ ...event, sender: event.sender }, true);
                    }
                });
        }
        fetchMessages();
        // Sidebar: load sellers and users
        async function loadSidebar() {
            // Sellers
            const sellerList = document.getElementById('sellerList');
            const userList = document.getElementById('userList');
            sellerList.innerHTML = '<li>Loading...</li>';
            userList.innerHTML = '<li>Loading...</li>';
            try {
                const sellers = await fetch('/api/chat/sellers').then(r => r.json());
                sellerList.innerHTML = '';
                sellers.forEach(seller => {
                    const li = document.createElement('li');
                    li.textContent = seller.name;
                    li.style.cursor = 'pointer';
                    li.onclick = () => window.location.href = `/chat/${seller.id}`;
                    if (seller.id == receiverId) li.style.fontWeight = 'bold';
                    sellerList.appendChild(li);
                });
            } catch {}
            try {
                const users = await fetch('/api/chat/users').then(r => r.json());
                userList.innerHTML = '';
                users.forEach(user => {
                    const li = document.createElement('li');
                    li.textContent = user.name;
                    li.style.cursor = 'pointer';
                    li.onclick = () => window.location.href = `/chat/${user.id}`;
                    if (user.id == receiverId) li.style.fontWeight = 'bold';
                    userList.appendChild(li);
                });
            } catch {}
        }
        loadSidebar();
    </script>
</body>
</html>
