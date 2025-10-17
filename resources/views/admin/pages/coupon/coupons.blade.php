@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá')

@section('content')
    <div class="right_col" role="main">
        <div class="page-title mb-4 d-flex justify-content-between align-items-center">
            <h3>Quản lý mã giảm giá</h3>
            <a href="{{ route('admin.coupons.add') }}" class="btn btn-success">Thêm mã giảm giá</a>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Danh sách mã giảm giá
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã coupon</th>
                                        <th>Loại</th>
                                        <th>Giá trị</th>
                                        <th>Đơn hàng tối thiểu</th>
                                        <th>Số lần dùng / Giới hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coupons as $coupon)
                                        <tr>
                                            <td>{{ $coupon->id }}</td>
                                            <td>{{ $coupon->code }}</td>
                                            <td>{{ $coupon->type === 'percent' ? '%' : 'VNĐ' }}</td>
                                            <td>{{ number_format($coupon->value, 0, ',', '.') }}
                                                {{ $coupon->type === 'percent' ? '%' : 'VNĐ' }}</td>
                                            <td>{{ number_format($coupon->min_order, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $coupon->used }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                                            <td>
                                                @if ($coupon->status)
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @else
                                                    <span class="badge bg-secondary">Ngưng</span>
                                                @endif
                                            </td>
                                            <td
                                                style="display: flex; gap: 5px; justify-content: center; align-items: center;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalUpdate-{{ $coupon->id }}" title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-delete-coupon"
                                                    data-id="{{ $coupon->id }}" data-code="{{ $coupon->code }}"
                                                    title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modalUpdate-{{ $coupon->id }}" tabindex="-1"
                                            aria-labelledby="couponModalLabel-{{ $coupon->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg"> <!-- full màn hình vừa phải -->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="couponModalLabel-{{ $coupon->id }}">
                                                            Chỉnh sửa mã giảm giá</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.coupons.update', $coupon->id) }}"
                                                            method="POST" id="update-coupon-{{ $coupon->id }}">
                                                            @csrf

                                                            <div class="mb-3">
                                                                <input type="hidden" name="id"
                                                                    value="{{ $coupon->id }}">
                                                                <label class="form-label">Mã giảm giá <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="code" class="form-control"
                                                                    value="{{ $coupon->code }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Loại <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="type" class="form-select" required>
                                                                    <option value="">Chọn loại</option>
                                                                    <option value="percent"
                                                                        {{ $coupon->type == 'percent' ? 'selected' : '' }}>
                                                                        %</option>
                                                                    <option value="amount"
                                                                        {{ $coupon->type == 'amount' ? 'selected' : '' }}>
                                                                        VNĐ</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Giá trị <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" name="value" class="form-control"
                                                                    value="{{ $coupon->value }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Đơn hàng tối thiểu <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" name="min_order" class="form-control"
                                                                    value="{{ $coupon->min_order }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Giới hạn sử dụng</label>
                                                                <input type="number" name="usage_limit"
                                                                    class="form-control"
                                                                    value="{{ $coupon->usage_limit }}">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Số lượt đã dùng</label>
                                                                <input type="number" name="used" class="form-control"
                                                                    value="{{ $coupon->used }}">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Ngày bắt đầu <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="date" name="start_date"
                                                                    class="form-control"
                                                                    value="{{ $coupon->start_date }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Ngày kết thúc <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="date" name="end_date"
                                                                    class="form-control" value="{{ $coupon->end_date }}"
                                                                    required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Trạng thái <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="status" class="form-select" required>
                                                                    <option value="1"
                                                                        {{ $coupon->status ? 'selected' : '' }}>Hiển thị
                                                                    </option>
                                                                    <option value="0"
                                                                        {{ !$coupon->status ? 'selected' : '' }}>Ẩn
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Quay lại</button>
                                                        <button type="button" class="btn btn-primary btn-update-coupon"
                                                            data-id="{{ $coupon->id }}">Chỉnh sửa</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            <form id="form-delete-coupon" method="POST" action="{{ route('admin.coupons.delete') }}"
                                style="display:none;">
                                @csrf
                                <input type="hidden" name="id" id="delete-coupon-id">
                            </form>
                            <div class="mt-3">
                                {{ $coupons->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const updateButtons = document.querySelectorAll('.btn-update-coupon');

            updateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Đổi text và disable nút
                    this.textContent = 'Đang cập nhật...';
                    this.disabled = true;

                    // Submit form tương ứng
                    const couponId = this.dataset.id;
                    const form = document.querySelector('#update-coupon-' + couponId);

                    if (form) {
                        form.submit();
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll('.btn-delete-coupon');
            const form = document.getElementById('form-delete-coupon');
            const inputId = document.getElementById('delete-coupon-id');

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm(`Bạn có chắc muốn xóa mã giảm giá "${this.dataset.code}" không?`)) {
                        inputId.value = this.dataset.id;
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
