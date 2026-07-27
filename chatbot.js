const chatHistory = [];

function addMessage(body, type, text, className = "") {
  const message = document.createElement("div");
  message.className = `message ${type} ${className}`.trim();

  const bubble = document.createElement("div");
  bubble.className = "bubble";
  bubble.textContent = text;

  message.appendChild(bubble);
  body.appendChild(message);
  body.scrollTop = body.scrollHeight;

  return message;
}

document.addEventListener("DOMContentLoaded", () => {
  const chatbot = document.createElement("div");
  chatbot.id = "chatbotWindow";

  chatbot.innerHTML = `
    <div class="chat-header">
      <div>
        <h3>🤖 Trợ lý Một Cửa AI</h3>
        <span>Hỗ trợ chuẩn bị hồ sơ</span>
      </div>
      <button type="button" class="close-chat" id="closeChat">✕</button>
    </div>

    <div class="chat-body" id="chatBody"></div>

    <div class="chat-footer">
      <input id="chatInput" type="text"
        placeholder="Ví dụ: Tôi muốn mở quán cà phê tại nhà">
      <button type="button" id="sendBtn">
        <i class="fa-solid fa-paper-plane"></i>
      </button>
    </div>
  `;

  document.body.appendChild(chatbot);

  const body = document.getElementById("chatBody");
  const input = document.getElementById("chatInput");

  addMessage(
    body,
    "bot",
    "Xin chào! Tôi sẽ hỗ trợ bạn chuẩn bị hồ sơ. Bạn muốn thực hiện việc gì hôm nay?",
  );

  document.getElementById("openChatbot")?.addEventListener("click", () => {
    chatbot.style.display = "flex";
    input.focus();
  });

  document.getElementById("closeChat").addEventListener("click", () => {
    chatbot.style.display = "none";
  });

  document.getElementById("sendBtn").addEventListener("click", sendMessage);

  input.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      sendMessage();
    }
  });
});

async function sendMessage() {
  const input = document.getElementById("chatInput");
  const body = document.getElementById("chatBody");
  const message = input.value.trim();

  if (!message) return;

  addMessage(body, "user", message);
  input.value = "";
  input.disabled = true;

  chatHistory.push({
    role: "user",
    parts: [{ text: message }],
  });

  const typing = addMessage(body, "bot", "AI đang trả lời...", "typing");

  try {
    const response = await fetch("api/rag_chat.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        message: message,
        history: chatHistory.slice(0, -1),
      }),
    });

    const data = await response.json();
    typing.remove();

    const reply = data.reply || "Chưa nhận được phản hồi. Vui lòng thử lại.";
    addMessage(body, "bot", reply);

    if (response.ok) {
      chatHistory.push({
        role: "model",
        parts: [{ text: reply }],
      });
    }
  } catch (error) {
    typing.remove();
    addMessage(body, "bot", "Không thể kết nối tới AI. Vui lòng thử lại.");
  } finally {
    input.disabled = false;
    input.focus();
  }
}
