<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Chatbot AI Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ env('APP_URL') }}assets/images/favicon.png">
    <script src="{{ env('APP_URL') }}assets/js/jquery-3.6.3.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ env('APP_URL') }}assets/css/login.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container h-100">
    <div class="row h-100">
        <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
            <div class="d-table-cell align-middle">
                <div class="text-center mt-4">
                    <h1 class="h2">ChatBot AI</h1>
                    <p class="lead">Đăng nhập để thực hiện Chat</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="m-sm-4">
                            <div class="text-center">
                                <img src="{{ env('APP_URL') }}assets/images/logo.jpg" alt="VietGPT-Chat" title="VietGPT-Chat" class="img-fluid rounded-circle" width="132" height="132">
                            </div>
                            
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                        
                            @endif

                            <form action="{{ env('APP_URL') }}auth/login-submit" method="POST" id="LoginForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="url" value="{{ Request::input('url') }}" placeholder="">
                                <div class="form-group">
                                    <label>Địa chỉ Email</label>
                                    <input type="email" class="form-control form-control-lg" name="email" placeholder="Enter your email" required>
                                </div>
                                <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <input type="password" class="form-control form-control-lg" type="password" name="password" placeholder="Enter your password" required>
                                </div>
                                {{-- <div>
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" class="custom-control-input" value="remember-me" name="remember-me" checked="">
                                        <label class="custom-control-label text-small">Remember me next time</label>
                                    </div>
                                </div> --}}
                                <div class="text-center mt-3">
                                    <button type="submit" name="submit" value="submit" class="btn btn-lg btn-primary mb-3"><i class="fa-solid fa-right-to-bracket"></i> Đăng nhập</button>
                                    {{-- <a href="{{ env('APP_URL') }}redirect/google" class="btn btn-lg btn-danger mb-3"><i class="fa-brands fa-google"></i> Đăng nhập với Gmail</a> --}}
                                </div>
                            </form>
                            <small>
                                Nếu chưa có tài khoản thì đăng ký <a href="{{ env('APP_URL') }}auth/register">tại đây?</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
