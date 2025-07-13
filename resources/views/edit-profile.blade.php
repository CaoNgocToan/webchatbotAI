<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Chỉnh sửa thông tin cá nhân - Chatbot AI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ env('APP_URL') }}assets/images/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .card-body {
            padding: 2rem;
        }

        h3 {
            font-weight: 600;
            color: #333;
        }

        label {
            font-weight: 500;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            padding: 10px 24px;
            border-radius: 30px;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .form-control-lg {
            border-radius: 12px;
        }

        .back-link {
            font-size: 14px;
            display: inline-block;
            margin-top: 12px;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3><i class="fa-solid fa-user-gear"></i> Cập nhật thông tin</h3>
                        <p class="text-muted mb-0">Bạn có thể chỉnh sửa thông tin cá nhân tại đây.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ __($error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ env('APP_URL') }}auth/profile-update">
                        @csrf
                        <div class="form-group">
                            <label>👤 Họ tên</label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label>📧 Email</label>
                            <input type="email" name="email" class="form-control form-control-lg"
                                   value="{{ old('email', $user->email) }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>📱 Điện thoại</label>
                            <input type="tel" name="phone" class="form-control form-control-lg"
                                   value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="form-group">
                            <label>🔐 Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control form-control-lg"
                                   placeholder="Để trống nếu không muốn đổi mật khẩu">
                        </div>

                        <div class="form-group">
                            <label>🔐 Nhập lại mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg"
                                   placeholder="Xác nhận mật khẩu mới">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                            </button>
                            <br>
                            <a href="{{ env('APP_URL') }}" class="back-link">← Quay lại Chatbot</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
