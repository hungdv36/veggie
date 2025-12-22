@extends('layouts.client')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Tài khoản')
@section('breadcrumb', 'Tài khoản')

@section('content')
    <style>
        .account-sidebar .list-group-item {
            border: none;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .account-sidebar .list-group-item.active,
        .account-sidebar .list-group-item:hover {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #fff;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .account-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .stat-box {
            border-radius: 12px;
            background: #f9fafb;
            transition: all 0.2s ease;
        }

        .stat-box:hover {
            background: #eef2ff;
            transform: translateY(-2px);
        }

        .modal-content {
            border-radius: 15px;
        }
    </style>

    <div class="py-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="account-sidebar list-group">
                        <a class="list-group-item list-group-item-action active" data-bs-toggle="tab" href="#dashboard">
                            <i class="fas fa-chart-pie me-2"></i> Bảng điều khiển
                        </a>
                        <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#orders">
                            <i class="fas fa-receipt me-2"></i> Đơn hàng
                        </a>
                        <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#address">
                            <i class="fas fa-map-marker-alt me-2"></i> Địa chỉ
                        </a>
                        <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#account">
                            <i class="fas fa-user-cog me-2"></i> Tài khoản
                        </a>
                        <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#password">
                            <i class="fas fa-lock me-2"></i> Đổi mật khẩu
                        </a>
                    </div>
                </div>

                <!-- Content -->
                <div class="col-lg-9">
                    <div class="tab-content">

                        <!-- Dashboard -->
                        <div class="tab-pane fade show active" id="dashboard">
                            <div class="account-card card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <!-- Form upload avatar -->
                                        <form action="{{ route('account.updateAvatar') }}" method="POST"
                                            enctype="multipart/form-data" id="avatarForm" class="d-flex align-items-center">
                                            @csrf

                                            <!-- Ẩn input file -->
                                            <input type="file" name="avatar" id="avatarInput" style="display: none;"
                                                accept="image/*">

                                            <!-- Avatar hiện tại, click vào là chọn file -->
                                            <label for="avatarInput" style="cursor: pointer; margin-right: 1rem;">
                                                <img id="avatarPreview"
                                                    src="{{ $user->avatar ? asset('assets/clients/img/' . $user->avatar) : asset('assets/clients/img/avt.png') }}"
                                                    class="rounded-circle border border-3 border-primary-subtle shadow-sm"
                                                    width="90" height="90" alt="Avatar người dùng">
                                            </label>

                                            <!-- Thông tin user và nút submit -->
                                            <div class="flex-grow-1">
                                                <h5 class="fw-bold mb-1">{{ $user->name ?? 'Người dùng' }}</h5>
                                                <p class="text-muted mb-2">{{ $user->email }}</p>
                                                <button type="submit" class="btn btn-primary btn-sm"
                                                    style="padding: 2px 6px; font-size: 0.8rem;">Cập nhật</button>

                                                <a href="{{ route('logout') }}" class="text-danger small d-block mt-1"><i
                                                        class="fas fa-sign-out-alt me-1"></i> Đăng xuất</a>

                                                @if (session('success'))
                                                    <p class="text-success small mt-1">{{ session('success') }}</p>
                                                @endif
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row text-center mb-4">
                                        <div class="col-md-4 mb-3">
                                            <div class="stat-box py-3 shadow-sm">
                                                <h6 class="text-muted mb-1">Tổng đơn hàng</h6>
                                                <h4 class="fw-bold text-dark">{{ $orders->count() }}</h4>
                                            </div>
                                            <div class="tab-pane fade" id="liton_tab_orders">
                                                <div class="ltn__myaccount-tab-content-inner">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Đơn hàng</th>
                                                                    <th>Ngày</th>
                                                                    <th>Trạng thái</th>
                                                                    <th>Tổng cộng</th>
                                                                    <th>Phương thức thanh toán</th>
                                                                    <th>Thao tác</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($orders as $order)
                                                                    <tr>
                                                                        <td>#{{ $order->id }}</td>
                                                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                                        <td>
                                                                            @php
                                                                                $refundStatus =
                                                                                    $order->payment?->payment_method ===
                                                                                        'momo' &&
                                                                                    $order->status === 'canceled'
                                                                                        ? $order->refund?->status
                                                                                        : $order->status;
                                                                            @endphp

                                                                            @switch($refundStatus)
                                                                                @case('pending')
                                                                                    <span class="badge bg-warning">Chờ xác
                                                                                        nhận</span>
                                                                                @break

                                                                                @case('processing')
                                                                                    <span class="badge bg-primary">Đang xử lý</span>
                                                                                @break

                                                                                @case('shipped')
                                                                                    <span class="badge bg-secondary">Đang giao
                                                                                        hàng</span>
                                                                                @break

                                                                                @case('completed')
                                                                                    <span class="badge bg-success">Hoàn thành</span>
                                                                                @break

                                                                                @case('received')
                                                                                    <span class="badge bg-info">Đã nhận được
                                                                                        hàng</span>
                                                                                @break

                                                                                @case('canceled')
                                                                                    <span class="badge bg-danger">Đã hủy</span>
                                                                                @break

                                                                                @case('waiting_info')
                                                                                    <span class="badge bg-warning">Chờ nhập thông
                                                                                        tin ngân hàng</span>
                                                                                @break

                                                                                @case('submitted')
                                                                                    <span class="badge bg-primary">Đã gửi thông tin
                                                                                        ngân hàng</span>
                                                                                @break

                                                                                @case('in_process')
                                                                                    <span class="badge bg-info">Đang xử lý hoàn
                                                                                        tiền</span>
                                                                                @break

                                                                                @case('refunded')
                                                                                    <span class="badge bg-success">Hoàn tiền thành
                                                                                        công</span>
                                                                                @break

                                                                                @case('failed')
                                                                                    <span class="badge bg-danger">Hoàn tiền thất
                                                                                        bại</span>
                                                                                @break
                                                                            @endswitch
                                                                        </td>
                                                                        <td>{{ number_format($order->total_amount, 0, ',', '.') }}
                                                                            đ</td>
                                                                        <td>
                                                                            <p>
                                                                                @if ($order->payment)
                                                                                    @switch($order->payment->payment_method)
                                                                                        @case('cash')
                                                                                            Thanh toán khi nhận hàng
                                                                                        @break

                                                                                        @case('momo')
                                                                                            Thanh toán quan Momo
                                                                                        @break

                                                                                        @default
                                                                                            {{ ucfirst($order->payment->payment_method) }}
                                                                                    @endswitch
                                                                                @else
                                                                                    Chưa thanh toán
                                                                                @endif
                                                                            </p>
                                                                        </td>
                                                                        <td><a href="{{ route('order.show', $order->id) }}"
                                                                                class="btn btn-sm btn-info">Xem chi tiết</a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="stat-box py-3 shadow-sm">
                                                <h6 class="text-muted mb-1">Địa chỉ đã lưu</h6>
                                                <h4 class="fw-bold text-success">{{ $addresses->count() }}</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-top pt-3">
                                        <h6 class="fw-bold mb-3">Hướng dẫn nhanh</h6>
                                        <ul class="list-unstyled small text-muted">
                                            <li><i class="fas fa-box me-2 text-secondary"></i> <a href="#orders"
                                                    class="text-decoration-underline" data-bs-toggle="tab">Xem đơn hàng gần
                                                    đây</a></li>
                                            <li><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#address"
                                                    class="text-decoration-underline" data-bs-toggle="tab">Quản lý địa chỉ
                                                    giao hàng</a></li>
                                            <li><i class="fas fa-user-edit me-2 text-secondary"></i> <a href="#account"
                                                    class="text-decoration-underline" data-bs-toggle="tab">Cập nhật thông
                                                    tin cá nhân</a></li>
                                            <li><i class="fas fa-key me-2 text-secondary"></i> <a href="#password"
                                                    class="text-decoration-underline" data-bs-toggle="tab">Đổi mật
                                                    khẩu</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders -->
                        <div class="tab-pane fade" id="orders">
                            <div class="account-card card">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3"><i class="fas fa-clipboard-list me-2"></i> Đơn hàng của bạn
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ngày</th>
                                                    <th>Trạng thái</th>
                                                    <th>Tổng cộng</th>
                                                    <th>Phương thức thanh toán</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($orders as $order)
                                                    <tr>
                                                        <td>#{{ $order->id }}</td>
                                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                        @php
                                                            $reasons = [
                                                                'sai_san_pham' => 'Sản phẩm không đúng',
                                                                'hong_hang' => 'Sản phẩm hỏng',
                                                                'khac' => 'Khác',
                                                            ];
                                                        @endphp

                                                        <td>
                                                            @php
                                                                $returned = $order->orderItems
                                                                    ->filter(
                                                                        fn($item) => $item->returnRequest &&
                                                                            $item->returnRequest->status === 'done',
                                                                    )
                                                                    ->count();

                                                                $rejectedItem = $order->orderItems
                                                                    ->filter(
                                                                        fn($item) => $item->returnRequest &&
                                                                            $item->returnRequest->status === 'rejected',
                                                                    )
                                                                    ->first();

                                                                $total = $order->orderItems->count();

                                                                // Trạng thái chính
                                                                $status =
                                                                    ($order->payment?->payment_method === 'momo' ||
                                                                        $order->payment?->payment_method === 'vnpay') &&
                                                                    $order->status === 'canceled'
                                                                        ? trim(
                                                                            $order->refund?->status ?? 'waiting_info',
                                                                        )
                                                                        : $order->status;
                                                            @endphp

                                                            {{-- Trạng thái ưu tiên: Từ chối hoàn hàng --}}
                                                            @if ($rejectedItem)
                                                                <span class="badge bg-danger"
                                                                    title="Lý do: {{ $reasons[$rejectedItem->returnRequest->reject_reason] ?? $rejectedItem->returnRequest->reject_reason }}">
                                                                    <i class="bi bi-x-circle"></i> Từ chối hoàn
                                                                </span>

                                                                {{-- Tất cả sản phẩm đã hoàn --}}
                                                            @elseif($total > 0 && $returned === $total)
                                                                <span class="badge bg-success"
                                                                    title="Tất cả sản phẩm đã hoàn">
                                                                    <i class="bi bi-check-circle"></i> Đã hoàn
                                                                </span>

                                                                {{-- Hoàn một phần --}}
                                                            @elseif($returned > 0)
                                                                <span class="badge bg-info"
                                                                    title="Hoàn {{ $returned }}/{{ $total }} sản phẩm">
                                                                    <i class="bi bi-arrow-repeat"></i> Hoàn 1 phần
                                                                </span>

                                                                {{-- Các trạng thái còn lại --}}
                                                            @else
                                                                @switch($status)
                                                                    @case('pending')
                                                                        <span class="badge bg-warning"><i class="bi bi-clock"></i>
                                                                            Chờ xác nhận</span>
                                                                    @break

                                                                    @case('processing')
                                                                        <span class="badge bg-primary"><i class="bi bi-gear"></i>
                                                                            Đang xử lý</span>
                                                                    @break

                                                                    @case('shipped')
                                                                        <span class="badge bg-secondary"><i
                                                                                class="bi bi-truck"></i> Đang giao</span>
                                                                    @break

                                                                    @case('completed')
                                                                        <span class="badge bg-success"><i
                                                                                class="bi bi-check-circle"></i> Đã giao</span>
                                                                    @break

                                                                    @case('received')
                                                                        <span class="badge bg-info"><i class="bi bi-box-seam"></i>
                                                                            Đã nhận</span>
                                                                    @break

                                                                    @case('canceled')
                                                                        <span class="badge bg-danger"><i
                                                                                class="bi bi-x-circle"></i> Đã hủy</span>
                                                                    @break

                                                                    {{-- Refund --}}
                                                                    @case('waiting_info')
                                                                        <span class="badge bg-warning"><i
                                                                                class="bi bi-clock-history"></i> Chờ nhập thông
                                                                            tin</span>
                                                                    @break

                                                                    @case('submitted')
                                                                        <span class="badge bg-primary"><i class="bi bi-send"></i>
                                                                            Yêu cầu hoàn tiền</span>
                                                                    @break

                                                                    @case('in_process')
                                                                        <span class="badge bg-info"><i
                                                                                class="bi bi-arrow-repeat"></i> Đang xử lý hoàn
                                                                            tiền</span>
                                                                    @break

                                                                    @case('refunded')
                                                                        <span class="badge bg-success"><i
                                                                                class="bi bi-cash-stack"></i> Hoàn tiền thành
                                                                            công</span>
                                                                    @break

                                                                    @case('failed')
                                                                        <span class="badge bg-danger"><i
                                                                                class="bi bi-exclamation-circle"></i> Hoàn tiền
                                                                            thất bại</span>
                                                                    @break

                                                                    @default
                                                                        <span class="badge bg-secondary"><i
                                                                                class="bi bi-question-circle"></i> Không xác
                                                                            định</span>
                                                                @endswitch
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                                        <td>
                                                            <p>
                                                                @if ($order->payment)
                                                                    @switch($order->payment->payment_method)
                                                                        @case('cash')
                                                                            Thanh toán khi nhận hàng
                                                                        @break

                                                                        @case('paypal')
                                                                            Thanh toán qua ví Paypal
                                                                        @break

                                                                        @case('momo')
                                                                            Thanh toán qua ví Momo
                                                                        @break

                                                                        @case('vnpay')
                                                                            Thanh toán qua ví VNPAY
                                                                        @break

                                                                        @default
                                                                            {{ ucfirst($order->payment->payment_method) }}
                                                                    @endswitch
                                                                @else
                                                                    Chưa thanh toán
                                                                @endif
                                                            </p>
                                                        </td>
                                                        <td><a href="{{ route('order.show', $order->id) }}"
                                                                class="btn btn-sm btn-outline-primary"><i
                                                                    class="fas fa-eye"></i></a></td>
                                                    </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">Chưa có đơn hàng
                                                                nào.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="tab-pane fade" id="address">
                                <div class="account-card card">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3"><i class="fas fa-map me-2"></i> Địa chỉ giao hàng</h5>
                                        <p class="text-muted small mb-4">Địa chỉ mặc định sẽ được dùng khi thanh toán.</p>
                                        <div class="table-responsive mb-3">
                                            <table class="table align-middle table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Tên</th>
                                                        <th>Địa chỉ</th>
                                                        <th>Điện thoại</th>
                                                        <th>Mặc định</th>
                                                        <th>Chức năng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($addresses as $address)
                                                        <tr>
                                                            <td>{{ $address->full_name }}</td>
                                                            <td>
                                                                {{ $address->address }}, {{ $address->ward }},
                                                                {{ $address->district }}, {{ $address->province }}
                                                            </td>
                                                            <td>{{ $address->phone }}</td>
                                                            <td>
                                                                @if ($address->default)
                                                                    <span class="badge bg-success">Mặc định</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    @if (!$address->default)
                                                                        <form
                                                                            action="{{ route('account.addresses.update', $address->id) }}"
                                                                            method="POST">
                                                                            @csrf @method('PUT')
                                                                            <button
                                                                                class="btn btn-sm btn-outline-warning">Chọn</button>
                                                                        </form>
                                                                    @endif

                                                                    <form
                                                                        action="{{ route('account.addresses.delete', $address->id) }}"
                                                                        method="POST">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-outline-danger"
                                                                            onclick="return confirm('Xóa địa chỉ này?')">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">Chưa có địa chỉ
                                                                nào.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal">
                                            <i class="fas fa-plus me-1"></i> Thêm địa chỉ mới
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal thêm địa chỉ -->
                            <div class="modal fade" id="addAddressModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">Thêm địa chỉ mới</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="{{ route('account.addresses.add') }}" method="POST">
                                                @csrf

                                                <div class="mb-3">
                                                    <label class="form-label">Tên người nhận</label>
                                                    <input type="text" name="full_name" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Địa chỉ (Số nhà, tên đường)</label>
                                                    <input type="text" name="address" class="form-control" required>
                                                </div>

                                                {{-- TỈNH / THÀNH PHỐ --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Tỉnh / Thành phố</label>
                                                    <select name="province" id="provinceSelect" class="form-control"
                                                        required>
                                                        <option value="">-- Chọn tỉnh / thành phố --</option>
                                                    </select>
                                                </div>

                                                {{-- QUẬN / HUYỆN --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Quận / Huyện</label>
                                                    <select name="district" id="districtSelect" class="form-control" required
                                                        disabled>
                                                        <option value="">-- Chọn quận / huyện --</option>
                                                    </select>
                                                </div>

                                                {{-- PHƯỜNG / XÃ --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Phường / Xã</label>
                                                    <select name="ward" id="wardSelect" class="form-control" required
                                                        disabled>
                                                        <option value="">-- Chọn phường / xã --</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Số điện thoại</label>
                                                    <input type="text" name="phone" class="form-control" required>
                                                </div>

                                                <div class="form-check mb-3">
                                                    <input type="checkbox" name="default" class="form-check-input"
                                                        id="default">
                                                    <label for="default" class="form-check-label">Đặt làm mặc định</label>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">Lưu địa chỉ</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <div class="tab-pane fade" id="password">
                                <div class="account-card card">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3"><i class="fas fa-key me-2"></i> Đổi mật khẩu</h5>
                                        <form action="{{ route('account.change-password') }}" method="POST">
                                            @csrf
                                            <div class="mb-3"><label>Mật khẩu hiện tại</label><input type="password"
                                                    name="current_password" class="form-control" required></div>
                                            <div class="mb-3"><label>Mật khẩu mới</label><input type="password"
                                                    name="new_password" class="form-control" required></div>
                                            <div class="mb-3"><label>Nhập lại mật khẩu mới</label><input type="password"
                                                    name="confirm_password" class="form-control" required></div>
                                            <button type="submit" class="btn btn-primary w-100">Đổi mật khẩu</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Info -->
                            <div class="tab-pane fade" id="account">
                                <div class="account-card card">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3"><i class="fas fa-user-edit me-2"></i> Thông tin cá nhân</h5>
                                        <form action="{{ route('account.update') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="text-center mb-4">
                                                <div class="profile-pic-container">
                                                    <img src="{{ asset('storage/uploads/users/' . $user->avatar) }}"
                                                        alt="Avatar" id="preview-image" class="profile-pic">
                                                    <input type="file" name="avatar" id="avatar" accept="image/*"
                                                        class="d-none">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Họ và tên</label>
                                                    <input type="text" name="ltn_name" id="ltn_name"
                                                        class="form-control" value="{{ $user->name }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Số điện thoại</label>
                                                    <input type="text" name="ltn_phone_number" id="ltn_phone_number"
                                                        class="form-control" value="{{ $user->phone_number }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label>Email</label>
                                                <input type="email" name="ltn_email" id="ltn_email" class="form-control"
                                                    value="{{ $user->email }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label>Địa chỉ</label>
                                                <input type="text" name="ltn_address" id="ltn_address"
                                                    class="form-control" value="{{ $user->address }}" required>
                                            </div>
                                            <div class="btn-wrapper">
                                                <button type="submit" class="btn btn-primary w-100">Cập nhật thông
                                                    tin</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                        </div><!-- /tab-content -->
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const provinceSelect = document.getElementById("provinceSelect");
                const districtSelect = document.getElementById("districtSelect");
                const wardSelect = document.getElementById("wardSelect");

                const modal = document.getElementById('addAddressModal');
                // Chỉ load tỉnh khi modal mở
                modal.addEventListener('shown.bs.modal', loadProvinces);

                // Load TỈNH/THÀNH PHỐ
                function loadProvinces() {
                    if (provinceSelect.options.length > 1) return; // Load 1 lần

                    fetch("https://provinces.open-api.vn/api/p/")
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(p => {
                                let option = document.createElement("option");
                                // Gán TÊN vào value (dành cho Controller)
                                option.value = p.name;
                                // Lưu MÃ (CODE) vào thuộc tính data-code (dành cho fetch)
                                option.dataset.code = p.code;
                                option.textContent = p.name;
                                provinceSelect.appendChild(option);
                            });
                        });
                }

                // Khi chọn TỈNH → Load QUẬN/HUYỆN
                provinceSelect.addEventListener("change", function() {
                    // Lấy MÃ (CODE) của tỉnh đã chọn từ thuộc tính data-code
                    const selectedOption = this.options[this.selectedIndex];
                    const provinceCode = selectedOption.dataset.code;

                    districtSelect.innerHTML = '<option value="">-- Chọn quận / huyện --</option>';
                    wardSelect.innerHTML = '<option value="">-- Chọn phường / xã --</option>';

                    districtSelect.disabled = true;
                    wardSelect.disabled = true;

                    if (!provinceCode) return;

                    fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                        .then(res => res.json())
                        .then(data => {
                            data.districts.forEach(d => {
                                let option = document.createElement("option");
                                // Gán TÊN vào value
                                option.value = d.name;
                                // Lưu MÃ (CODE) vào thuộc tính data-code
                                option.dataset.code = d.code;
                                option.textContent = d.name;
                                districtSelect.appendChild(option);
                            });

                            districtSelect.disabled = false;
                        });
                });

                // Khi chọn HUYỆN → Load XÃ/PHƯỜNG
                districtSelect.addEventListener("change", function() {
                    // Lấy MÃ (CODE) của huyện đã chọn từ thuộc tính data-code
                    const selectedOption = this.options[this.selectedIndex];
                    const districtCode = selectedOption.dataset.code;

                    wardSelect.innerHTML = '<option value="">-- Chọn phường / xã --</option>';
                    wardSelect.disabled = true;

                    if (!districtCode) return;

                    fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                        .then(res => res.json())
                        .then(data => {
                            data.wards.forEach(w => {
                                let option = document.createElement("option");
                                // Gán TÊN vào value (đã đúng)
                                option.value = w.name;
                                option.textContent = w.name;
                                wardSelect.appendChild(option);
                            });

                            wardSelect.disabled = false;
                        });
                });

            });
        </script>
    @endpush
