document.addEventListener('DOMContentLoaded', function() {
    const chatPopupWidget = document.getElementById('chatPopupWidget');
    const closeChatWidgetButton = document.getElementById('closeChatWidget');

    if (chatPopupWidget && closeChatWidgetButton) {
        // Sembunyikan chat widget saat pertama kali dimuat
        chatPopupWidget.style.display = 'none';

        // Event listener untuk tombol tutup
        closeChatWidgetButton.addEventListener('click', function() {
            chatPopupWidget.style.display = 'none';
        });

        // Event listener untuk tombol chat di card kantin
        document.querySelectorAll('.chat-with-seller-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const sellerId = this.getAttribute('data-seller-id');
                const sellerName = this.getAttribute('data-seller-name');

                if (sellerId && sellerName) {
                    window.location.href = `/user/chat?receiver_id=${sellerId}`;
                }
            });
        });
    }
});
