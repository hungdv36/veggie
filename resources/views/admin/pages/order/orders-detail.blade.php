@php
    $shippingFee = 25000; // phí vận chuyển cố định
    $statusOrder = ['pending', 'processing', 'shipped', 'failed_delivery', 'completed', 'received', 'canceled'];
    $currentIndex = array_search($order->status, $statusOrder);
    $cancellableStatuses = ['pending', 'processing'];
@endphp

@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <div class="container py-4 d-flex justify-content-center">
        <div class="content-wrapper" style="max-width: 900px; width: 100%;">

            {{-- 🔹 Thanh nút chức năng trên cùng --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Quay lại
                </a>
                <div class="d-flex gap-2">
                    <button class="btn btn-info">
                        <i class="fa fa-print me-1"></i> In hóa đơn
                    </button>
                    @if (!$order->invoice_sent)
                        <button id="btnSendInvoice" class="btn btn-primary">
                            <i class="fa fa-envelope"></i> Gửi hóa đơn
                        </button>
                    @else
                        <div class="text-center mt-1">
                            <span class="badge bg-success px-3 py-2 shadow-sm d-inline-block" style="font-size: 14px;">
                                ✅ Đã gửi hóa đơn lúc
                                {{ \Carbon\Carbon::parse($order->invoice_sent_at)->format('H:i d/m/Y') }}
                            </span>
                        </div>
                    @endif

                    @if (in_array($order->status, ['pending', 'processing', 'failed_delivery']))
                        <button id="cancelOrderBtn" class="btn btn-danger">
                            <i class="fa fa-times me-1"></i> Hủy đơn hàng
                        </button>
                    @endif
                </div>
            </div>

            {{-- Tiêu đề --}}
            <h4 class="fw-bold mb-4 text-center">
                Chi Tiết Đơn Hàng <span class="text-primary">#{{ $order->order_code }}</span>
            </h4>

            {{-- #1. Thông Tin Đơn Hàng --}}
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-header fw-bold bg-light border-0 rounded-top-3">#1. Thông Tin Đơn Hàng</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Mã Đơn Hàng</label>
                            <input type="text" class="form-control" value="{{ $order->order_code }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phương Thức Thanh Toán</label>
                            <input type="text" class="form-control" value="{{ $order->payment->payment_method ?? '' }}"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tên Khách Hàng</label>
                            <input type="text" class="form-control" value="{{ $order->shippingAddress->full_name }}"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trạng Thái Thanh Toán</label>
                            <input type="text" class="form-control"
                                value="{{ $order->payment->status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Điện Thoại</label>
                            <input type="text" class="form-control" value="{{ $order->shippingAddress->phone }}"
                                readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control" value="{{ $order->shippingAddress->address }}"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>

            {{-- #2. Thông Tin Sản Phẩm --}}
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-header fw-bold bg-light border-0 rounded-top-3">#2. Thông Tin Sản Phẩm</div>
                <div class="card-body">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Số Lượng</th>
                                <th>Giá Bán</th>
                                <th>Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end flex-column align-items-end mt-3">
                        <p class="mb-1">Mã giảm giá (Voucher):
                            <strong class="text-danger">
                                -{{ number_format($order->orderCoupons->sum('discount_amount'), 0, ',', '.') }}₫
                            </strong>
                        </p>
                        <p class="mb-1">Phí vận chuyển:
                            <strong class="text-secondary">{{ number_format($shippingFee, 0, ',', '.') }}₫</strong>
                        </p>
                        <p class="fw-bold fs-5">Tổng:
                            <span class="text-primary">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- #3. Lịch Sử Thay Đổi Trạng Thái --}}
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-header fw-bold bg-light border-0 rounded-top-3">#3. Lịch Sử Thay Đổi Trạng Thái</div>
                <div class="card-body">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Trạng Thái Thay Đổi</th>
                                <th>Ghi Chú</th>
                                <th>Người Thay Đổi</th>
                                <th>Thời Gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $statusVN = [
                                    'pending' => 'Chờ xác nhận',
                                    'processing' => 'Đã Xác Nhận',
                                    'shipped' => 'Đang giao',
                                    'failed_delivery' => 'Giao hàng thất bại',
                                    'completed' => 'Giao hàng thành công',
                                    'received' => 'Đã nhận được hàng',
                                    'canceled' => 'Đơn hàng đã hủy',
                                ];
                            @endphp

                            @forelse ($order->status_logs->sortBy('changed_at') as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $statusVN[$log->old_status] ?? $log->old_status }} →
                                        {{ $statusVN[$log->status] ?? $log->status }}</td>
                                    <td>{{ $log->notes ?? '-' }}</td>
                                    <td>{{ $log->role_id == 1 ? 'Admin' : 'Customer' }}</td>
                                    <td>{{ $log->changed_at ? \Carbon\Carbon::parse($log->changed_at)->format('H:i d/m/Y') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted">Chưa có lịch sử thay đổi trạng thái</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- #4. Thay Đổi Trạng Thái --}}
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header fw-bold bg-light border-0 rounded-top-3">
                    #4. Thay Đổi Trạng Thái Đơn Hàng
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                        <div class="row g-3">
                            {{-- Trạng thái --}}
                            <div class="col-12">
                                <label class="form-label">Trạng Thái</label>
                                <select id="statusSelect" name="status" class="form-select"
                                    {{ in_array($order->status, ['completed', 'received', 'canceled', 'failed_delivery']) ? 'disabled' : '' }}>

                                    {{-- Nếu chưa có trạng thái --}}
                                    @if (!$order->status)
                                        <option value="">-- Chọn trạng thái --</option>
                                    @endif

                                    @foreach ($statusOrder as $index => $status)
                                        @php
                                            $showOption = $index >= $currentIndex && $status !== 'canceled';
                                            if ($order->status === 'canceled' && $status === 'canceled') {
                                                $showOption = true;
                                            }
                                        @endphp

                                        @if ($showOption)
                                            <option value="{{ $status }}"
                                                {{ $order->status == $status ? 'selected' : '' }}
                                                {{ $order->status === 'canceled' && $status === 'canceled' ? 'disabled' : '' }}>
                                                @switch($status)
                                                    @case('pending')
                                                        Chờ Xác Nhận
                                                    @break

                                                    @case('processing')
                                                        Đã Xác Nhận
                                                    @break

                                                    @case('shipped')
                                                        Đang Giao Hàng
                                                    @break

                                                    @case('failed_delivery')
                                                        Giao hàng thất bại
                                                    @break

                                                    @case('completed')
                                                        Giao hàng thành công
                                                    @break

                                                    @case('received')
                                                        Đã nhận được hàng
                                                    @break

                                                    @case('canceled')
                                                        Đơn hàng đã hủy
                                                    @break
                                                @endswitch
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- Ghi chú --}}
                            <div class="col-12">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="Nhập ghi chú (nếu có)..."></textarea>
                            </div>
                        </div>

                        {{-- Nút thao tác --}}
                        <div class="d-flex justify-content-end mt-3">
                            <button type="reset" class="btn btn-secondary me-2">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>


            {{-- Modal Xác nhận hủy đơn --}}
            <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-3">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="cancelModalLabel">Xác nhận hủy đơn hàng</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có chắc chắn muốn hủy đơn hàng này không?</p>
                            <div class="mb-3">
                                <label for="cancelNote" class="form-label">Ghi chú (tuỳ chọn)</label>
                                <textarea id="cancelNote" class="form-control" rows="3" placeholder="Lý do hủy đơn..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" id="confirmCancelBtn" class="btn btn-danger">Xác nhận hủy</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const select = document.getElementById('statusSelect');
        const cancelBtn = document.getElementById('cancelOrderBtn');

        const cancellableStatuses = ['pending', 'processing', 'failed_delivery'];
        const orderStatus = '{{ $order->status }}';

        if (!cancellableStatuses.includes(orderStatus)) {
            cancelBtn.style.display = 'none';
        }

        // Ẩn nút Hủy khi dropdown mở
        select.addEventListener('focus', () => {
            cancelBtn.style.display = 'none';
        });

        // Hiện lại nút Hủy khi dropdown đóng và trạng thái cho phép
        select.addEventListener('blur', () => {
            if (cancellableStatuses.includes(orderStatus)) {
                cancelBtn.style.display = 'inline-block';
            }
        });

        document.querySelector('form[action="{{ route('admin.orders.updateStatus', $order->id) }}"]')
            .addEventListener('submit', function(e) {
                e.preventDefault(); // ngăn form submit mặc định

                const orderId = {{ $order->id }};
                const newStatus = document.getElementById('statusSelect').value;
                const note = this.querySelector('textarea[name="note"]').value;

                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            status: newStatus,
                            note
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire({
                            icon: data.status ? 'success' : 'error',
                            title: data.status ? 'Thành công' : 'Thất bại',
                            text: data.message, // luôn lấy từ server
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            willClose: () => {
                                if (data.status) location.reload();
                            }
                        });
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi hệ thống',
                            text: 'Vui lòng thử lại',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    });
            });

        document.getElementById('cancelOrderBtn')?.addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('cancelModal')).show();
        });
        document.getElementById('confirmCancelBtn')?.addEventListener('click', function() {
            const note = document.getElementById('cancelNote').value;
            const orderId = {{ $order->id }};

            fetch('{{ route('admin.orders.cancel', $order->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        note
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: data.status ? 'success' : 'error',
                        title: data.status ? 'Thành công' : 'Thất bại',
                        text: data.message,
                        timer: 3000, // 5 giây
                        timerProgressBar: true, // thanh tiến trình
                        showConfirmButton: false,
                        willClose: () => {
                            if (data.status) location.reload(); // reload sau khi alert tắt
                        }
                    });
                });
        });
        $(document).ready(function() {
            $('.send-invoice-mail').on('click', function(e) {
                e.preventDefault();
                let button = $(this);
                let orderId = button.data('id');

                $.ajax({
                    url: '{{ route('admin.orders.send-invoice') }}', // URL trùng với route
                    type: 'POST',
                    data: {
                        order_id: orderId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            button.remove(); // ẩn nút sau khi gửi
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi gửi hóa đơn.');
                    }
                });
            });
        });
    </script>
@endsection
