const chatBody = document.querySelector(".chat-body");
const modelSelect = document.querySelector("#model-select");
const messageInput = document.querySelector(".message-input");
const sendMessageButton = document.querySelector("#send-message");
const fileInput = document.querySelector("#file-input");
const fileUploadWrapper = document.querySelector(".file-upload-wrapper");
const fileCancelButton = document.querySelector("#file-cancel");
const chatbotToggler = document.querySelector("#chatbot-toggler");
const closeChatbot = document.querySelector("#close-chatbot");


// Api setup
// const API_KEY = "AIzaSyDmn51cdS4Q-XTBanYvljOxyEhJU8bmfCs"; // LINK LẤY API KEY: https://aistudio.google.com/apikey
const API_URL = '/chat'; // gọi backend proxy `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${API_KEY}`;

const userData = {
    message: null,
    file: {
        data: null,
        mime_type: null
    }
};

// const chatHistory = [
//     {
//         role: "model",
//         parts: [{ text: `Đinh Duy Vinh (2005), chàng sinh viên đến từ Quảng Ngãi, hiện đang theo học tại Đại học Duy Tân, Đà Nẵng, là một người trẻ đam mê công nghệ và lập trình. Từ thuở nhỏ, Vinh đã có niềm đam mê mãnh liệt với các thiết bị điện tử và luôn muốn tìm hiểu mọi thứ xung quanh. Chính sự tò mò này đã đưa anh đến với thế giới lập trình ngay từ những năm cấp 3, đặc biệt là trong thời gian giãn cách xã hội do dịch COVID-19. Với thời gian rảnh rỗi, Vinh bắt đầu tự học lập trình web, và rồi từ những dự án nhỏ ban đầu, anh đã phát triển được những sản phẩm hữu ích cho cộng đồng.
// Những dự án mà Vinh thực hiện không chỉ đơn giản là những sản phẩm công nghệ mà còn là minh chứng cho sự sáng tạo và khả năng giải quyết vấn đề của anh. Anh đã tự tay xây dựng một loạt các dự án đa dạng như hệ thống quản lý sinh viên, web game giải trí, website chống lừa đảo, trang web tải ảnh từ Imgur, công cụ tạo mã QR code, dự báo thời tiết trực tuyến, và cả extension Chrome giúp đánh giá nhanh giảng viên của trường Đại học Duy Tân. Không dừng lại ở đó, Vinh còn đắm chìm vào việc khai thác API từ các mạng xã hội như Instagram, Facebook, TikTok và Zalo để lấy thông tin người dùng. Anh cũng đã thử sức với việc tạo module iOS để crack ứng dụng Locket, phát triển API tải video từ TikTok, tạo web chuyển đổi 2FA, và không thể không nhắc đến các bot Telegram mà Vinh viết để tự động hóa các tác vụ một cách hiệu quả.
// Vinh không chỉ giỏi trong việc phát triển các dự án công nghệ mà còn luôn mong muốn chia sẻ những gì mình học được với cộng đồng. Kênh YouTube của anh (YouTube: @duyvinh09) là nơi anh chia sẻ những mẹo, thủ thuật và tiện ích cực kỳ hữu ích mà anh đã tự tìm ra, giúp đỡ mọi người trong hành trình học hỏi công nghệ. Ngoài YouTube, Vinh cũng kết nối và chia sẻ kiến thức qua các nền tảng khác như GitHub (GitHub: duyvinh09) và Facebook (Facebook: duyvinh09), nơi anh luôn sẵn sàng giao lưu, học hỏi từ cộng đồng và giúp đỡ những người có chung niềm đam mê. Đặc biệt, Vinh còn sở hữu một nhóm chat trên Telegram, nơi anh và các bạn có thể trao đổi kiến thức, cùng nhau phát triển và học hỏi từ những người đi trước.
// Với một portfolio đầy ấn tượng tại duyvinh09.github.io và dinhduyvinh.eu.org, Vinh không ngừng khẳng định khả năng của mình qua mỗi dự án. Anh là một chàng trai luôn nỗ lực học hỏi, phát triển và sẵn sàng chia sẻ với cộng đồng những gì anh biết. Với tinh thần sáng tạo không ngừng nghỉ và sự nhiệt huyết trong từng dự án, Đinh Duy Vinh chắc chắn sẽ còn đạt được nhiều thành công và tiếp tục là nguồn cảm hứng cho thế hệ trẻ đam mê công nghệ.` }],
//     },
// ];

