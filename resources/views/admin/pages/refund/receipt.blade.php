<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Biên lai hoàn tiền</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        h3 {
            margin: 0;
        }

        .info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="text-align:left;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/clients/img/logo1.png'))) }}"
                alt="Logo" style="max-width:120px;">
        </div>
        <h3 style="text-align:center;">Biên lai hoàn tiền</h3>
        <p style="text-align:center;">Đơn hàng #{{ $refund->order->order_code }}</p>
    </div>
    <div class="info">
        <p><strong>Khách hàng:</strong> {{ optional($refund->order->user)->name ?? 'Khách vãng lai' }}</p>
        <p><strong>Địa chỉ:</strong> {{ optional($refund->order->shippingAddress)->address ?? '-' }},
            {{ optional($refund->order->shippingAddress)->city ?? '-' }}</p>
        <p><strong>Ngày hoàn tiền:</strong> {{ now()->format('d/m/Y') }}</p>
    </div>

    <h4>Sản phẩm</h4>
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($refund->order->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                        @if ($item->variant)
                            <small>({{ $item->variant->size?->name ?? '' }},
                                {{ $item->variant->color?->name ?? '' }})</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Tổng tiền hoàn: {{ number_format($refund->amount ?? $refund->order->total_amount, 0, ',', '.') }}
        ₫</p>

    <div class="footer">
        <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi.</p>
        <p>Mọi thắc mắc xin liên hệ: hotline: 0335.239.319 - Email: support@yourshop.com</p>
    </div>
</body>

</html>
