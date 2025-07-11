<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title') | Chatbot - Tư vấn Chuyển đồi số Doanh nghiệp</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Chatbot - Tư vấn Chuyển đồi số Doanh nghiệp" name="description" />
        <meta content="Phan Minh Trung - trungminhphan@gmail.com" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ env('APP_URL') }}assets/admin/images/favicon.ico">
        @section('css') @show
        <!-- App css -->
        <link href="{{ env('APP_URL') }}assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ env('APP_URL') }}assets/admin/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ env('APP_URL') }}assets/admin/css/app.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ env('APP_URL') }}assets/admin/css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        {{-- {{ dd(env('APP_URL')) }} --}}
        <!-- Navigation Bar-->
        <header id="topnav" style="background-color:#015151;">
            <!-- Topbar Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-right mb-0">
                        <li class="dropdown notification-list">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle nav-link">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                        </li>
                        {{-- <li class="d-none d-sm-block">
                            <form class="app-search">
                                <div class="app-search-box">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search...">
                                        <div class="input-group-append">
                                            <button class="btn" type="submit">
                                                <i class="fe-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </li> --}}
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="{{ env('APP_URL') }}assets/admin/images/logo-sm.png" alt="{{ Session::get('user.name') }}" alt="{{ Session::get('user.username') }}" class="rounded-circle">
                                <span class="pro-user-name ml-1">{{ Session::get('user.username') }}<i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>
                                @if(Session::get('user.roles') && in_array('Admin', Session::get('user.roles')))
                                <a href="{{ env('APP_URL') }}admin/user" class="dropdown-item notify-item">
                                    <i class="fe-user"></i> <span>Quản lý tài khoản</span>
                                </a>
                                @endif
                                <a href="{{ env('APP_URL') }}auth/logout" class="dropdown-item notify-item">
                                    <i class="fe-log-out"></i> <span>Đăng xuất</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                    <!-- LOGO -->
                    <div class="logo-box">
                        <a href="{{ env('APP_URL') }}admin" class="logo text-center">
                            <span class="logo-lg">
                                <img src="{{ env('APP_URL') }}assets/admin/images/logo.png" title="Chatbot AI" height="60">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ env('APP_URL') }}assets/admin/images/logo-sm.png" alt="" height="26">
                            </span>
                        </a>
                    </div>
                </div> <!-- end container-fluid-->
            </div>
            <!-- end Topbar -->
            <div class="topbar-menu">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">
                            <li class="has-submenu">
                                <a href="{{ env('APP_URL') }}admin/dashboard"><i class="icon-speedometer"></i>Dashboard</a>
                            </li>
                            <li class="has-submenu">
                                <a href="{{ env('APP_URL') }}admin/messages"><i class="fab fa-rocketchat"></i>Tin nhắn Chat</a>
                            </li>
                            <li class="has-submenu">
                                <a href="{{ env('APP_URL') }}admin/topic"><i class="fa fa-book"></i></i>Chủ đề</a>
                            </li>
                            <li class="has-submenu">
                                <a href="{{ env('APP_URL') }}admin/fine-tuning"><i class="fas fa-book-medical"></i>Tập huấn dữ liệu</a>
                            </li>
                            <li class="has-submenu">
                                <a href="{{ env('APP_URL') }}admin/export-view"><i class="fas fa-file-export"></i> Xuất dữ liệu</a>
                            </li>
                            {{--<li class="has-submenu">
                                <a href="#"><i class="icon-layers"></i> Danh mục <div class="arrow-down"></div></a>
                                <ul class="submenu">
                                    <li><a href="{{ env('APP_URL') }}admin/danh-muc/dia-chi">Địa Chỉ</a></li>
                                    <li><a href="{{ env('APP_URL') }}admin/danh-muc/noi-cap-cmnd">Nơi cấp CMND</a></li>
                                    <li><a href="{{ env('APP_URL') }}admin/danh-muc/noi-cap-cccd">Nơi cấp CCCD</a></li>
                                    <li><a href="{{ env('APP_URL') }}admin/danh-muc/dan-toc">Dân tộc</a></li>
                                    <li><a href="{{ env('APP_URL') }}admin/danh-muc/ton-giao">Tôn giáo</a></li>
                                </ul>
                            </li>
                            
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-file-document-box-search-outline"></i> Tra cứu Hồ sơ</a>
                            </li>
                            <li class="has-submenu">
                                <a href="{{ env('APP_URL') }}admin/thu-ngan"><i class="fas fa-money-check-alt"></i> Thu ngân</a>
                            </li>
                            <li class="has-submenu">
                                <a href="{{ env('APP_URL') }}admin/luu-tru"><i class="fas fa-warehouse"></i> Lưu trữ</a>
                            </li>
                            <li class="has-submenu">
                                <a href="#"> <i class="icon-chart"></i>Thống kê <div class="arrow-down"></div></a>
                                <ul class="submenu">
                                    <li><a href="{{ env('APP_URL') }}admin/thong-ke/theo-hop-dong">Theo Hợp đồng</a></li>
                                    <li><a href="{{ env('APP_URL') }}admin/thong-ke/theo-khach-hang">Theo Khách Hàng</a></li>
                                    <li><a href="{{ env('APP_URL') }}admin/thong-ke/theo-khach-hang">Theo Khách Hàng</a></li>
                                    <li><a href="{{ env('APP_URL') }}admin/thong-ke/bao-cao">Báo cáo Sở Tư Pháp</a></li>
                                    <li><a href="{{ env('APP_URL') }}admin/logs">Logs</a></li>
                                </ul>
                            </li>--}}
                        </ul>
                        <!-- End navigation menu -->

                        <div class="clearfix"></div>
                    </div>
                    <!-- end #navigation -->
                </div>
                <!-- end container -->
            </div>
            <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container-fluid">
                <!-- start page title -->
                @section('body') @show
            </div>
        </div>
        <!-- end wrapper -->
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
          <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-center">
                        &copy; 2024 Chatbot Hỗ trợ tư vấn chuyển đổi số Doanh nghiệp <br />
                        Phát triển bởi: Nhóm nghiên cứu AI <a href="https://cict.agu.edu.vn">Trung tâm Tin học</a> Trường Đại học An Giang
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
        <!-- Vendor js -->
        <script src="{{ env('APP_URL') }}assets/admin/js/vendor.min.js"></script>
        @section('js') @show
        <!-- App js -->
        {{-- <script src="{{ env('APP_URL') }}assets/admin/js/app.min.js"></script> --}}
    </body>
</html>
