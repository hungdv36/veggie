<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Hoàn tiền đơn hàng</title>
</head>

<body style="font-family: DejaVu Sans, sans-serif;">

    <h2>Thông báo hoàn tiền đơn hàng #{{ $refund->order->order_code }}</h2>

    <p>Chào {{ $refund->order->user->name }},</p>

    <p>Đơn hàng của bạn đã được xử lý hoàn tiền thành công.</p>

    <p><strong>Số tiền hoàn:</strong> {{ number_format($refund->order->total_amount) }} đ</p>
    <p><strong>Ngày hoàn:</strong> {{ now()->format('d/m/Y') }}</p>

    <p>Biên lai hoàn tiền được đính kèm trong email này dưới dạng file PDF.</p>

    <br>
    <p>Trân trọng,<br>
        Đội ngũ hỗ trợ khách hàng</p>

</body>

</html>
