@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng')

@section('breadcrumb', 'Chi tiết đơn hàng')

@section('content')
    <div class="container my-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-3 text-primary">
                    <i class="fas fa-receipt me-2"></i>Chi tiết đơn hàng #{{ $order->id }}
                </h3>
                <div class="border-bottom mb-3 pb-2">
                    <p class="mb-1"><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>Trạng thái:</strong>
                        @if ($order->status == 'pending')
                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                        @elseif ($order->status == 'processing')
                            <span class="badge bg-info">Đang xử lý</span>
                        @elseif ($order->status == 'completed')
                            <span class="badge bg-success">Hoàn thành</span>
                        @elseif ($order->status == 'canceled')
                            <span class="badge bg-danger">Đã hủy</span>
                        @endif
                    </p>
                    @if ($order->status == 'canceled' && $order->cancel_reason)
                        <p class="mb-1">
                            <strong>Lý do hủy đơn hàng:</strong>
                            <span class="text-danger">{{ $order->cancel_reason }}</span>
                        </p>
                    @endif
                    <p class="mb-1"><strong>Phương thức thanh toán:</strong>
                        @if ($order->payment && $order->payment->payment_method == 'cash')
                            <span class="badge bg-secondary">Thanh toán khi nhận hàng</span>
                        @elseif ($order->payment && $order->payment->payment_method == 'paypal')
                            <span class="badge bg-primary">Thanh toán bằng PayPal</span>
                        @else
                            <span class="badge bg-danger">Chưa xác định</span>
                        @endif
                    </p>
                    <p class="fw-bold fs-5 mt-3 text-success">
                        Tổng tiền: {{ number_format($order->total_amount, 0, ',', '.') }}₫
                    </p>
                </div>

                <!-- Danh sách sản phẩm -->
                <h5 class="fw-bold mb-3"><i class="fas fa-box-open me-2"></i>Sản phẩm trong đơn hàng</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset('assets/admin/img/product/' . $item->product->image) }}"
                                            alt="{{ $item->product->name }}" width="80">
                                    </td>
                                    <td class="fw-medium">{{ $item->product->name }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-success fw-bold">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <!-- Thông tin giao hàng -->
                <div class="mt-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-truck me-2"></i>Thông tin giao hàng</h5>
                    <div class="p-3 bg-light rounded-3">
                        <p class="mb-1"><strong>Tên người nhận:</strong> {{ $order->shippingAddress->full_name }}</p>
                        <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->shippingAddress->address }}</p>
                        <p class="mb-1"><strong>Thành phố:</strong> {{ $order->shippingAddress->city }}</p>
                        <p class="mb-0"><strong>Số điện thoại:</strong> {{ $order->shippingAddress->phone }}</p>
                    </div>
                </div>

                <!-- Hành động -->
                @if ($order->status == 'pending')
                    <form action="{{ route('order.cancel', $order->id) }}" method="POST" class="mt-4"
                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                        @csrf
                        <div class="mb-3">
                            <label for="cancel_reason" class="form-label fw-semibold">Lý do hủy đơn hàng</label>
                            <select name="cancel_reason" id="cancel_reason" class="form-select" required>
                                <option value="">-- Chọn lý do --</option>
                                <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                                <option value="Thay đổi địa chỉ giao hàng">Thay đổi địa chỉ giao hàng</option>
                                <option value="Không còn nhu cầu mua">Không còn nhu cầu mua</option>
                                <option value="Muốn đặt lại đơn mới">Muốn đặt lại đơn mới</option>
                                <option value="Lý do khác">Lý do khác</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-outline-danger px-4 py-2">
                            <i class="fas fa-times-circle me-1"></i> Hủy đơn hàng
                        </button>
                    </form>
                @endif

                <!-- Đánh giá -->
                @if ($order->status == 'completed')
                    <div class="mt-5">
                        <h5 class="fw-bold mb-3"><i class="fas fa-star me-2 text-warning"></i>Đánh giá sản phẩm</h5>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>
                                                <a href="{{ route('product.detail', $item->product->slug) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-pen me-1"></i>Đánh giá
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