const chatHistory = [];

const initialInputHeight = messageInput.scrollHeight;

// Create message element with dynamic classes and return it
const createMessageElement = (content, ...classes) => {
    const div = document.createElement("div");
    div.classList.add("message", ...classes);
    div.innerHTML = content;
    return div;
};

// Generate bot response using API
const generateBotResponse = async (incomingMessageDiv) => {
    const messageElement = incomingMessageDiv.querySelector(".message-text");
    
    // chatHistory.push({
    //     role: "user",
    //     parts: [{ text: `Using the details provided above, please address this query: ${userData.message}` }, ...(userData.file.data ? [{ inline_data: userData.file }] : [])],
    // });

    chatHistory.push({
        role: "user",
        parts: [{ text: userData.message }, ...(userData.file.data ? [{ inline_data: userData.file }] : [])],
    });
    
    // API request options
    const requestOptions = {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            contents: chatHistory
        })
    }

    try {
        // Fetch bot response from API
        const response = await fetch(API_URL, requestOptions);
        const data = await response.json();
        if (!response.ok) throw new Error(data.error.message);

        // Extract and display bot's response text
        const apiResponseText = data.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, "$1").trim();
        messageElement.innerText = apiResponseText;
        chatHistory.push({
            role: "model",
            parts: [{ text: apiResponseText }]
        });
    } catch (error) {
        messageElement.innerText = error.message;
        messageElement.style.color = "#ff0000";
    } finally {
        userData.file = {};
        incomingMessageDiv.classList.remove("thinking");
        chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
    }
};

// Handle outgoing user message
const handleOutgoingMessage = (e) => {
    e.preventDefault();
    userData.message = messageInput.value.trim();
    messageInput.value = "";
    fileUploadWrapper.classList.remove("file-uploaded");
    messageInput.dispatchEvent(new Event("input"));

    // Create and display user message
    const messageContent = `<div class="message-text"></div>
                            ${userData.file.data ? `<img src="data:${userData.file.mime_type};base64,${userData.file.data}" class="attachment" />` : ""}`;

    const outgoingMessageDiv = createMessageElement(messageContent, "user-message");
    outgoingMessageDiv.querySelector(".message-text").innerText = userData.message;
    chatBody.appendChild(outgoingMessageDiv);
    chatBody.scrollTop = chatBody.scrollHeight;

    // Simulate bot response with thinking indicator after a delay
    setTimeout(() => {
        const messageContent = `<svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
                    <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
                </svg>
                <div class="message-text">
                    <div class="thinking-indicator">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>`;

        const incomingMessageDiv = createMessageElement(messageContent, "bot-message", "thinking");
        chatBody.appendChild(incomingMessageDiv);
        chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
        generateBotResponse(incomingMessageDiv);
    }, 600);
};

// Handle Enter key press for sending messages
messageInput.addEventListener("keydown", (e) => {
    const userMessage = e.target.value.trim();
    if (e.key === "Enter" && userMessage && !e.shiftKey && window.innerWidth > 768) {
        handleOutgoingMessage(e);
    }
});

messageInput.addEventListener("input", (e) => {
    messageInput.style.height = `${initialInputHeight}px`;
    messageInput.style.height = `${messageInput.scrollHeight}px`;
    document.querySelector(".chat-form").style.boderRadius = messageInput.scrollHeight > initialInputHeight ? "15px" : "32px";
});

