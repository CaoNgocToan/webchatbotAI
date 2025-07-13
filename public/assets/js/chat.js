function handleSubmit(e) {
    e.preventDefault(); // Chặn reload trang
    sendMessage();      // Gọi hàm gửi tin nhắn
}

function handleEnter(e) {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

async function sendMessage() {
    const input = document.getElementById("user-input");
    const text = input.value.trim();
    if (!text) return;

    addMessage(text, 'user');
    input.value = "";

    const container = document.getElementById("chat-container");
    const typingMsg = document.createElement("div");
    typingMsg.className = "message bot";
    const bubble = document.createElement("div");
    bubble.className = "bubble";
    bubble.textContent = ".";

    const timestamp = document.createElement("div");
    timestamp.className = "timestamp";
    timestamp.textContent = getCurrentTime();

    typingMsg.appendChild(bubble);
    typingMsg.appendChild(timestamp);
    container.appendChild(typingMsg);
    container.scrollTop = container.scrollHeight;

    let dots = 1;
    const typingInterval = setInterval(() => {
        dots = (dots % 3) + 1;
        bubble.textContent = ".".repeat(dots);
        container.scrollTop = container.scrollHeight;
    }, 400);

    try {
        const ChatForm = document.getElementById("ChatForm");
        const href = ChatForm.getAttribute("action");

        const response = await fetch(href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.getElementById('_token').value
            },
            body: JSON.stringify({ title: text })
        });

        const data = await response.text();
        clearInterval(typingInterval);
        typingMsg.remove();

        const reply = data || "🤖 Xin lỗi, tôi không hiểu.";
        addMessage(reply, 'bot', true);

    } catch (error) {
        clearInterval(typingInterval);
        typingMsg.remove();
        addMessage("⚠️ Đã xảy ra lỗi khi kết nối máy chủ.", 'bot');
    }
}


function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
}

function addMessage(text, sender = 'bot', typing = false) {
    const container = document.getElementById("chat-container");
    const message = document.createElement("div");
    message.className = `message ${sender}`;

    const bubble = document.createElement("div");
    bubble.className = "bubble";
    bubble.innerHTML = sender === 'user' ? text : "";

    const timestamp = document.createElement("div");
    timestamp.className = "timestamp";
    timestamp.textContent = getCurrentTime();

    message.appendChild(bubble);
    message.appendChild(timestamp);
    container.appendChild(message);
    container.scrollTop = container.scrollHeight;

    if (sender === 'bot' && typing) {
        typeEffect(bubble, text, container, 15, timestamp);
    }
}

function typeEffect(el, text, container, speed = 15, timestampEl = null) {
    let i = 0;
    el.innerHTML = "";
    function type() {
        if (i < text.length) {
            const char = text[i] === "\n" ? "<br>" : text[i];
            el.innerHTML += char;
            i++;
            container.scrollTop = container.scrollHeight;
            setTimeout(type, speed);
        } else if (timestampEl) {
            timestampEl.textContent = getCurrentTime();
        }
    }
    type();
}

function toggleDropdown() {
    const menu = document.getElementById("dropdown-menu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

window.addEventListener("click", function (e) {
    const avatar = document.querySelector('.user-avatar');
    if (!avatar.contains(e.target)) {
        document.getElementById("dropdown-menu").style.display = "none";
    }
});