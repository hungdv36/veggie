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
        box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }
    .account-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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
                                    <img src="{{ $user->avatar ?? asset('https://cdn.pixabay.com/photo/2020/07/01/12/58/icon-5359553_640.png') }}"
                                        class="rounded-circle me-3 border border-3 border-primary-subtle shadow-sm"
                                        width="90" height="90" alt="Avatar người dùng">
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ $user->name ?? 'Người dùng' }}</h5>
                                        <p class="text-muted mb-1">{{ $user->email }}</p>
                                        <a href="{{ route('logout') }}" class="text-danger small"><i class="fas fa-sign-out-alt me-1"></i> Đăng xuất</a>
                                    </div>
                                </div>

                                <div class="row text-center mb-4">
                                    <div class="col-md-4 mb-3">
                                        <div class="stat-box py-3 shadow-sm">
                                            <h6 class="text-muted mb-1">Tổng đơn hàng</h6>
                                            <h4 class="fw-bold text-dark">{{ $orders->count() }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="stat-box py-3 shadow-sm">
                                            <h6 class="text-muted mb-1">Đơn đang xử lý</h6>
                                            <h4 class="fw-bold text-primary">{{ $orders->where('status', 'processing')->count() }}</h4>
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
                                        <li><i class="fas fa-box me-2 text-secondary"></i> <a href="#orders" class="text-decoration-underline" data-bs-toggle="tab">Xem đơn hàng gần đây</a></li>
                                        <li><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#address" class="text-decoration-underline" data-bs-toggle="tab">Quản lý địa chỉ giao hàng</a></li>
                                        <li><i class="fas fa-user-edit me-2 text-secondary"></i> <a href="#account" class="text-decoration-underline" data-bs-toggle="tab">Cập nhật thông tin cá nhân</a></li>
                                        <li><i class="fas fa-key me-2 text-secondary"></i> <a href="#password" class="text-decoration-underline" data-bs-toggle="tab">Đổi mật khẩu</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders -->
                    <div class="tab-pane fade" id="orders">
                        <div class="account-card card">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3"><i class="fas fa-clipboard-list me-2"></i> Đơn hàng của bạn</h5>
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Ngày</th>
                                                <th>Trạng thái</th>
                                                <th>Tổng cộng</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        @switch($order->status)
                                                            @case('pending') <span class="badge bg-warning">Chờ xác nhận</span> @break
                                                            @case('processing') <span class="badge bg-primary">Đang xử lý</span> @break
                                                            @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                                                            @case('canceled') <span class="badge bg-danger">Đã hủy</span> @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                                    <td><a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center text-muted">Chưa có đơn hàng nào.</td></tr>
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
                                                <th>Tên</th><th>Địa chỉ</th><th>Thành phố</th><th>Điện thoại</th><th>Mặc định</th><th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($addresses as $address)
                                                <tr>
                                                    <td>{{ $address->full_name }}</td>
                                                    <td>{{ $address->address }}</td>
                                                    <td>{{ $address->city }}</td>
                                                    <td>{{ $address->phone }}</td>
                                                    <td>
                                                        @if ($address->default)
                                                            <span class="badge bg-success">Mặc định</span>
                                                        @else
                                                            <form action="{{ route('account.addresses.update', $address->id) }}" method="POST">
                                                                @csrf @method('PUT')
                                                                <button class="btn btn-sm btn-outline-warning">Chọn</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('account.addresses.delete', $address->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa địa chỉ này?')"><i class="fas fa-trash-alt"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="6" class="text-center text-muted">Chưa có địa chỉ nào.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
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
                                            <label class="form-label">Địa chỉ</label>
                                            <input type="text" name="address" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Thành phố</label>
                                            <input type="text" name="city" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Số điện thoại</label>
                                            <input type="text" name="phone" class="form-control" required>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input type="checkbox" name="default" class="form-check-input" id="default">
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
                                    <div class="mb-3"><label>Mật khẩu hiện tại</label><input type="password" name="current_password" class="form-control" required></div>
                                    <div class="mb-3"><label>Mật khẩu mới</label><input type="password" name="new_password" class="form-control" required></div>
                                    <div class="mb-3"><label>Nhập lại mật khẩu mới</label><input type="password" name="confirm_password" class="form-control" required></div>
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
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    @method('PUT') @csrf
                                    <div class="text-center mb-4">
                                        <img src="{{ $user->avatar ?? asset('https://cdn.pixabay.com/photo/2020/07/01/12/58/icon-5359553_640.png') }}"
                                        class="rounded-circle me-3 border border-3 border-primary-subtle shadow-sm"
                                        width="90" height="90" alt="Avatar người dùng">
                                        <input type="file" name="avatar" id="avatar" class="form-control mt-2" accept="image/*">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3"><label>Họ và tên</label><input type="text" name="ltn__name" class="form-control" value="{{ $user->name }}"></div>
                                        <div class="col-md-6 mb-3"><label>Số điện thoại</label><input type="text" name="ltn__phone_number" class="form-control" value="{{ $user->phone_number }}"></div>
                                    </div>
                                    <div class="mb-3"><label>Email</label><input type="email" class="form-control" value="{{ $user->email }}" readonly></div>
                                    <div class="mb-3"><label>Địa chỉ</label><input type="text" name="ltn__adress" class="form-control" value="{{ $user->address }}"></div>
                                    <button type="submit" class="btn btn-primary w-100">Cập nhật thông tin</button>
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
