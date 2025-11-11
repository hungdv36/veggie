<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #198754, #25a36f);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            padding: 40px 35px;
            width: 400px;
            max-width: 95%;
            animation: fadeInUp 0.8s ease;
        }

        .login-card h1 {
            font-weight: 700;
            font-size: 26px;
            color: #198754;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 15px;
        }

        .btn-login {
            background: #198754;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #157347;
            transform: translateY(-2px);
        }

        .login-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .login-logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
            object-fit: cover;
        }

        .footer-text {
            text-align: center;
            font-size: 13px;
            color: #888;
            margin-top: 25px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('assets/clients/img/logo1.png') }}" alt="ClotheStore Logo">
        </div>

        <h1>Đăng nhập Quản trị</h1>

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text" name="email" class="form-control" placeholder="Email"
                    value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng nhập
            </button>
        </form>

        <div class="footer-text">
            <p>© {{ date('Y') }} <strong>ClotheStore Admin</strong>. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