// Handle file input change event
fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        fileUploadWrapper.querySelector("img").src = e.target.result;
        fileUploadWrapper.classList.add("file-uploaded");
        const base64String = e.target.result.split(",")[1];

        // Store file data in userData
        userData.file = {
            data: base64String,
            mime_type: file.type
        };
        
        fileInput.value = ""; 
    };

    reader.readAsDataURL(file);
});

fileCancelButton.addEventListener("click", (e) => {
    userData.file = {};
    fileUploadWrapper.classList.remove("file-uploaded");
});

const picker = new EmojiMart.Picker({
    theme: "light",
    showSkinTones: "none",
    previewPosition: "none",
    onEmojiSelect: (emoji) => {
        const { selectionStart: start, selectionEnd: end } = messageInput;
        messageInput.setRangeText(emoji.native, start, end, "end");
        messageInput.focus();
    },
    onClickOutside: (e) => {
        if (e.target.id === "emoji-picker") {
            document.body.classList.toggle("show-emoji-picker");
        } else {
            document.body.classList.remove("show-emoji-picker");
        }
    },
});

document.querySelector(".chat-form").appendChild(picker);

fileInput.addEventListener("change", async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!validImageTypes.includes(file.type)) {
        await Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WEBP)',
            confirmButtonText: 'OK'
        });
        resetFileInput();
        return;
    }
    const reader = new FileReader();
    reader.onload = (e) => {
        fileUploadWrapper.querySelector("img").src = e.target.result;
        fileUploadWrapper.classList.add("file-uploaded");
        const base64String = e.target.result.split(",")[1];
        userData.file = {
            data: base64String,
            mime_type: file.type
        };
    };
    reader.readAsDataURL(file);
});

function resetFileInput() {
    fileInput.value = "";
    fileUploadWrapper.classList.remove("file-uploaded");
    fileUploadWrapper.querySelector("img").src = "#";
    userData.file = { data: null, mime_type: null };
    document.querySelector(".chat-form").reset();
}

sendMessageButton.addEventListener("click", (e) => handleOutgoingMessage(e));
document.querySelector("#file-upload").addEventListener("click", (e) => fileInput.click());
chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
closeChatbot.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
// Dark Mode Toggle
const darkModeToggle = document.querySelector('#dark-mode-toggle');
darkModeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    darkModeToggle.textContent = document.body.classList.contains('dark-mode') ? 'Light Mode' : 'Dark Mode';
});

// Lưu session_id vào localStorage nếu chưa có
if (!localStorage.getItem('session_id')) {
    localStorage.setItem('session_id', Math.random().toString(36).substring(2));
}
const sessionId = localStorage.getItem('session_id');

// Thêm sự kiện cho nút Xem lịch sử
const viewHistoryBtn = document.querySelector('#view-history');
viewHistoryBtn.addEventListener('click', async () => {
    try {
        const response = await fetch('/history?session_id=' + sessionId);
        const data = await response.json();
        if (data.length === 0) {
            alert('Không có lịch sử chat');
            return;
        }
        chatBody.innerHTML = ''; // Xóa nội dung hiện tại
        data.forEach(item => {
            const userDiv = createMessageElement(`<div class="message-text">${item.message}</div>`, 'user-message');
            chatBody.appendChild(userDiv);
            if (item.reply) {
                const botDiv = createMessageElement(`<div class="message-text">${item.reply}</div>`, 'bot-message');
                chatBody.appendChild(botDiv);
            }
        });
    } catch (err) {
        alert('Lỗi tải lịch sử: ' + err.message);
    }
});

// Nút tải lịch sử
const downloadHistoryBtn = document.querySelector('#download-history');
downloadHistoryBtn.addEventListener('click', () => {
    const sessionId = localStorage.getItem('session_id');
    if (!sessionId) {
        alert('Không có session để tải lịch sử');
        return;
    }
    window.location.href = '/download-history?session_id=' + sessionId;
});

