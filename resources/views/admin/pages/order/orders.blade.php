@extends('layouts.admin')
@section('title', 'Quản lý đơn hàng')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách đơn hàng</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Quản lý hình ảnh và thông tin đơn hàng</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <table class="table table-striped table-bordered" style="text-align: center">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Mã đơn hàng</th>
                                                    <th>Tài khoản</th>
                                                    <th>Thông tin địa chỉ</th>
                                                    <th>Tổng tiền</th>
                                                    <th>Trạng thái đơn hàng</th>
                                                    <th>Trạng thái thanh toán</th>
                                                    <th>Chi tiết</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td>{{ $order->id }}</td>
                                                        <td>{{ $order->order_code }}</td>
                                                        <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                                                        <th>
                                                            <a href="javascript:void(0)"data-toggle="modal"
                                                                data-target="#addressShippingModal-{{ $order->id }}">{{ $order->shippingAddress->address }}</a>
                                                        </th>
                                                        <td>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                                                        <td class="order-status">
                                                            @php
                                                                $itemsCount = $order->orderItems->count();

                                                                // Kiểm tra có item nào bị hoàn không
                                                                $returnItem = $order->orderItems->first(function (
                                                                    $item,
                                                                ) {
                                                                    return $item->returnRequest !== null;
                                                                });
                                                            @endphp

                                                            {{-- TRƯỜNG HỢP 1: Đơn chỉ có 1 sản phẩm & sản phẩm đó bị hoàn --}}
                                                            @if ($itemsCount === 1 && $returnItem)
                                                                @php
                                                                    $returnStatusVN = [
                                                                        'requested' => 'Khách hàng gửi yêu cầu',
                                                                        'approved' => 'Shop chấp thuận',
                                                                        'returning' => 'Đang xử lý hoàn hàng',
                                                                        'returned_goods' => 'Đang hoàn hàng',
                                                                        'done' => 'Hoàn tất đơn hàng trả',
                                                                    ];
                                                                @endphp

                                                                <span class="badge bg-info fs-7 px-3 py-2">
                                                                    {{ $returnStatusVN[$returnItem->returnRequest->status] ?? 'Đang hoàn hàng' }}
                                                                </span>

                                                                {{-- TRƯỜNG HỢP 2: Đơn có nhiều sản phẩm → GIỮ NGUYÊN TRẠNG THÁI ĐƠN --}}
                                                            @else
                                                                @if ($order->status == 'pending')
                                                                    <span
                                                                        class="badge bg-warning text-dark fs-7 px-3 py-2">Chờ
                                                                        xác nhận</span>
                                                                @elseif ($order->status == 'processing')
                                                                    <span class="badge bg-primary fs-7 px-3 py-2">Đã xác
                                                                        nhận</span>
                                                                @elseif ($order->status == 'shipped')
                                                                    <span
                                                                        class="badge bg-info text-dark fs-7 px-3 py-2">Đang
                                                                        giao hàng</span>
                                                                @elseif ($order->status == 'completed')
                                                                    <span class="badge bg-success fs-7 px-3 py-2">Giao hàng
                                                                        thành công</span>
                                                                @elseif ($order->status == 'received')
                                                                    <span class="badge bg-info fs-7 px-3 py-2">Đã nhận được
                                                                        hàng</span>
                                                                @elseif ($order->status == 'failed_delivery')
                                                                    <span class="badge bg-secondary fs-7 px-3 py-2">Giao
                                                                        hàng thất bại</span>
                                                                @elseif ($order->status == 'canceled')
                                                                    <span class="badge bg-danger fs-7 px-3 py-2">Đã
                                                                        hủy</span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{-- Trạng thái hoàn tiền --}}
                                                            @if ($order->status == 'canceled' && $order->payment?->status == 'completed')
                                                                @php
                                                                    $refundStatus = $order->refund->status ?? null;
                                                                @endphp
                                                                @if (!$refundStatus)
                                                                    <span class="badge bg-warning fs-7 px-3 py-2">Chờ nhập
                                                                        thông tin ngân hàng</span>
                                                                @else
                                                                    <span
                                                                        class="badge 
                {{ $refundStatus == 'waiting_info' ? 'bg-warning' : '' }}
                {{ $refundStatus == 'submitted' ? 'bg-primary' : '' }}
                {{ $refundStatus == 'in_process' ? 'bg-dark' : '' }}
                {{ $refundStatus == 'refunded' ? 'bg-info' : '' }}
                {{ $refundStatus == 'failed' ? 'bg-danger' : '' }}
            fs-7 px-3 py-2">
                                                                        @switch($refundStatus)
                                                                            @case('waiting_info')
                                                                                Chờ nhập thông tin
                                                                            @break

                                                                            @case('submitted')
                                                                                Đã gửi yêu cầu
                                                                            @break

                                                                            @case('in_process')
                                                                                Đang xử lý hoàn tiền
                                                                            @break

                                                                            @case('refunded')
                                                                                Hoàn tiền thành công
                                                                            @break

                                                                            @case('failed')
                                                                                Hoàn tiền thất bại
                                                                            @break
                                                                        @endswitch
                                                                    </span>
                                                                @endif
                                                            @endif

                                                            @if ($order->payment_method == 'cod')
                                                                @php
                                                                    $codPaidStatuses = ['completed', 'received']; // trạng thái COD coi là đã thanh toán
                                                                @endphp
                                                                <span
                                                                    class="badge {{ in_array($order->status, $codPaidStatuses) ? 'bg-success' : 'bg-danger' }} fs-7 px-3 py-2">
                                                                    {{ in_array($order->status, $codPaidStatuses) ? 'Đã thanh toán (COD)' : 'Chưa thanh toán' }}
                                                                </span>
                                                            @else
                                                                {{-- Online --}}
                                                                <span
                                                                    class="badge 
        {{ $order->payment?->status == 'completed' ? 'bg-success' : ($order->payment?->status == 'pending' ? 'bg-danger' : 'bg-secondary') }}
        fs-7 px-3 py-2">
                                                                    @if ($order->payment?->status == 'completed')
                                                                        Đã thanh toán
                                                                    @elseif ($order->payment?->status == 'pending')
                                                                        Chưa thanh toán
                                                                    @else
                                                                        Thanh toán thất bại
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <button type="button" class="btn btn-info" data-toggle="modal"
                                                                data-target="#orderItemsModal-{{ $order->id }}">Xem</button>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-secondary dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown">
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    @if ($order->status == 'pending')
                                                                        <a class="dropdown-item confirm-order"
                                                                            href="javascript:void(0)"
                                                                            data-id="{{ $order->id }}">Xác nhận</a>
                                                                    @endif
                                                                    <a class="dropdown-item" target="_blank"
                                                                        href="{{ route('admin.orders.detail', $order->id) }}">Xem
                                                                        chi tiết</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @foreach ($orders as $order)
                                            {{-- Modal Address --}}
                                            <!-- Modal Thông tin giao hàng -->
                                            <div class="modal fade" id="addressShippingModal-{{ $order->id }}"
                                                tabindex="-1" aria-labelledby="addressShippingModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title" id="addressShippingModalLabel">Thông tin
                                                                giao hàng</h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Đóng">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-borderless mb-0">
                                                                <tr>
                                                                    <th>Người nhận:</th>
                                                                    <td><strong>{{ $order->shippingAddress->full_name }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Địa chỉ:</th>
                                                                    <td>{{ $order->shippingAddress->address }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Thành phố:</th>
                                                                    <td>{{ $order->shippingAddress->province }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Quận:</th>
                                                                    <td>{{ $order->shippingAddress->district }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Phường:</th>
                                                                    <td>{{ $order->shippingAddress->ward }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Số điện thoại:</th>
                                                                    <td><strong>{{ $order->shippingAddress->phone }}</strong>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Đóng</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Modal OrderItems --}}
                                            <div class="modal fade" id="orderItemsModal-{{ $order->id }}" tabindex="-1"
                                                aria-labelledby="orderItemsModal-{{ $order->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="orderItemsModalLabel">Chi tiết hóa
                                                                đơn</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Tên sản phẩm</th>
                                                                        <th>Số lượng</th>
                                                                        <th>Đơn giá</th>
                                                                        <th>Thành tiền</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php $index = 1;  @endphp
                                                                    @foreach ($order->orderItems as $item)
                                                                        <tr>
                                                                            <td>{{ $index++ }}</td>
                                                                            <td>
                                                                                <strong>{{ $item->product->name }}</strong>
                                                                                @if ($item->variant)
                                                                                    <div class="text-muted small">
                                                                                        {{ $item->variant->color->name ?? 'Không có' }},
                                                                                        {{ $item->variant->size->name ?? 'Không có' }}
                                                                                    </div>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $item->quantity }}</td>
                                                                            <td><span
                                                                                    class="text-success fw-semibold">{{ number_format($item->price, 0, ',', '.') }}
                                                                                    ₫</span></td>
                                                                            <td><span
                                                                                    class="text-success fw-semibold">{{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                                                                    ₫</span></td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="mt-3 d-flex justify-content-end">
                                            {{ $orders->links('pagination::bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on("click", ".confirm-order", function(e) {
            e.preventDefault();

            let button = $(this);
            let orderId = button.data("id");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "orders/confirm",
                data: {
                    id: orderId
                },
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);

                        // Cập nhật trạng thái trong bảng
                        button.closest("tr").find(".order-status").text("Đã xác nhận");

                        // Ẩn nút Xác nhận
                        button.hide();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error("Đã có lỗi xảy ra: " + error);
                }
            });
        });
    </script>

@endsection
