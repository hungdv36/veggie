@extends('layouts.admin')
@section('title', 'Chi tiết yêu cầu hoàn hàng')

@section('content')
    <div class="container py-4 d-flex justify-content-center">
        <div class="content-wrapper" style="max-width: 800px; width: 100%;">

            <h3 class="text-center mb-4">Yêu cầu hoàn hàng #{{ $return->id }}</h3>

            @php
                $statusVN = [
                    'requested' => 'Khách gửi yêu cầu',
                    'approved' => 'Shop chấp thuận',
                    'returning' => 'Đang hoàn hàng',
                    'returned_goods' => 'Đang xử lý hoàn',
                    'done' => 'Hoàn hàng thành công',
                    'rejected' => 'Từ chối hoàn',
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
                                            @switch($return->status)
                                                @case('requested')
                                                    bg-warning
                                                    @break

                                                @case('approved')
                                                    bg-primary
                                                    @break

                                                @case('returning')
                                                    bg-info
                                                    @break

                                                @case('returned_goods')
                                                    bg-secondary
                                                    @break

                                                @case('done')
                                                    bg-success
                                                    @break

                                                @case('rejected')
                                                    bg-danger
                                                    @break
                                            @endswitch
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

            @php
                $statusOrder = ['requested', 'approved', 'returning', 'returned_goods', 'done', 'rejected'];
                $reasons = [
                    'sai_san_pham' => 'Sản phẩm không đúng',
                    'hong_hang' => 'Sản phẩm hỏng',
                    'khac' => 'Khác',
                ];

                $isLocked = in_array($return->status, ['rejected', 'done']);
                $currentIndex = array_search($return->status, $statusOrder);
            @endphp

            <div class="card mb-4 shadow-sm border-0 rounded-3 p-3">
                <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Chọn trạng thái --}}
                    <div class="mb-3">
                        <label class="form-label">Trạng thái hoàn hàng</label>
                        <select name="status" class="form-select" id="statusSelect" {{ $isLocked ? 'disabled' : '' }}>
                            @foreach ($statusOrder as $index => $statusKey)
                                {{-- Hiển thị trạng thái hiện tại và những trạng thái sau --}}
                                @if ($index >= $currentIndex)
                                    <option value="{{ $statusKey }}" @selected($return->status === $statusKey)>
                                        {{ $statusVN[$statusKey] ?? $statusKey }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Lý do từ chối --}}
                    <div class="mb-3" id="rejectReasonContainer"
                        style="display: {{ $return->status === 'rejected' ? '' : 'none' }}">
                        <label class="form-label">Lý do từ chối</label>
                        <select name="reject_reason" class="form-select" id="rejectReasonSelect"
                            {{ $isLocked ? 'disabled' : '' }}>
                            <option value="">Chọn lý do</option>
                            @foreach ($reasons as $key => $label)
                                <option value="{{ $key }}" @selected($return->reject_reason === $key)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Ghi chú --}}
                    <div class="mb-3">
                        <label class="form-label">Ghi chú (nếu có)</label>
                        <textarea name="staff_note" class="form-control" rows="2" {{ $isLocked ? 'disabled' : '' }}>{{ $return->staff_note }}</textarea>
                    </div>

                    <div class="d-flex justify-content-start">
                        <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary me-2">Quay lại</a>
                        <button type="submit" class="btn btn-primary" {{ $isLocked ? 'disabled' : '' }}>Cập nhật trạng
                            thái</button>
                    </div>
                </form>
            </div>

            <script>
                const statusSelect = document.getElementById('statusSelect');
                const rejectContainer = document.getElementById('rejectReasonContainer');

                // Khi load form
                window.addEventListener('DOMContentLoaded', () => {
                    rejectContainer.style.display = statusSelect.value === 'rejected' ? '' : 'none';
                });

                // Khi đổi trạng thái
                if (!statusSelect.disabled) { // chỉ toggle khi chưa khóa
                    statusSelect.addEventListener('change', () => {
                        rejectContainer.style.display = statusSelect.value === 'rejected' ? '' : 'none';
                    });
                }
            </script>
        </div>
    </div>
@endsection
