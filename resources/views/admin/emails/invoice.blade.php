<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn {{ $order->order_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 20px;
        }

        .invoice-container {
            max-width: 700px;
            margin: auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(90deg, #007bff, #00bfff);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .invoice-header .order-code {
            font-size: 16px;
            font-weight: 500;
        }

        .invoice-body {
            padding: 20px;
        }

        .customer-info,
        .shipping-info {
            margin-bottom: 20px;
        }

        .customer-info h5,
        .shipping-info h5 {
            font-weight: 600;
            color: #007bff;
            margin-bottom: 10px;
        }

        .product-card {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-bottom: 10px;
        }

        .product-card img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .product-details {
            flex: 1;
        }

        .product-details p {
            margin: 2px 0;
        }

        .product-price,
        .product-total {
            text-align: right;
            min-width: 80px;
        }

        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
            font-size: 18px;
            font-weight: 700;
        }

        .order-status {
            margin-top: 15px;
            font-size: 16px;
        }

        .badge-status {
            font-size: 0.9rem;
        }

        .invoice-footer {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            font-style: italic;
            color: #555;
        }

        @media(max-width:576px) {
            .product-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .product-price,
            .product-total {
                text-align: left;
                width: 100%;
                margin-top: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>HÓA ĐƠN</h1>
            <div class="order-code">Mã đơn: <strong>{{ $order->order_code }}</strong></div>
        </div>

        <div class="invoice-body">
            <div class="row mb-3">
                <div class="col-md-6 customer-info">
                    <h5>Thông tin khách hàng</h5>
                    <p>{{ $order->user->name ?? $order->shippingAddress->full_name }}</p>
                    <p>Email: {{ $order->user->email ?? 'N/A' }}</p>
                    <p>Điện thoại: {{ $order->shippingAddress->phone ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 shipping-info">
                    <h5>Địa chỉ giao hàng</h5>
                    <p>{{ $order->shippingAddress->address ?? '' }}</p>
                    <p>{{ $order->shippingAddress->ward ?? '' }}, {{ $order->shippingAddress->district ?? '' }}
                        {{ $order->shippingAddress->province ?? '' }}</p>
                </div>
            </div>

            <div class="product-list">
                @foreach ($order->orderItems as $item)
                    <div class="product-card">
                        <div class="product-details">
                            <p><strong>{{ $item->product->name ?? '' }}</strong></p>
                            <p>Biến thể:
                                @if ($item->variant)
                                    @if ($item->variant->color)
                                        {{ $item->variant->color->name }}
                                    @endif
                                    @if ($item->variant->size)
                                        /{{ $item->variant->size->name }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </p>
                            <p>Số lượng: {{ $item->quantity }}</p>
                        </div>
                        <div class="product-price">{{ number_format($item->price, 0, ',', '.') }} đ</div>
                        <div class="product-total">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="total-section">
                Tổng tiền: {{ number_format($order->total_amount, 0, ',', '.') }} đ
            </div>

            <div class="order-status">
                <strong>Trạng thái:</strong>
                @if ($order->status == 'pending')
                    <span class="badge bg-warning text-dark badge-status">Chờ xử lý</span>
                @elseif ($order->status == 'processing')
                    <span class="badge bg-primary badge-status">Đang xử lý</span>
                @elseif ($order->status == 'shipped')
                    <span class="badge bg-info text-dark badge-status">Đang giao</span>
                @elseif ($order->status == 'delivered')
                    <span class="badge bg-success badge-status">Đã giao</span>
                @elseif ($order->status == 'cancelled')
                    <span class="badge bg-danger badge-status">Đã hủy</span>
                @endif
                <br>
                <strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="invoice-footer">
            Cảm ơn quý khách đã đặt hàng!
        </div>
    </div>
</body>

</html>
