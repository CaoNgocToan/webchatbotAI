<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Chatbot AI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ env('APP_URL') }}assets/images/favicon.png">
    <script src="{{ env('APP_URL') }}assets/js/jquery-3.6.3.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="{{ env('APP_URL') }}assets/css/chat.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
<div class="chat">
    <div class="header">
        <div class="logo">🤖 ChatBot</div>
        <div class="user-menu-wrapper">
            <div class="user-avatar" onclick="toggleDropdown()">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="{{ env('APP_URL') }}auth/profile-edit">👤 Quản lý tài khoản</a>
                <a href="{{ env('APP_URL') }}auth/logout">🚪 Đăng xuất</a>
            </div>
        </div>

    </div>
    </div>
    <div id="chat-container" class="chat-container">
    <div class="message bot">
        <div class="bubble">🤖 Xin chào! Tôi có thể giúp gì cho bạn hôm nay?</div>
        <div class="timestamp">07:53</div>
    </div>
</div>


    <div class="input-area">
    <form id="ChatForm" action="{{ env('APP_URL') }}chat/generate" method="POST" onsubmit="handleSubmit(event)" style="display: flex; width: 100%;">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
        
        <textarea
            id="user-input"
            name="title"
            autocomplete="off"
            placeholder="Nhập tin nhắn..."
            onkeydown="handleEnter(event)"
        >{{ $title }}</textarea>

        <button type="submit" name="submit" value="submit" title="Gửi">
            <i class="fa-solid fa-paper-plane"></i> Gửi
        </button>
    </form>
</div>


</div>
<footer class="chat-footer">
    <a href="https://cdsdnag.com" target="_blank">
        &copy; Chatbot - Tư vấn Chuyển đổi số Doanh nghiệp
    </a>
</footer>




<script type="text/javascript" src="{{ env('APP_URL') }}assets/js/chat.js"></script>

</body>
</html>
