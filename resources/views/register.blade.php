<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Chatbot AI -  Đăng ký tài khoản người dùng</title>
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
                <div class="card">
                    <div class="card-body">
                        <div class="m-sm-4">
                            <div class="text-center">
                                <h1 class="h3">Chatbot AI - Đăng ký tài khoản</h1>
                            </div>
                            <hr />
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ __($error) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <h6>Đăng ký thành công, vui lòng đăng nhập <a href="{{ env('APP_URL') }}auth/login">tại đây</a></h6>
                                </div>
                            @endif
                            <form action="{{ env('APP_URL') }}auth/register-submit" method="POST" id="RegisterForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="url" value="{{ Request::input('url') }}" placeholder="">
                                <div class="form-group">
                                    <label>Họ tên</label>
                                    <input type="text" class="form-control form-control-lg" name="name" placeholder="Họ tên" value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Địa chỉ Email (Tài khoản đăng nhập)</label>
                                    <input type="email" class="form-control form-control-lg" name="email" placeholder="Địa chỉ Email" value="{{ old('email') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Điện thoại</label>
                                    <input type="tel" class="form-control form-control-lg" name="phone" placeholder="Điện thoại" value="{{ old('phone') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <input type="password" class="form-control form-control-lg" name="password" value="{{ old('password') }}" placeholder="Nhập mật khẩu" required>
                                </div>
                                 <div class="form-group">
                                    <label>Nhập lại mật khẩu</label>
                                    <input type="password" class="form-control form-control-lg" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Nhập lại mật khẩu" required>
                                </div>
                                <div class="text-center mt-3">
                                    <button type="submit" name="submit" value="submit" class="btn btn-lg btn-primary mb-3"><i class="fa-solid fa-user-plus"></i> Đăng ký</button>
                                </div>
                            </form>
                            <small>
                                Nếu đã có tài khoản thì đăng nhập <a href="{{ env('APP_URL') }}auth/login">tại đây?</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
