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

    // Tạo tin nhắn đang gõ của bot
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

    // Hiệu ứng typing "..."
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

        const data = await response.json();
        clearInterval(typingInterval);
        typingMsg.remove();

        const reply = data.text || "🤖 Xin lỗi, tôi không hiểu.";
        const source = data.source || null;

        addMessage(reply, 'bot', false, source);

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

function addMessage(text, sender = 'bot', typing = false, source = null) {
    const container = document.getElementById("chat-container");

    const message = document.createElement("div");
    message.className = `message ${sender}`;

    const bubble = document.createElement("div");
    bubble.className = "bubble";

    const timestamp = document.createElement("div");
    timestamp.className = "timestamp";
    timestamp.textContent = getCurrentTime();

    message.appendChild(bubble);
    message.appendChild(timestamp);
    container.appendChild(message);
    container.scrollTop = container.scrollHeight;

    if (sender === 'bot' && typing) {
        // Gõ từng chữ
        typeEffect(bubble, text, container, 15, () => {
            // Gõ xong thì thêm dòng nguồn (nếu có)
            if (source) {
                const filename = source.split('/').pop();
                const link = document.createElement("a");
                link.href = source;
                link.target = "_blank";
                link.className = "source-link";
                link.textContent = `📄 Nguồn: ${filename}`;

                const sourceDiv = document.createElement("div");
                sourceDiv.className = "source-wrapper";
                sourceDiv.appendChild(link);



                bubble.appendChild(sourceDiv);
                container.scrollTop = container.scrollHeight;
            }
        });
    } else {
        // Nếu không typing thì hiển thị ngay
        bubble.innerHTML = text;
        if (source) {
            const filename = source.split('/').pop();
            bubble.innerHTML += `<br><br><a href="${source}" target="_blank" class="source-link">📄 Nguồn: ${filename}</a>`;
        }
    }
}






function typeEffect(element, text, container, speed = 5, onDone = null) {
    let i = 0;
    element.textContent = "";

    const interval = setInterval(() => {
        element.textContent += text.charAt(i);
        i++;
        container.scrollTop = container.scrollHeight;

        if (i >= text.length) {
            clearInterval(interval);
            if (typeof onDone === 'function') onDone();
        }
    }, speed);
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