@extends('layouts.admin')

@section('title', 'Hoàn tiền đơn hàng')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Hoàn tiền đơn hàng</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Danh sách hoàn tiền</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table class="table table-striped table-bordered table-hover text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã đơn hàng</th>
                                        <th>Khách hàng</th>
                                        <th>Địa chỉ</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái đơn</th>
                                        <th>Trạng thái hoàn tiền</th>
                                        <th>Ngày hủy</th>
                                        <th>Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->order_code }}</td>
                                            <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                                            <td>{{ $order->shippingAddress->address ?? '-' }}</td>
                                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                                            <td>
                                                @php
                                                    $statusClass = '';
                                                    switch ($order->status) {
                                                        case 'pending':
                                                            $statusClass = 'bg-warning text-dark';
                                                            break;
                                                        case 'processing':
                                                            $statusClass = 'bg-primary';
                                                            break;
                                                        case 'shipped':
                                                            $statusClass = 'bg-info text-dark';
                                                            break;
                                                        case 'completed':
                                                            $statusClass = 'bg-success';
                                                            break;
                                                        case 'received':
                                                            $statusClass = 'bg-info';
                                                            break;
                                                        case 'canceled':
                                                            $statusClass = 'bg-danger';
                                                            break;
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $refundStatus = $order->refund?->status ?? 'waiting_info';
                                                    $statusClass = match ($refundStatus) {
                                                        'waiting_info' => 'bg-warning',
                                                        'submitted' => 'bg-primary',
                                                        'in_process' => 'bg-info',
                                                        'refunded' => 'bg-success',
                                                        'failed' => 'bg-danger',
                                                        default => '',
                                                    };
                                                    $statusLabel = match ($refundStatus) {
                                                        'waiting_info' => 'Chờ nhập thông tin ngân hàng',
                                                        'submitted' => 'Đã gửi yêu cầu',
                                                        'in_process' => 'Đang xử lý hoàn tiền',
                                                        'refunded' => 'Hoàn tiền thành công',
                                                        'failed' => 'Hoàn tiền thất bại',
                                                        default => '-',
                                                    };
                                                @endphp

                                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                            </td>
                                            <td>{{ $order->refund?->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('admin.refunds.show', $order->refund->id) }}"
                                                    class="btn btn-sm btn-primary" title="Xem chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3 d-flex justify-content-end">
                                {{ $orders->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