// Nút xóa lịch sử
const deleteHistoryBtn = document.querySelector('#delete-history');
deleteHistoryBtn.addEventListener('click', async () => {
    const sessionId = localStorage.getItem('session_id');
    if (!sessionId) {
        alert('Không có session để xóa lịch sử');
        return;
    }
    if (confirm('Bạn có chắc muốn xóa toàn bộ lịch sử chat?')) {
        try {
            const response = await fetch('/delete-history?session_id=' + sessionId, { method: 'DELETE' });
            const result = await response.json();
            alert(result.message);
            chatBody.innerHTML = ''; // Xóa nội dung hiển thị
        } catch (err) {
            alert('Lỗi xóa lịch sử: ' + err.message);
        }
    }
    document.getElementById('chatbot-toggle').addEventListener('click', () => {
    document.getElementById('chatbot-popup').style.display = 'block';
});
document.getElementById('chatbot-close').addEventListener('click', () => {
    document.getElementById('chatbot-popup').style.display = 'none';
});

document.getElementById('send-message').addEventListener('click', async () => {
    const message = document.getElementById('chat-input').value.trim();
    if (!message) return;
    const chatBody = document.getElementById('chat-body');
    chatBody.innerHTML += `<div class="user-message">${message}</div>`;
    document.getElementById('chat-input').value = '';

    const response = await fetch('/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message, model: 'gpt', session_id: localStorage.getItem('session_id') || 'guest' })
    });
    const data = await response.json();
    chatBody.innerHTML += `<div class="bot-message">${data.reply}</div>`;
    chatBody.scrollTop = chatBody.scrollHeight;
});

// ==== Helpers ====
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const box  = document.getElementById('chat-messages') || document.querySelector('.chat-body');
const grid = document.getElementById('chat-trending-grid');

// Session id (giữ nguyên cách bạn đang tạo)
const sessionId = localStorage.getItem('session_id') || (() => {
  const id = Math.random().toString(36).slice(2);
  localStorage.setItem('session_id', id);
  return id;
})();

// ==== 🗑️ Xóa lịch sử ====
document.getElementById('chat-clear')?.addEventListener('click', async () => {
  if (!confirm('Xóa toàn bộ lịch sử chat?')) return;
  try {
    const res = await fetch('/delete-history?session_id='+encodeURIComponent(sessionId), {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': csrf }
    });
    const j = await res.json();
    alert(j.message || 'Đã xóa lịch sử');
    if (box) box.innerHTML = '';
    if (grid) grid.innerHTML = '';
  } catch (e) {
    alert('Lỗi xóa lịch sử: '+e.message);
  }
});

// ==== 📷 Upload ảnh (Vision) ====
document.getElementById('chat-image')?.addEventListener('change', async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  if (!['image/jpeg','image/png','image/gif','image/webp'].includes(file.type)) {
    alert('Chỉ chấp nhận ảnh JPEG/PNG/GIF/WEBP'); e.target.value = ''; return;
  }

  // preview nhỏ
  const reader = new FileReader();
  reader.onload = () => {
    const div = document.createElement('div');
    div.className = 'user-message';
    div.innerHTML = `<div class="message-text">[Ảnh đã tải]</div>${reader.result}`;
    box.appendChild(div); box.scrollTop = box.scrollHeight;
  };
  reader.readAsDataURL(file);

  // gửi lên server vision
  const fd = new FormData();
  fd.append('image', file);
  fd.append('session_id', sessionId);
  fd.append('model', 'gpt'); // hoặc 'gemini'

  try {
    const res = await fetch('/chat/vision', { method:'POST', headers:{'X-CSRF-TOKEN':csrf}, body: fd });
    const data = await res.json();

    // Bot mô tả ảnh
    const div = document.createElement('div');
    div.className = 'bot-message';
    div.innerHTML = `<div class="message-text">${data.reply || 'Không thể phân tích ảnh'}</div>`;
    box.appendChild(div); box.scrollTop = box.scrollHeight;

    // (tuỳ chọn) nếu muốn hiển thị sản phẩm gợi ý sau vision bạn có thể parse và gọi /chat/trending hoặc một API gợi ý khác.
  } catch (err) {
    alert('Lỗi vision: ' + err.message);
  } finally {
    e.target.value = '';
  }
});

