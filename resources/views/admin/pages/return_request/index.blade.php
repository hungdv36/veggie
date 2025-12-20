@extends('layouts.admin')
@section('title', 'Quản lý hoàn hàng')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách hoàn hàng</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Quản lý yêu cầu hoàn hàng</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="card-box table-responsive">
                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Đơn hàng</th>
                                            <th>Sản phẩm</th>
                                            <th>Khách</th>
                                            <th>Lý do</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày gửi</th>
                                            <th>Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($returns as $return)
                                            <tr>
                                                <td>{{ $return->id }}</td>
                                                <td>#{{ $return->order?->order_code }}</td>

                                                <td class="text-start">
                                                    <strong>
                                                        {{ $return->orderItem?->product?->name ?? 'Không tồn tại' }}
                                                    </strong>
                                                    <div class="text-muted small">
                                                        {{ $return->orderItem?->variant?->color?->name ?? 'N/A' }} /
                                                        {{ $return->orderItem?->variant?->size?->name ?? 'N/A' }}
                                                    </div>
                                                </td>

                                                <td>{{ $return->user?->name ?? 'Khách vãng lai' }}</td>
                                                <td>{{ $return->reason }}</td>

                                                @php
                                                    $statusTexts = [
                                                        'requested' => 'Đã gửi yêu cầu',
                                                        'approved' => 'Shop chấp thuận',
                                                        'returned_goods' => 'Đang xử lý hoàn',
                                                        'returning' => 'Đang hoàn hàng',
                                                        'done' => 'Hoàn hàng thành công',
                                                    ];
                                                @endphp

                                                <td>
                                                    <span
                                                        class="badge
                                                            @switch($return->status)
                                                                @case('requested') bg-warning @break
                                                                @case('approved') bg-primary @break
                                                                @case('returned_goods') bg-secondary @break
                                                                @case('returning') bg-info @break
                                                                @case('done') bg-success @break
                                                            @endswitch
                                                        ">
                                                        {{ $statusTexts[$return->status] }}
                                                    </span>
                                                </td>

                                                <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>

                                                <td>
                                                    <a href="{{ route('admin.returns.show', $return->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="mt-3 d-flex justify-content-end">
                                    {{ $returns->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
