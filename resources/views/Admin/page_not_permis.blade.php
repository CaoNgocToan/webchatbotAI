<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>VietGPT-Chat</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="VietGPT Chat" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ env('APP_URL') }}assets/admin/images/favicon.ico">
        <!-- App css -->
        <link href="{{ env('APP_URL') }}assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ env('APP_URL') }}assets/admin/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ env('APP_URL') }}assets/admin/css/app.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ env('APP_URL') }}assets/admin/css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="account-pages">
        <!-- Begin page -->
        <div class="accountbg" style="background: url('{{ env('APP_URL') }}assets/admin/images/bg.png');background-size: cover;"></div>
        <div class="wrapper-page account-page-full">
            <div class="card">
                <div class="card-block">
                    <div class="account-box">
                        <div class="card-box p-5">
                            <h2 class="text-uppercase text-center pb-4">
                                <a href="{{ env('APP_URL') }}" class="text-success">
                                    <span><img src="{{ env('APP_URL') }}assets/images/logo.jpg" class="rounded-circle" alt="" height="200"></span>
                                </a>
                            </h2>
                            <div class="text-center">
                                <h1 class="text-error">500</h1>
                                <h4 class="text-uppercase text-danger mt-3">Không có quyền truy cập</h4>
                                <a class="btn btn-md btn-danger btn-block mt-3" href="{{ env('APP_URL') }}"> Trở về trang chủ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-t-40 text-center">
                <p class="account-copyright">2023 © VietGPT-Chat</p>
            </div>
        </div>

        <!-- jQuery  -->
        <script src="{{ env('APP_URL') }}assets/admin/js/vendor.min.js"></script>
    </body>
</html>
