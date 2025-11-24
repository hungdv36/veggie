@php
    $shippingFee = 25000;
    $refundStatus = [
        'waiting_info' => 'Chờ thông tin',
        'submitted' => 'Đã gửi yêu cầu',
        'in_process' => 'Đang xử lý',
        'refunded' => 'Hoàn tiền thành công',
        'failed' => 'Hoàn tiền thất bại',
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Chi tiết hoàn tiền đơn hàng')

@section('content')
    <div class="container" style="max-width: 900px;">
        <h4 class="mb-4">Chi tiết hoàn tiền - Mã đơn: {{ $refund->order->order_code }}</h4>

        {{-- Thông tin đơn hàng --}}
        <div class="card mb-3">
            <div class="card-header fw-bold bg-light">Thông tin đơn hàng</div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="form-label">Khách hàng</label>
                    <input type="text" class="form-control"
                        value="{{ optional($refund->order->user)->name ?? 'Chưa có thông tin' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control"
                        value="{{ optional($refund->order->shippingAddress)->address ?? '' }}, {{ optional($refund->order->shippingAddress)->city ?? '' }}"
                        disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Tổng tiền đơn</label>
                    <input type="text" class="form-control"
                        value="{{ number_format($refund->order->total_amount ?? 0) }} đ" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Trạng thái đơn</label>
                    <input type="text" class="form-control" value="{{ $refund->order->status ?? 'Chưa có thông tin' }}"
                        disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Ngày hủy</label>
                    <input type="text" class="form-control" value="{{ $refund->created_at->format('d/m/Y') }}" disabled>
                </div>
            </div>
        </div>

        {{-- Thông tin sản phẩm --}}
        <div class="card mb-4">
            <div class="card-header fw-bold bg-light">Thông tin sản phẩm</div>
            <div class="card-body p-0" style="overflow-x:auto;">
                <table class="table table-bordered text-center align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá bán</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($refund->order->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                                    @if ($item->variant)
                                        <small> ({{ $item->variant->size?->name ?? '' }},
                                            {{ $item->variant->color?->name ?? '' }})</small>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Tổng voucher & phí ship --}}
                <div class="d-flex justify-content-end flex-column align-items-end mt-3">
                    <p class="mb-1">Voucher:
                        <strong class="text-danger">
                            -{{ number_format($refund->order->orderCoupons->sum('discount_amount') ?? 0, 0, ',', '.') }}₫
                        </strong>
                    </p>
                    <p class="mb-1">Phí vận chuyển:
                        <strong class="text-secondary">{{ number_format($shippingFee, 0, ',', '.') }}₫</strong>
                    </p>
                    <p class="fw-bold fs-5">Tổng cần hoàn:
                        <span class="text-primary">
                            {{ number_format($refund->amount ?? $refund->order->total_amount, 0, ',', '.') }}₫
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Thông tin hoàn tiền --}}
        <div class="card mb-3">
            <div class="card-header fw-bold bg-light">Hoàn tiền</div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="form-label">Trạng thái hoàn tiền</label>
                    <input type="text" class="form-control"
                        value="{{ $refundStatus[$refund->status] ?? $refund->status }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Ngân hàng</label>
                    <input type="text" class="form-control" value="{{ $refund->bank_name }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Số tài khoản</label>
                    <input type="text" class="form-control" value="{{ $refund->account_number }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Chủ tài khoản</label>
                    <input type="text" class="form-control" value="{{ $refund->account_holder }}" disabled>
                </div>
                @if ($refund->receipt)
                    <p><strong>Biên lai:</strong>
                        <a href="{{ asset($refund->receipt) }}" target="_blank" class="fs-5">Xem</a>
                    </p>
                @endif
            </div>
        </div>

        {{-- Upload biên lai & cập nhật trạng thái --}}
        @if ($refund->status != 'refunded')
            <div class="card mb-3">
                <div class="card-header fw-bold bg-light">Cập nhật hoàn tiền</div>
                <div class="card-body">
                    {{-- Cập nhật trạng thái --}}
                    @if ($refund->status == 'submitted')
                        <form action="{{ route('admin.refunds.updateStatus', $refund->id) }}" method="POST"
                            class="mb-3">
                            @csrf
                            @method('PUT')
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select mb-2">
                                <option value="submitted" selected>Đã gửi yêu cầu</option>
                                <option value="in_process">Đang xử lý hoàn tiền</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Cập nhật trạng thái</button>
                        </form>
                    @endif

                    {{-- Upload biên lai & hoàn tiền thủ công --}}
                    <form action="{{ route('admin.refunds.complete', $refund->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Xác nhận hoàn tiền</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
