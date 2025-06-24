<div id="chatPopupWidget" class="chat-popup" style="display: none; position: fixed; bottom: 80px; right: 20px; width: 300px; max-width: 90%; background-color: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000;">
    <div class="chat-popup-header" style="background-color: #007bff; color: white; padding: 10px 15px; border-bottom: 1px solid #0056b3; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between; align-items: center;">
        <h4 style="margin: 0; font-size: 1.1em;">Chat Support</h4>
        <button id="closeChatWidget" type="button" style="background: none; border: none; color: white; font-size: 1.5em; line-height: 1; cursor: pointer; padding: 0;">&times;</button>
    </div>
    <div class="chat-popup-body" style="padding: 15px; height: 250px; overflow-y: auto;">
        <p>Welcome to chat! How can I help you today?</p>
        {{-- Placeholder untuk pesan chat --}}
    </div>
    <div class="chat-popup-footer" style="padding: 10px 15px; border-top: 1px solid #eee; background-color: #f8f9fa;">
        <div style="display: flex;">
            <input type="text" id="chatMessageInput" placeholder="Type your message..." style="flex-grow: 1; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; margin-right: 8px;">
            <button id="sendChatMessage" type="button" style="background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Send</button>
        </div>
    </div>
</div>
