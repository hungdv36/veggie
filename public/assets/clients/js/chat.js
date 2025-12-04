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

            // --- Xác định tên người dùng ---
            let username = "bạn";
            if (data.user_name) {
                username = data.user_name; // Nếu đã login thì dùng tên từ server
            } else {
                // Nếu chưa login thì kiểm tra localStorage
                let guestName = localStorage.getItem("guestName");
                if (!guestName) {
                    guestName = prompt("Bạn tên gì?") || "Khách";
                    localStorage.setItem("guestName", guestName);
                }
                username = guestName;
            }

            // --- Kiểm tra có lịch sử chat không ---
            if (data.logs && data.logs.length > 0) {
                // Có lịch sử thì chỉ load lịch sử, KHÔNG hiển thị lời chào
                data.logs.forEach((item) => {
                    chatMessages.innerHTML += `<div class="user-message">${item.message}</div>`;
                    chatMessages.innerHTML += `<div class="bot-message">${item.reply}</div>`;
                });
            } else {
                // Không có lịch sử → hiển thị lời chào 1 lần duy nhất
                chatMessages.innerHTML += `<div class="bot-message">Chào ${username} 👋! Tôi là trợ lý ảo của ClotheStore, bạn cần hỗ trợ gì hôm nay?</div>`;
            }

            chatMessages.scrollTop = chatMessages.scrollHeight;
        } catch (error) {
            console.error("Lỗi fetch lịch sử chat:", error);
            chatMessages.innerHTML += `<div class="bot-message">Xin lỗi, không tải được lịch sử chat.</div>`;
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

const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const box = document.getElementById('chat-messages');
const sessionId = localStorage.getItem('session_id') || (() => {
  const id = Math.random().toString(36).slice(2);
  localStorage.setItem('session_id', id);
  return id;
})();

// Thêm nút vào header
(function addHeaderButtons(){
  const header = document.getElementById('chat-header');
  if (!header) return;
  const actions = document.createElement('div');
  actions.style.cssText = 'display:flex;gap:8px;margin-left:auto';
  actions.innerHTML = `
    <input type="file" id="chat-image" accept="image/*" style="display:none">
    <button id="chat-clear" title="Xóa lịch sử" style="background:#ef4444;color:#fff;border:none;border-radius:6px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;">🗑️</button>
  `;
  header.insertBefore(actions, document.getElementById('chat-close'));
})();
// Xóa lịch sử
document.getElementById('chat-clear')?.addEventListener('click', async () => {
  if (!confirm('Xóa toàn bộ lịch sử chat?')) return;
  await fetch('/delete-history?session_id='+sessionId, { method:'DELETE', headers:{'X-CSRF-TOKEN':csrf} });
  box.innerHTML = '';
});

// Hiển thị bán chạy
document.getElementById('chat-trending')?.addEventListener('click', async () => {
  const res = await fetch('/chat/trending');
  const data = await res.json();
  const items = data.items || [];
  let html = '<div class="trending-grid">';
  items.forEach(p => {
    html += `<div class="trending-card">
      <div class="img"><img src="${p.image_url||''}" style="width:100%;height:auto"></>${p.name}</div><div class="price">${p.price.toLocaleString()} đ</div></div>
    </div>`;
  });
  html += '</div>';
  box.innerHTML += html;
});
  
});