// ==== ⭐ Bán chạy ====
document.getElementById('chat-trending')?.addEventListener('click', async () => {
  if (!grid) return;
  grid.innerHTML = '<div style="color:#fff;opacity:.7">Đang tải sản phẩm bán chạy...</div>';
  try {
    const res = await fetch('/chat/trending?days=7&limit=8'); // routes phía server
    const j = await res.json();
    const items = j.items || [];
    if (!items.length) { grid.innerHTML = '<div style="color:#fff;opacity:.7">Chưa có dữ liệu bán chạy.</div>'; return; }

    const dom = document.createElement('div');
    dom.className = 'trending-grid';
    items.forEach(p => {
      const price = (p.price||0).toLocaleString('vi-VN')+' đ';
      const card = document.createElement('div'); card.className='trending-card';
      card.innerHTML = `
        <div class="img">${p.image_url ? `${p.image_url}` : ''}</div>
        <div class="info">
          <div class="name">${p.name||'Sản phẩm'}</div>
          <div class="price">${price}</div>
          <div class="actions">
            ${p.url ? `${p.url}Xem</a>` : ''}
            <button data-id="${p.id}" class="btn add">Thêm vào giỏ</button>
          </div>
        </div>`;
      dom.appendChild(card);
    });
    grid.innerHTML = '';
    grid.appendChild(dom);

    // Thêm vào giỏ (đổi route cho phù hợp site bạn)
    grid.querySelectorAll('.btn.add').forEach(btn => {
      btn.addEventListener('click', async () => {
        try {
          await fetch('/cart/add', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
            body: JSON.stringify({product_id: btn.dataset.id, qty:1})
          });
          btn.textContent = 'Đã thêm'; btn.disabled = true;
        } catch {}
      });
    });
  } catch (e) {
    grid.innerHTML = '<div style="color:#fff;opacity:.7">Lỗi tải bán chạy: '+e.message+'</div>';
  }
});

// ====== Lấy CSRF & Session ======
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const box  = document.getElementById('chat-messages') || document.querySelector('.chat-body');
const grid = document.getElementById('chat-trending-grid');

const sessionId = localStorage.getItem('session_id') || (() => {
  const id = Math.random().toString(36).slice(2);
  localStorage.setItem('session_id', id);
  return id;
})();

// ====== Upload Ảnh (Vision) – nút icon trigger input hidden ======
document.getElementById('chat-image-btn')?.addEventListener('click', () => {
  document.getElementById('chat-image')?.click();
});

document.getElementById('chat-image')?.addEventListener('change', async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  if (!['image/jpeg','image/png','image/gif','image/webp'].includes(file.type)) {
    alert('Chỉ chấp nhận ảnh JPEG/PNG/GIF/WEBP'); e.target.value = ''; return;
  }

  // Preview nhỏ
  const reader = new FileReader();
  reader.onload = () => {
    const div = document.createElement('div');
    div.className = 'user-message';
    div.innerHTML = `<div class="message-text">[Ảnh đã tải]</div>${reader.result}`;
    box.appendChild(div); box.scrollTop = box.scrollHeight;
  };
  reader.readAsDataURL(file);

  // Gửi lên server vision
  const fd = new FormData();
  fd.append('image', file);
  fd.append('session_id', sessionId);
  fd.append('model', 'gpt'); // hoặc 'gemini'

  try {
    const res = await fetch('/chat/vision', { method:'POST', headers:{'X-CSRF-TOKEN':csrf}, body: fd });
    const data = await res.json();

    // ⚠️ Không đẩy "DO NOT mention..." ra UI – chỉ render reply
    const botDiv = document.createElement('div');
    botDiv.className = 'bot-message';
    botDiv.innerHTML = `<div class="message-text">${(data.reply || '').replace(/DO NOT mention.*$/i,'').trim()}</div>`;
    box.appendChild(botDiv); box.scrollTop = box.scrollHeight;
  } catch (err) {
    alert('Lỗi vision: ' + err.message);
  } finally {
    e.target.value = '';
  }
});

