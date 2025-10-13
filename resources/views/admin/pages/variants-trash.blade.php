@extends('layouts.admin')
@section('title', 'Biến thể đã xóa')

@section('content')
    <div class="right_col" role="main">
        <div class="page-title mb-4">
            <div class="title_left">
                <h3>Biến thể đã xóa</h3>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.variants.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Quay
                    lại</a>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <span>Thùng rác biến thể</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Sản phẩm</th>
                                        <th>Size</th>
                                        <th>Màu sắc</th>
                                        <th>Số lượng</th>
                                        <th>Giá (VNĐ)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variants as $variant)
                                        <tr id="variant-row-{{ $variant->id }}"
                                            class="{{ $variant->trashed() ? 'table-warning' : '' }}">
                                            <td>{{ $variant->id }}</td>
                                            <td>{{ $variant->product->name ?? '---' }}</td>
                                            <td>{{ $variant->size }}</td>
                                            <td>{{ $variant->color }}</td>
                                            <td>{{ $variant->stock }}</td>
                                            <td>{{ number_format($variant->price) }}</td>
                                            <td style="display:flex; gap:5px;">
                                                @if (!$variant->trashed())
                                                    <!-- Sửa -->
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modalUpdate-{{ $variant->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <!-- Xóa mềm -->
                                                    <button class="btn btn-sm btn-outline-danger btn-delete-variant"
                                                        data-id="{{ $variant->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @else
                                                    <!-- Khôi phục -->
                                                    <button class="btn btn-sm btn-success btn-restore-variant"
                                                        data-id="{{ $variant->id }}">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                    <!-- Xóa vĩnh viễn -->
                                                    <button class="btn btn-sm btn-danger btn-force-delete-variant"
                                                        data-id="{{ $variant->id }}">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $variants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
