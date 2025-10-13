@extends('layouts.admin')

@section('title', 'Quản lý biến thể')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="right_col" role="main">

        <div class="page-title mb-4">
            <div class="title_left">
                <h3>Quản lý biến thể</h3>
                <a href="{{ route('admin.variants.trash') }}" class="btn btn-outline-danger me-2">
                    <i class="fa fa-trash"></i> Thùng rác
                </a>
                </a>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.variants.add') }}" class="btn btn-success">Thêm biến thể</a>
            </div>
        </div>


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>Danh sách tất cả biến thể</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0" style="text-align: center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variants as $variant)
                                        <tr>
                                            <td>{{ $variant->id }}</td>
                                            <td>{{ $variant->product->name }}</td>
                                            <td>{{ $variant->size }}</td>
                                            <td>{{ $variant->color }}</td>
                                            <td>{{ $variant->stock }}</td>
                                            <td>{{ number_format($variant->price ?? 0, 0, ',', '.') }} VNĐ</td>
                                            <td
                                                style="display: flex; gap: 5px; justify-content: center; align-items: center;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalUpdate-{{ $variant->id }}"
                                                    title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-delete-variant"
                                                    data-id="{{ $variant->id }}" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal cập nhật biến thể -->
                                        <div class="modal fade" id="modalUpdate-{{ $variant->id }}" tabindex="-1"
                                            aria-labelledby="variantModalLabel-{{ $variant->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="variantModalLabel-{{ $variant->id }}">
                                                            Chỉnh sửa biến thể
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Đóng"></button>
                                                    </div>

                                                    <form action="{{ route('admin.variants.update') }}" method="POST">
                                                        @csrf
                                                        <!-- Hidden input chứa ID biến thể -->
                                                        <input type="hidden" name="variant_id"
                                                            value="{{ $variant->id }}">

                                                        <div class="modal-body">

                                                            <div class="mb-3">
                                                                <label for="product_id" class="form-label">Sản phẩm</label>
                                                                <select name="product_id" class="form-select" required>
                                                                    @foreach ($products as $product)
                                                                        <option value="{{ $product->id }}"
                                                                            {{ $variant->product_id == $product->id ? 'selected' : '' }}>
                                                                            {{ $product->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Size</label>
                                                                <input type="text" name="size" class="form-control"
                                                                    value="{{ $variant->size }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Màu sắc</label>
                                                                <input type="text" name="color" class="form-control"
                                                                    value="{{ $variant->color }}" required>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Số lượng</label>
                                                                    <input type="number" name="stock"
                                                                        class="form-control" value="{{ $variant->stock }}"
                                                                        min="0" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Giá (VNĐ)</label>
                                                                    <input type="number" name="price"
                                                                        class="form-control" value="{{ $variant->price }}"
                                                                        min="0" step="1000" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Hủy</button>
                                                            <button type="button"
                                                                class="btn btn-primary btn-update-variant"
                                                                data-id="{{ $variant->id }}">
                                                                Cập nhật
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-3">
                                {{ $variants->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