// ====== Xóa lịch sử (trash icon) ======
document.getElementById('chat-clear')?.addEventListener('click', async () => {
  if (!confirm('Xóa toàn bộ lịch sử chat?')) return;
  try {
    const res = await fetch('/delete-history?session_id='+encodeURIComponent(sessionId), {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': csrf }
    });
    const j = await res.json();
    alert(j.message || 'Đã xóa lịch sử');
    if (box) box.innerHTML = '';
    if (grid) grid.innerHTML = '';
  } catch (e) {
    alert('Lỗi xóa lịch sử: '+e.message);
  }
});

// ====== Hiển thị Bán chạy ======
document.getElementById('chat-trending')?.addEventListener('click', async () => {
  if (!grid) return;
  grid.innerHTML = '<div style="color:#fff;opacity:.7">Đang tải sản phẩm bán chạy...</div>';
  try {
    const res = await fetch('/chat/trending?days=7&limit=8');
    const j = await res.json();
    const items = j.items || [];
    if (!items.length) { grid.innerHTML = '<div style="color:#fff;opacity:.7">Chưa có dữ liệu bán chạy.</div>'; return; }

    const dom = document.createElement('div');
    dom.className = 'trending-grid';
    items.forEach(p => {
      const price = (p.price||0).toLocaleString('vi-VN')+' đ';
      const card = document.createElement('div'); card.className='trending-card';
      card.innerHTML = `
        <div class="img">${p.image_url ? `<{p.image_url}` : ''}</div>
        <div class="info">
          <div class="name">${p.name||'Sản phẩm'}</div>
          <div class="price">${price}</div>
          <div class="actions">
            ${p.url ? `${p.url}Xem</a>` : ''}
            <button data-id="${p.id}" class="btn add">Thêm vào giỏ</button>
          </div>
        </div>`;
      dom.appendChild(card);
    });
    grid.innerHTML = '';
    grid.appendChild(dom);

    // Thêm vào giỏ – đổi route cho đúng site của bạn nếu khác
    grid.querySelectorAll('.btn.add').forEach(btn => {
      btn.addEventListener('click', async () => {
        try {
          await fetch('/cart/add', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
            body: JSON.stringify({product_id: btn.dataset.id, qty:1})
          });
          btn.textContent = 'Đã thêm'; btn.disabled = true;
        } catch {}
      });
    });
  } catch (e) {
    grid.innerHTML = '<div style="color:#fff;opacity:.7">Lỗi tải bán chạy: '+e.message+'</div>';
  }
});

