document.addEventListener("DOMContentLoaded", () => {
    const chatIcon = document.getElementById("chatbot-icon");
    const chatBox = document.getElementById("chat-box");
    const chatClose = document.getElementById("chat-close");
    const chatMessages = document.getElementById("chat-messages");
    const sendBtn = document.getElementById("chat-send");
    const input = document.getElementById("userMessage");

    async function loadChatHistory() {
        chatMessages.innerHTML = "";
        try {
            const res = await fetch(window.chatConfig.historyUrl);
            const data = await res.json();

            if (!localStorage.getItem("hasOpenedChat")) {
                const username = data.user_name || "bạn";
                chatMessages.innerHTML += `<div class="bot-message">Chào ${username} 👋! Tôi là trợ lý ảo của ClotheStore, bạn cần hỗ trợ gì hôm nay?</div>`;
                localStorage.setItem("hasOpenedChat", "true");
            }

            if (data.logs && data.logs.length > 0) {
                data.logs.forEach((item) => {
                    chatMessages.innerHTML += `<div class="user-message">${item.message}</div>`;
                    chatMessages.innerHTML += `<div class="bot-message">${item.reply}</div>`;
                });
            }

            chatMessages.scrollTop = chatMessages.scrollHeight;
        } catch (error) {
            console.error("Lỗi fetch lịch sử chat:", error);
        }
    }

    chatIcon.addEventListener("click", () => {
        chatBox.style.display = "flex";
        loadChatHistory();
    });

    chatClose.addEventListener("click", () => (chatBox.style.display = "none"));

    async function sendMessage() {
        const message = input.value.trim();
        if (!message) return;

        // Hiển thị tin nhắn người dùng ngay
        chatMessages.innerHTML += `<div class="user-message">${message}</div>`;
        input.value = "";

        const userId = window.chatConfig.userId;
        let guestName = null;
        if (!userId) {
            guestName = localStorage.getItem("guestName");
            if (!guestName) {
                guestName = prompt("Bạn tên gì?") || "Khách";
                localStorage.setItem("guestName", guestName);
            }
        }

        try {
            const res = await fetch(window.chatConfig.sendUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": window.chatConfig.csrfToken,
                },
                body: JSON.stringify({ message, guest_name: guestName }),
            });

            const text = await res.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error("Server trả về không phải JSON:", text);
                chatMessages.innerHTML += `<div class="bot-message">Xin lỗi, không nhận được phản hồi hợp lệ.</div>`;
                return;
            }

            // Hiển thị reply của bot
            chatMessages.innerHTML += `<div class="bot-message">${data.reply}</div>`;
            chatMessages.scrollTop = chatMessages.scrollHeight;
        } catch (error) {
            console.error(error);
            chatMessages.innerHTML += `<div class="bot-message">Xin lỗi, không gửi được tin nhắn.</div>`;
        }
    }

    sendBtn.addEventListener("click", sendMessage);
    input.addEventListener("keypress", (e) => {
        if (e.key === "Enter") sendMessage();
    });
});
