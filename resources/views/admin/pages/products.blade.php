@extends('layouts.admin')
@section('title', 'Quản lý sản phẩm')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách sản phẩm</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Danh sách sản phẩm <small>Quản lý hình ảnh và thông tin</small></h2>
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
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Hình ảnh</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Danh mục</th>
                                                    <th>Giá</th>
                                                    <th>Mô tả</th>
                                                    <th>Sửa</th>
                                                    <th>Xóa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        <td>
                                                            @if ($product->images->isNotEmpty())
                                                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                                    width="100">
                                                            @else
                                                                <span>Không có ảnh</span>
                                                            @endif
                                                        </td>

                                                        <td>{{ $product->name }}</td>

                                                        <td>{{ $product->category->name ?? 'Không có danh mục' }}</td>

                                                        <td>{{ number_format($product->price, 0, ',', '.') }}₫</td>

                                                        <td>{{ $product->description }}</td>

                                                        <td>
                                                            <a href="" class="btn btn-sm btn-warning"
                                                                data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $product->id }}">
                                                                <i class="fa fa-edit"></i> Sửa
                                                            </a>
                                                        </td>

                                                        <td>
                                                            <form action="" method="POST"
                                                                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                                                @csrf

                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-trash"></i> Xóa

                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="modalUpdate-{{ $product->id }}"
                                                        tabindex="-1" aria-labelledby="productModelLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="productModelLabel">
                                                                        Chỉnh sửa</h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="update-product" method="POST"
                                                                        enctype="multipart/form-data">
                                                                        @csrf

                                                                        <div class="mb-3">
                                                                            <label for="name" class="form-label">Tên
                                                                                sản phẩm <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="name"
                                                                                id="name" class="form-control"
                                                                                value="{{ $product->name ?? '' }}" required>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="name" class="form-label">Danh
                                                                                Mục
                                                                                <span class="text-danger">*</span></label>
                                                                            <div>
                                                                                <select name="category_id" id="category_id"
                                                                                    class="form-control">
                                                                                    <option value="">Chọn danh mục
                                                                                    </option>
                                                                                    @foreach ($categories as $category)
                                                                                        <option value="{{ $category->id }}"
                                                                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                                                            {{ $category->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>

                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="description" class="form-label">Mô
                                                                                tả</label>
                                                                            <textarea name="description" id="description" class="form-control" rows="4">{{ $product->description ?? '' }}</textarea>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="name" class="form-label">GíG
                                                                                sản phẩm <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="number" name="price"
                                                                                id="product-price" class="form-control"
                                                                                value="{{ $product->price ?? '' }}"
                                                                                required="required">
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="name" class="form-label"> Số
                                                                                lượng
                                                                                sản phẩm <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="number" name="stock"
                                                                                id="product-stock" class="form-control"
                                                                                value="{{ $product->stock ?? '' }}"
                                                                                required="required">
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="name" class="form-label"> Đơn
                                                                                vị
                                                                                sản phẩm <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="unit"
                                                                                id="product-unit" class="form-control"
                                                                                value="{{ $product->unit ?? '' }}"
                                                                                required="required">
                                                                        </div>

                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align"
                                                                                for="product-images-{{ $product->id }}">
                                                                                Hình ảnh
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <label class="custom-file-upload"
                                                                                    for="product-images-{{ $product->id }}">
                                                                                    Chọn ảnh
                                                                                </label>
                                                                                <input type="file" name="images[]"
                                                                                    id="product-images-{{ $product->id }}"
                                                                                    class="product-images"
                                                                                    data-id="{{ $product->id }}"
                                                                                    accept="image/*" multiple required>

                                                                                <div id="image-preview-container-{{ $product->id }}"
                                                                                    class="image-preview-container image-preview-listproduct"
                                                                                    data-id="{{ $product->id }}">
                                                                                    @foreach ($product->images as $image)
                                                                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                                                                            alt="Ảnh sản phẩm"
                                                                                            class="image-preview-item"
                                                                                            style="max-width: 120px; margin: 5px; border-radius: 6px;">
                                                                                    @endforeach

                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Quay lại</button>
                                                                    <button type="button"
                                                                        class="btn btn-primary btn-update-submit-product"
                                                                        data-id="{{ $product->id }}">Chỉnh sửa</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection
