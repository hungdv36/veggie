@extends('layouts.admin')
@section('title', 'Chi tiết yêu cầu hoàn hàng')

@section('content')
    <div class="container py-4 d-flex justify-content-center">
        <div class="content-wrapper" style="max-width: 800px; width: 100%;">

            <h3 class="text-center mb-4">Yêu cầu hoàn hàng #{{ $return->id }}</h3>

            @php
                $statusVN = [
                    'requested' => 'Khách gửi yêu cầu',
                    'reviewing' => 'Shop đang xem xét',
                    'approved' => 'Shop đồng ý hoàn',
                    'rejected' => 'Yêu cầu bị từ chối',
                    'received_from_customer' => 'Hàng trả về shop',
                    'inspected' => 'Shop kiểm tra hàng',
                    'packaging' => 'Shop chuẩn bị hàng đổi',
                    'shipped_to_customer' => 'Hàng đang vận chuyển',
                    'completed_run' => 'Hoàn tất đổi hàng',
                    'done' => 'Hoàn tất đơn hàng trả',
                ];
            @endphp

            {{-- Thông tin yêu cầu --}}
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-header bg-light fw-bold">Thông tin yêu cầu</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Khách hàng</th>
                            <td>{{ $return->user->name ?? 'Khách vãng lai' }}</td>
                        </tr>
                        <tr>
                            <th>Sản phẩm</th>
                            <td>{{ $return->orderItem?->product?->name ?? 'Sản phẩm không tồn tại' }}</td>
                        </tr>
                        <tr>
                            <th>Lý do</th>
                            <td>{{ $return->reason }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <span
                                    class="badge 
                                {{ in_array($return->status, ['requested']) ? 'bg-warning' : '' }}
                                {{ in_array($return->status, ['reviewing', 'packaging', 'shipped_to_customer', 'received_from_customer', 'inspected']) ? 'bg-primary' : '' }}
                                {{ $return->status == 'approved' ? 'bg-success' : '' }}
                                {{ $return->status == 'rejected' ? 'bg-danger' : '' }}
                                {{ $return->status == 'done' ? 'bg-info' : '' }}
                                {{ $return->status == 'completed' ? 'bg-success' : '' }}
                            ">
                                    {{ $statusVN[$return->status] ?? $return->status }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Thông tin sản phẩm --}}
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-header bg-light fw-bold">Thông tin sản phẩm</div>
                <div class="card-body d-flex align-items-center">

                    <img src="{{ asset('assets/admin/img/product/' . $return->orderItem->product->image) }}"
                        alt="Hình sản phẩm" class="me-4 rounded"
                        style="width:100px; height:100px; object-fit:cover; border:1px solid #dee2e6;">

                    <div class="flex-grow-1">
                        <h5 class="mb-2">{{ $return->orderItem->product->name }}</h5>
                        <p class="mb-1"><strong>Size / Màu:</strong> {{ $return->orderItem->variant->size->name ?? '-' }}
                            / {{ $return->orderItem->variant->color->name ?? '-' }}</p>
                        <p class="mb-1"><strong>Số lượng:</strong> {{ $return->orderItem->quantity }}</p>
                    </div>
                </div>
            </div>

            {{-- Địa chỉ --}}
            <div class="card mb-3 p-3 shadow-sm">
                <h5 class="mb-3">Thông tin giao hàng</h5>
                <p><strong>Người nhận:</strong> {{ $return->order->shippingAddress->full_name ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $return->order->shippingAddress->address ?? 'N/A' }},
                    {{ $return->order->shippingAddress->ward ?? 'N/A' }},
                    {{ $return->order->shippingAddress->district ?? 'N/A' }},
                    {{ $return->order->shippingAddress->province ?? 'N/A' }}
                </p>
                <p><strong>Số điện thoại:</strong> {{ $return->order->shippingAddress->phone ?? 'N/A' }}</p>
            </div>

            {{-- Hình ảnh & Video --}}
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-header bg-light fw-bold">Hình ảnh & Video</div>
                <div class="card-body text-center">
                    @if ($return->images)
                        <div class="mb-3">
                            @foreach ($return->images as $img)
                                <img src="{{ asset($img) }}" class="img-thumbnail m-1" style="max-width:100px;">
                            @endforeach
                        </div>
                    @endif
                    @if ($return->videos)
                        <div>
                            @foreach ($return->videos as $vid)
                                <video src="{{ asset($vid) }}" controls class="m-1" style="max-width:200px;"></video>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Trạng thái xử lý yêu cầu --}}
            @php
                $statusOrder = [
                    'requested',
                    'reviewing',
                    'approved',
                    'received_from_customer',
                    'inspected',
                    'packaging',
                    'shipped_to_customer',
                    'completed_run',
                    'done',
                    'rejected',
                ];

                $currentStatus = $return->status;
                $currentIndex = array_search($currentStatus, $statusOrder);
                $isCompleted = $currentStatus === 'done';
            @endphp

            <div class="card mb-4 shadow-sm border-0 rounded-3 p-3">
                <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Trạng thái hoàn hàng</label>
                        <select name="status" class="form-select" {{ $isCompleted ? 'disabled' : '' }}>
                            @if ($isCompleted)
                                <option selected>{{ $statusVN[$currentStatus] }}</option>
                            @else
                                @foreach ($statusOrder as $index => $statusKey)
                                    @if ($index >= $currentIndex)
                                        <option value="{{ $statusKey }}" @selected($currentStatus === $statusKey)>
                                            {{ $statusVN[$statusKey] ?? $statusKey }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú (nếu có)</label>
                        <textarea name="staff_note" class="form-control" rows="2" {{ $isCompleted ? 'disabled' : '' }}>{{ $return->staff_note }}</textarea>
                    </div>
                    <div class="d-flex justify-content-start">
                        <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary me-2" {{ $isCompleted ? 'disabled' : '' }}>Cập nhật
                            trạng thái</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
