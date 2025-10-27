@extends('layouts.admin')
@section('title', 'Quản lý sản phẩm')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách sản phẩm</h3>
                    <a href="{{ route('admin.products.trash') }}" class="btn btn-outline-danger me-2">
                        <i class="fa fa-trash"></i>
                        Thùng rác
                    </a>
                </div>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.products.add') }}" class="btn btn-success">Thêm sản phẩm</a>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Quản lý hình ảnh và thông tin</h2>
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
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Ảnh</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Danh mục</th>
                                                    <th>Giá (dao động)</th>
                                                    <th>Tồn kho</th>
                                                    <th>Trạng thái</th>
                                                    <th>Biến thể</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $index => $product)
                                                    <tr id="product-row-{{ $product->id }}">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            @if ($product->image)
                                                                <img src="{{ asset('assets/admin/img/product/' . $product->image) }}"
                                                                    alt="{{ $product->name }}"
                                                                    style="height:100px;width:100px; object-fit:cover;">
                                                            @else
                                                                <img src="{{ asset('assets/img/product/default.png') }}"
                                                                    alt="Default" width="80">
                                                            @endif
                                                        </td>
                                                        <td>{{ $product->name }}</td>
                                                        <td>{{ $product->category->name ?? 'Không có' }}</td>
                                                        <td>
                                                            @if ($product->variants->count())
                                                                {{ number_format($product->variants->min('price')) }} -
                                                                {{ number_format($product->variants->max('price')) }}
                                                            @else
                                                                {{ number_format($product->price) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $product->total_stock }}</td>
                                                        <td>{{ $product->stock_status }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                                data-bs-target="#variantsModal-{{ $product->id }}">
                                                                Xem biến thể
                                                            </button>
                                                        </td>
                                                        <td
                                                            style="display: flex; gap: 5px; justify-content: center; align-items: center;">
                                                            <a href="{{ route('admin.products.show', $product->id) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalUpdate-{{ $product->id }}"
                                                                title="Sửa">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-delete-product"
                                                                data-id="{{ $product->id }}" title="Xóa">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal Update Product -->
                                                    <div class="modal fade" id="modalUpdate-{{ $product->id }}"
                                                        tabindex="-1"
                                                        aria-labelledby="productModalLabel-{{ $product->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="productModalLabel-{{ $product->id }}">Chỉnh
                                                                        sửa sản phẩm</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="update-product-{{ $product->id }}"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $product->id }}">

                                                                        <div class="mb-3">
                                                                            <label for="name-{{ $product->id }}"
                                                                                class="form-label">Tên sản phẩm <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="name"
                                                                                id="name-{{ $product->id }}"
                                                                                class="form-control"
                                                                                value="{{ $product->name }}" required>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="category_id-{{ $product->id }}"
                                                                                class="form-label">Danh mục <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="category_id"
                                                                                id="category_id-{{ $product->id }}"
                                                                                class="form-control" required>
                                                                                @foreach ($categories as $category)
                                                                                    <option value="{{ $category->id }}"
                                                                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                                                        {{ $category->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="price-{{ $product->id }}"
                                                                                    class="form-label">Giá <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="number" name="price"
                                                                                    id="price-{{ $product->id }}"
                                                                                    class="form-control"
                                                                                    value="{{ $product->price }}"
                                                                                    min="0" required>
                                                                            </div>
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="stock-{{ $product->id }}"
                                                                                    class="form-label">Tồn kho</label>
                                                                                <input type="number" name="stock"
                                                                                    id="stock-{{ $product->id }}"
                                                                                    class="form-control"
                                                                                    value="{{ $product->stock }}"
                                                                                    min="0">
                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="unit-{{ $product->id }}"
                                                                                class="form-label">Đơn vị</label>
                                                                            <input type="text" name="unit"
                                                                                id="unit-{{ $product->id }}"
                                                                                class="form-control"
                                                                                value="{{ $product->unit ?? 'cái' }}">
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="description-{{ $product->id }}"
                                                                                class="form-label">Mô tả</label>
                                                                            <textarea name="description" id="description-{{ $product->id }}" class="form-control" rows="4">{{ $product->description }}</textarea>
                                                                        </div>

                                                                        {{-- Ảnh đại diện --}}
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Hình ảnh đại
                                                                                diện</label>
                                                                            <input type="file" name="image"
                                                                                id="imageInput-{{ $product->id }}"
                                                                                class="d-none" accept="image/*">
                                                                            <label for="imageInput-{{ $product->id }}"
                                                                                class="d-flex flex-column align-items-center justify-content-center gap-2"
                                                                                style="position: relative; background-color: #fff; border:2px solid #ccc; height:100px; width:100%; cursor:pointer; border-radius:6px; overflow:hidden;">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="20" height="20"
                                                                                    fill="currentColor"
                                                                                    class="bi bi-image"
                                                                                    viewBox="0 0 16 16">
                                                                                    <path
                                                                                        d="M14.002 3.002a1 1 0 0 1 1 1v8.002a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4.002a1 1 0 0 1 1-1h12.002zm0-1H2a2 2 0 0 0-2 2v8.002a2 2 0 0 0 2 2h12.002a2 2 0 0 0 2-2V4.002a2 2 0 0 0-2-2z" />
                                                                                    <path
                                                                                        d="M10.648 8.646a.5.5 0 0 1 .707 0l2.647 2.647V5.5a.5.5 0 0 0-.5-.5H2.5a.5.5 0 0 0-.5.5v7.293l3.646-3.647a.5.5 0 0 1 .707 0l2.647 2.647 1.648-1.647z" />
                                                                                </svg>
                                                                                <span>Chọn 1 ảnh</span>
                                                                                <img id="imagePreview-{{ $product->id }}"
                                                                                    src="{{ $product->image ? asset('assets/admin/img/product/' . $product->image) : '#' }}"
                                                                                    alt="Preview"
                                                                                    style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); max-width:100%; max-height:100%; object-fit:cover; border-radius:4px;">
                                                                            </label>
                                                                        </div>

                                                                        {{-- Album ảnh --}}
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Album ảnh</label>
                                                                            <input type="file" name="images[]"
                                                                                id="imagesInput-{{ $product->id }}"
                                                                                class="d-none" accept="image/*" multiple>
                                                                            <label for="imagesInput-{{ $product->id }}"
                                                                                style="position: relative; width:100%; min-height:120px; border:2px solid #ccc; border-radius:6px; background:#fff; cursor:pointer; overflow:auto; padding:20px; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; gap:5px;">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="20" height="20"
                                                                                    fill="currentColor"
                                                                                    class="bi bi-images"
                                                                                    viewBox="0 0 16 16">
                                                                                    <path
                                                                                        d="M4.502 9a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z" />
                                                                                    <path
                                                                                        d="M14 3a1 1 0 0 1 1 1v8.002a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4.002a1 1 0 0 1 1-1h12zm0-1H2a2 2 0 0 0-2 2v8.002a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4.002a2 2 0 0 0-2-2z" />
                                                                                </svg>
                                                                                <span
                                                                                    style="width:100%; text-align:center;">Chọn
                                                                                    1 hoặc nhiều ảnh</span>
                                                                                <div id="imagesPreview-{{ $product->id }}"
                                                                                    style="display:flex; flex-wrap:wrap; justify-content:center; gap:5px; margin-top:5px;">
                                                                                    @foreach ($product->images as $img)
                                                                                        <img src="{{ asset($img->image_path) }}"
                                                                                            alt="Ảnh album"
                                                                                            style="height:80px;width:80px;object-fit:cover;"
                                                                                            class="rounded border">
                                                                                    @endforeach
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Quay lại</button>
                                                                    <button type="button"
                                                                        class="btn btn-primary btn-update-product"
                                                                        data-id="{{ $product->id }}">Chỉnh sửa</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @foreach ($products as $product)
                                            <div class="modal fade" id="variantsModal-{{ $product->id }}"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ $product->name }} - Biến thể</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($product->variants->count())
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Size</th>
                                                                            <th>Màu sắc</th>
                                                                            <th>Giá</th>
                                                                            <th>Giá KM</th>
                                                                            <th>Số lượng</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($product->variants as $variant)
                                                                            <tr>
                                                                                <td>{{ $variant->size->name ?? '' }}</td>
                                                                                <td>{{ $variant->color->name ?? '' }}</td>
                                                                                <td>{{ number_format($variant->price) }}
                                                                                </td>
                                                                                <td>{{ number_format($variant->sale_price ?? 0) }}
                                                                                </td>
                                                                                <td>{{ $variant->quantity }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @else
                                                                <p>Sản phẩm chưa có biến thể.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="mt-3 d-flex justify-content-end">
                                            {{ $products->links('pagination::bootstrap-5') }}
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
    <!-- /page content -->
@endsection
