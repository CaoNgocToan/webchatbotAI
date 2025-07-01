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
<div class="container">
    <div class="row clearfix">
        <div class="col-lg-12 col-12 col-md-12">
            <div class="card chat-app">
                <div class="chat">
                    <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-md-8">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                <img src="{{ env('APP_URL') }}assets/images/logo.jpg" alt="VietGPT-Chat" title="VietGPT-Chat">
                                </a>
                                <div class="chat-about">
                                    <h2 class="m-b-0">Chatbot AI</h2>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="https://cdsdnag.com" target="_blank">
                                    <span style="font-size:15px;color:#00a38b;">&copy; Chatbot - Tư vấn Chuyển đồi số Doanh nghiệp</span><br />
                                </a>
                                <a href="{{ env('APP_URL') }}auth/logout">
                                    <span style="font-size:15px;color:#00504d;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div id="chat-history" class="chat-history">
                        <div class="m-b-0 messages" id="messages">
                            <div class="message other-message"> <i class="fa-solid fa-user"></i> <strong>ChatbotAI</strong> xin chào, Hãy nhập thông tin cần trao đổi phía dưới. </div>
                            {{-- <div class="message my-message">Are we meeting today?</div>
                            <div class="message other-message"> Hi Aiden, how are you? How is the project coming along? </div> --}}
                        </div>
                        {{-- <div class="loading"><img src="{{ env('APP_URL') }}assets/images/loading.svg" /></div> --}}
                    </div>
                    <form id="ChatForm" action="{{ env('APP_URL') }}chat/generate" method="POST">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                    <div class="chat-message clearfix">
                        <div class="input-group mb-0">
                            <input type="text" class="form-control" placeholder="Nhập thông tin cần trao đổi..." id="title" name="title" value="{{ $title }}" autocomplete="off">
                            <div class="input-group-prepend">
                                <button type="submit" name="submit" value="submit" class="btn btn-info"><i class="fa-sharp fa-solid fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ env('APP_URL') }}assets/js/chat.js"></script>
</body>
</html>