// ====== TỰ ĐỘNG THÊM NÚT VÀO HEADER ======
(function initChatHeaderActions(){
  const header = document.getElementById('chat-header');
  if (!header || header.dataset.enhanced === '1') return;

  // Khối nút
  const actions = document.createElement('div');
  actions.className = 'chat-actions';
  actions.style.cssText = 'display:flex;gap:8px;align-items:center';

  // Button: Upload ảnh (camera)
  const btnImage = document.createElement('button');
  btnImage.id = 'chat-image-btn';
  btnImage.className = 'icon-btn';
  btnImage.title = 'Tải ảnh lên';
  btnImage.innerHTML = `<svg viewBox="0 0 24 24" class="icon"><path d="M9.5 4h5l1.2 2H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h3.3L9.5 4zm2.5 12a4 4 0 100-8 4 4 0 000 8z"/></svg>`;

  // Input file ẩn
  const inputFile = document.createElement('input');
  inputFile.type = 'file';
  inputFile.id = 'chat-image';
  inputFile.accept = 'image/*';
  inputFile.style.display = 'none';

  // Button: Xóa lịch sử (trash)
  const btnClear = document.createElement('button');
  btnClear.id = 'chat-clear';
  btnClear.className = 'icon-btn danger';
  btnClear.title = 'Xóa lịch sử';
  btnClear.innerHTML = `<svg viewBox="0 0 24 24" class="icon"><path d="M9 3h6l1 2h4v2H4V5h4l1-2zm1 6h2v8H10V9zm4 0h2v8h-2V9z"/></svg>`;

  // Button: Bán chạy (chart)
  const btnTrending = document.createElement('button');
  btnTrending.id = 'chat-trending';
  btnTrending.className = 'icon-btn primary';
  btnTrending.title = 'Sản phẩm bán chạy';
  btnTrending.innerHTML = `<svg viewBox="0 0 24 24" class="icon"><path d="M3 13h3v8H3v-8zm5-6h3v14H8V7zm5 3h3v11h-3V10zm5-8h3v19h-3V2z"/></svg>`;

  // Thêm vào header (ngay trước nút đóng)
  const closeBtn = document.getElementById('chat-close');
  header.insertBefore(actions, closeBtn);
  actions.appendChild(btnImage);
  actions.appendChild(inputFile);
  actions.appendChild(btnClear);
  actions.appendChild(btnTrending);

  header.dataset.enhanced = '1';
})();

// ====== STYLE NHẸ CHO ICON ======
(function injectChatCss(){
  const css = `
    .icon-btn{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:none;cursor:pointer;background:#1f2937;color:#fff}
    .icon-btn:hover{background:#374151}
    .icon-btn.primary{background:#2563eb}.icon-btn.primary:hover{background:#1d4ed8}
    .icon-btn.danger{background:#ef4444}.icon-btn.danger:hover{background:#dc2626}
    .icon{width:18px;height:18px}
    #chat-trending-grid{margin-top:10px}
    .trending-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
    .trending-card{background:#fff;color:#111;border:1px solid #eee;border-radius:10px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,.06)}
    .trending-card .img{width:100%;aspect-ratio:1/1;background:#f8f8f8;display:flex;align-items:center;justify-content:center}
    .trending-card .info{padding:8px}
    .trending-card .name{font-weight:600;font-size:.92rem}
    .trending-card .price{color:#2563eb;margin-top:6px}
    .trending-card .actions{display:flex;gap:8px;margin-top:8px}
    .trending-card .btn{flex:1;padding:6px;border:none;border-radius:8px;cursor:pointer;font-size:.85rem}
    .trending-card .btn.view{background:#7c3aed;color:#fff}
    .trending-card .btn.add{background:#10b981;color:#fff}
  `;
  const style = document.createElement('style');
  style.textContent = css;
  document.head.appendChild(style);
})();

// ====== CSRF & Session ======
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const box  = document.getElementById('chat-messages');
const sessionId = localStorage.getItem('session_id') || (() => {
  const id = Math.random().toString(36).slice(2);
  localStorage.setItem('session_id', id);
  return id;
})();

// ====== UPLOAD ẢNH (VISION) ======
document.getElementById('chat-image-btn')?.addEventListener('click', () => {
  document.getElementById('chat-image')?.click();
});

