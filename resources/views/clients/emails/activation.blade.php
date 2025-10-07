<!DOCTYPE html>
<html>
<head>
    <title>Kích hoạt tài khoản</title>
</head>
<body>
    <h1>Kích hoạt tài khoản</h1>
    <p>Xin chào {{ $user->name }},</p>
    <p>Cảm ơn bạn đã đăng ký tài khoản. Vui lòng nhấp vào liên kết bên dưới để kích hoạt tài khoản của bạn:</p>
    <a href="{{ url('/activate/'.$token) }}" style="padding: 10px 15px; background-color: #4CAF50; color: white;">Kích hoạt tài khoản</a>
    <p>Trân trọng</p>
    <p>Đội ngũ hỗ trợ khách hàng</p>
</body>
</html>