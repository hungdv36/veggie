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
                                                            {{-- <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                                                <i class="fa fa-edit"></i> Sửa
                                                            </a> --}}
                                                        </td>

                                                        <td>
                                                            <form action="" method="POST"
                                                                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-trash"></i> Xóa
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
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
