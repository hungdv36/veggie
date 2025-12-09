@extends('layouts.admin')
@section('title', isset($coupon) ? 'Chỉnh sửa mã giảm giá' : 'Thêm mã giảm giá')

@section('content')
    <div class="right_col" role="main">
        <div class="page-title mb-4">
            <div class="title_left">
                <h3>{{ isset($coupon) ? 'Chỉnh sửa mã giảm giá' : 'Thêm mã giảm giá' }}</h3>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form
                            action="{{ isset($coupon) ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}"
                            method="POST">
                            @csrf
                            @if (isset($coupon))
                                @method('PUT')
                            @endif

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control"
                                        value="{{ old('code', $coupon->code ?? '') }}" required>
                                    @error('code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Loại <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="" hidden>Chọn loại</option>
                                        <option value="percent"
                                            {{ old('type', $coupon->type ?? '') == 'percent' ? 'selected' : '' }}>%</option>
                                        <option value="amount"
                                            {{ old('type', $coupon->type ?? '') == 'amount' ? 'selected' : '' }}>VNĐ
                                        </option>
                                    </select>
                                    @error('type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Giá trị <span class="text-danger">*</span></label>
                                    <input type="number" name="value" class="form-control" min="0"
                                        value="{{ old('value', $coupon->value ?? '') }}" required>
                                    @error('value')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Đơn hàng tối thiểu</label>
                                    <input type="number" name="min_order" class="form-control" min="0"
                                        value="{{ old('min_order', $coupon->min_order ?? '') }}">
                                    @error('min_order')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Giới hạn sử dụng</label>
                                    <input type="number" name="usage_limit" class="form-control" min="0"
                                        value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}">
                                    @error('usage_limit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-select">
                                        <option value="1"
                                            {{ old('status', $coupon->status ?? 1) == 1 ? 'selected' : '' }}>Hoạt động
                                        </option>
                                        <option value="0"
                                            {{ old('status', $coupon->status ?? 1) == 0 ? 'selected' : '' }}>Ngưng</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ngày bắt đầu</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date', isset($coupon) ? $coupon->start_date->format('Y-m-d') : '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date', isset($coupon) ? $coupon->end_date->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
                                <button type="submit"
                                    class="btn btn-primary">{{ isset($coupon) ? 'Cập nhật' : 'Thêm mới' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
