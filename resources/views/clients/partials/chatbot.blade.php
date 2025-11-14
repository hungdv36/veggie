<div id="chatbot-toggle" style="position:fixed;bottom:20px;right:20px;width:55px;height:55px;border-radius:50%;background:#007bff;color:#fff;font-size:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:9999;box-shadow:0 4px 8px rgba(0,0,0,0.2);">💬</div>
<div id="chatbot-popup" style="display:none;position:fixed;bottom:90px;right:20px;width:400px;height:500px;background:#fff;border-radius:10px;box-shadow:0 4px 8px rgba(0,0,0,0.2);z-index:9999;">
    <div style="background:#007bff;color:#fff;padding:10px;border-radius:10px 10px 0 0;display:flex;justify-content:space-between;">
        <span>AI Chatbot</span>
        <button id="chatbot-close" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;">×</button>
    </div>
    <div id="chat-body" style="padding:10px;height:380px;overflow-y:auto;"></div>
    <div style="padding:10px;border-top:1px solid #ccc;">
        <textarea id="chat-input" style="width:80%;height:40px;"></textarea>
        <button id="send-message" style="width:18%;background:#007bff;color:#fff;border:none;border-radius:5px;">Gửi</button>
    </div>
</div>