document.getElementById('chat-image')?.addEventListener('change', async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const types = ['image/jpeg','image/png','image/gif','image/webp'];
  if (!types.includes(file.type)) { alert('Chỉ chấp nhận ảnh JPEG/PNG/GIF/WEBP'); e.target.value = ''; return; }

  // Preview nhỏ
  const reader = new FileReader();
  reader.onload = () => {
    const div = document.createElement('div');
    div.className = 'user-message';
    div.innerHTML = `<div class="message-text">[Ảnh đã tải]</div>${reader.result}`;
    box.appendChild(div); box.scrollTop = box.scrollHeight;
  };
  reader.readAsDataURL(file);

  // Gửi lên server vision
  const fd = new FormData();
  fd.append('image', file);
  fd.append('session_id', sessionId);
  fd.append('model', 'gpt'); // hoặc 'gemini'
  try {
    const res = await fetch('/chat/vision', { method:'POST', headers:{'X-CSRF-TOKEN':csrf}, body: fd });
    const data = await res.json();

    // KHÔNG hiển thị rule nội bộ; chỉ render reply đã được lọc
    const botDiv = document.createElement('div');
    botDiv.className = 'bot-message';
    botDiv.innerHTML = `<div class="message-text">${(data.reply || '').replace(/DO NOT mention.*$/i,'').trim()}</div>`;
    box.appendChild(botDiv); box.scrollTop = box.scrollHeight;
  } catch (err) {
    alert('Lỗi vision: ' + err.message);
  } finally {
    e.target.value = '';
  }
});

// ====== XÓA LỊCH SỬ ======
document.getElementById('chat-clear')?.addEventListener('click', async () => {
  if (!confirm('Xóa toàn bộ lịch sử chat?')) return;
  try {
    const res = await fetch('/delete-history?session_id='+encodeURIComponent(sessionId), {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': csrf }
    });
    const j = await res.json();
    alert(j.message || 'Đã xóa lịch sử');
    box.innerHTML = '';
    const grid = document.getElementById('chat-trending-grid');
    if (grid) grid.innerHTML = '';
  } catch (e) {
    alert('Lỗi xóa lịch sử: ' + e.message);
  }
});

// ====== BÁN CHẠY ======
document.getElementById('chat-trending')?.addEventListener('click', async () => {
  let grid = document.getElementById('chat-trending-grid');
  if (!grid) {
    grid = document.createElement('div');
    grid.id = 'chat-trending-grid';
    const inputArea = document.getElementById('chat-input');
    inputArea.appendChild(grid);
  }
  grid.innerHTML = '<div style="color:#fff;opacity:.7">Đang tải sản phẩm bán chạy...</div>';
  try {
    const res = await fetch('/chat/trending?days=7&limit=8');
    const j = await res.json();
    const items = j.items || [];
    if (!items.length) { grid.innerHTML = '<div style="color:#fff;opacity:.7">Chưa có dữ liệu bán chạy.</div>'; return; }

    const dom = document.createElement('div');
    dom.className = 'trending-grid';
    items.forEach(p => {
      const price = (p.price||0).toLocaleString('vi-VN')+' đ';
      const card = document.createElement('div'); card.className='trending-card';
      card.innerHTML = `
        <div class="img">${p.image_url ? `${p.image_url}` : ''}</div>
        <div class="info">
          <div class="name">${p.name||'Sản phẩm'}</div>
          <div class="price">${price}</div>
          <div class="actions">
            ${p.url ? `${p.url}Xem</a>` : ''}
            <button data-id="${p.id}" class="btn add">Thêm vào giỏ</button>
          </div>
        </div>`;
      dom.appendChild(card);
    });
    grid.innerHTML = '';
    grid.appendChild(dom);

    // Thêm vào giỏ – tùy chỉnh route nếu site bạn khác
    grid.querySelectorAll('.btn.add').forEach(btn => {
      btn.addEventListener('click', async () => {
        try {
          await fetch('/cart/add', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
            body: JSON.stringify({product_id: btn.dataset.id, qty:1})
          });
          btn.textContent = 'Đã thêm'; btn.disabled = true;
        } catch {}
      });
    });
  } catch (e) {
    grid.innerHTML = '<div style="color:#fff;opacity:.7">Lỗi tải bán chạy: '+e.message+'</div>';
  }
});

});
