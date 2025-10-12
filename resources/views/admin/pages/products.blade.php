@extends('layouts.admin')
@section('title', 'Quản lý sản phẩm')

@section('content')
    <div class="right_col" role="main">
        <div class="page-title mb-4">
            <div class="title_left">
                <h3>Quản lý sản phẩm</h3>
                <a href="#" class="btn btn-outline-danger me-2">
                    <i class="fa fa-trash"></i> Thùng rác
                </a>
                </a>
            </div>
            <div class="title_right text-right">
                <a href="#" class="btn btn-success">Thêm sản phẩm</a>
            </div>
        </div>


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>Danh sách tất cả sản phẩm</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Hỉnh ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Giá bán</th>
                                        <th>Tồn kho</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày thêm</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                    class="image-product" style="width:100px; height:100px">
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name ?? 'Không có danh mục' }}</td>
                                            <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>
                                                @if ($product->status == 'in_stock')
                                                    Còn hàng
                                                @elseif($product->status == 'out_of_stock')
                                                    Hết hàng
                                                @elseif($product->status == 'pre_order')
                                                    Đặt trước
                                                @else
                                                    Không xác định
                                                @endif
                                            </td>
                                            <td>{{ $product->created_at }}</td>
                                            <td
                                                style="display: flex; gap: 5px; justify-content: center; align-items: center;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalUpdate-{{ $product->id }}"
                                                    title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-delete-category"
                                                    data-id="{{ $product->id }}" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalUpdate-{{ $product->id }}" tabindex="-1"
                                            aria-labelledby="categoryModelLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="categoryModelLabel">Chỉnh sửa</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="update-category" method="POST"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="image" class="form-label">Hình ảnh</label>
                                                                <div class="mb-3">
                                                                    <label for="image-{{ $product->id }}"
                                                                        class="btn btn-primary">Chọn ảnh</label>
                                                                    <input type="file" name="image" class="image-input"
                                                                        data-id="{{ $product->id }}"
                                                                        id="image-{{ $product->id }}" accept="image/*"
                                                                        style="display:none;">

                                                                    <img class="image-preview"
                                                                        id="preview-{{ $product->id }}" src=""
                                                                        alt="Ảnh xem trước"
                                                                        style="height:100px;width:100px; margin-top:10px; display:none;">

                                                                    <img class="old-image" id="old-{{ $product->id }}"
                                                                        src="{{ $product->image
                                                                            ? asset('assets/admin/img/product/' . $product->image)
                                                                            : asset('uploads/products/default.png') }}"
                                                                        style="height:100px; width:100px; margin-top:10px; object-fit:cover;">
                                                                </div>

                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Tên sản phẩm <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" id="name"
                                                                    class="form-control" value="{{ $product->name ?? '' }}"
                                                                    required>
                                                            </div>

                                                            {{-- <div class="mb-3">
                                                                <label for="category_id" class="form-label">Danh mục <span
                                                                        class="text-danger">*</span></label>
<select name="category_id" id="category_id"
                                                                    class="form-select" required>
                                                                    <option value="">-- Chọn danh mục --</option>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}"
                                                                            {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>
                                                                            {{ $category->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div> --}}

                                                            <div class="mb-3">
                                                                <label for="price" class="form-label">Giá bán</label>
                                                                <textarea name="description" id="description" class="form-control" rows="4">{{ $product->price ?? '' }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="stock" class="form-label">Tồn kho</label>
                                                                <textarea name="stock" id="stock" class="form-control" rows="4">{{ $product->stock ?? '' }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="stack" class="form-label">Trạng
                                                                    thái</label>
                                                                <textarea name="status" id="status" class="form-control" rows="4">{{ $product->status ?? '' }}</textarea>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Quay lại</button>
                                                        <button type="button"
                                                            class="btn btn-primary btn-update-submit-category"
                                                            data-id="{{ $product->id }}">Chỉnh sửa</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $products->links() }}
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary btn-sm">Tải thêm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
