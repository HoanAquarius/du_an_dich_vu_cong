document.addEventListener("DOMContentLoaded", () => {
  // =========================
  // Tạo giao diện Chatbot
  // =========================

  const chatbot = document.createElement("div");

  chatbot.id = "chatbotWindow";

  chatbot.innerHTML = `
        <div class="chat-header">

            <div>

                <h3>🤖 Trợ lý AI</h3>

                <span>Luôn sẵn sàng hỗ trợ</span>

            </div>

            <div class="close-chat" id="closeChat">
                ✖
            </div>

        </div>

        <div class="chat-body" id="chatBody">

            <div class="message bot">

                <div class="bubble">

                    Xin chào 👋<br><br>

                    Tôi là trợ lý AI của Cổng Dịch vụ Công.<br><br>

                    Tôi có thể hỗ trợ:

                    <br>• Tra cứu thủ tục

                    <br>• Kiểm tra hồ sơ

                    <br>• Sinh Checklist

                    <br>• Hướng dẫn chuẩn bị giấy tờ

                </div>

            </div>

        </div>

        <div class="chat-footer">

            <input
                id="chatInput"
                type="text"
                placeholder="Nhập câu hỏi...">

            <button id="sendBtn">

                <i class="fa-solid fa-paper-plane"></i>

            </button>

        </div>
    `;

  document.body.appendChild(chatbot);

  // =========================
  // Nút mở chatbot
  // =========================

  const openBtn = document.getElementById("openChatbot");

  if (openBtn) {
    openBtn.onclick = () => {
      chatbot.style.display = "flex";
      document.getElementById("chatInput").focus();
    };
  }

  // =========================
  // Nút đóng
  // =========================

  document.addEventListener("click", (e) => {
    if (e.target.id == "closeChat") {
      chatbot.style.display = "none";
    }
  });

  // =========================
  // Gửi bằng nút
  // =========================

  document.addEventListener("click", (e) => {
    if (e.target.id == "sendBtn") {
      sendMessage();
    }
  });

  // =========================
  // Gửi bằng Enter
  // =========================

  document.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      sendMessage();
    }
  });
});

// ===================================
// Hàm gửi tin nhắn
// ===================================

function sendMessage() {
  const input = document.getElementById("chatInput");

  const body = document.getElementById("chatBody");

  let message = input.value.trim();

  if (message === "") return;

  // Tin nhắn người dùng

  body.innerHTML += `
        <div class="message user">

            <div class="bubble">

                ${message}

            </div>

        </div>
    `;

  input.value = "";

  body.scrollTop = body.scrollHeight;

  // Hiệu ứng AI đang trả lời

  body.innerHTML += `
        <div class="message bot typing" id="typing">

            <div class="bubble">

                AI đang trả lời...

            </div>

        </div>
    `;

  body.scrollTop = body.scrollHeight;

  fetch("api/rag_chat.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "message=" + encodeURIComponent(message),
  })
    .then((response) => response.json())
    .then((data) => {
      document.getElementById("typing").remove();

      body.innerHTML += `
        <div class="message bot">
            <div class="bubble">
                ${data.reply.replace(/\n/g, "<br>")}
            </div>
        </div>
    `;

      body.scrollTop = body.scrollHeight;
    })
    .catch((error) => {
      document.getElementById("typing").remove();

      body.innerHTML += `
        <div class="message bot">
            <div class="bubble">
                ❌ Không thể kết nối tới AI.
            </div>
        </div>
    `;
    });
}
