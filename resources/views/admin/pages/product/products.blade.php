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
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            @if ($product->image)
                                                                <img src="{{ asset('assets/img/product/' . $product->image) }}"
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
                                                        <td>{{ $product->stock }}</td>
                                                        <td>{{ $product->status == 'active' ? 'Còn hàng' : 'Hết hàng' }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                                data-bs-target="#variantsModal-{{ $product->id }}">
                                                                Xem biến thể
                                                            </button>
                                                        </td>
                                                        <td
                                                            style="display: flex; gap: 5px; justify-content: center; align-items: center;">
                                                            <button type="button" class="btn btn-sm btn-outline-primary"
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
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @foreach ($products as $product)
                                            <div class="modal fade" id="variantsModal-{{ $product->id }}" tabindex="-1"
                                                aria-hidden="true">
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
