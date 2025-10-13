@extends('layouts.admin')
@section('title', 'Quản lý người dùng')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thêm Sản Phẩm</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thêm Sản Phẩm mới </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>

                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <form action="{{ route('admin.product.add') }}" id="add-product" method="post"
                                class="form-horizontal form-label-left" enctype="multipart/form-data">
                                @csrf
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-name">Tên Sản
                                        Phẩm

                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" id="product-name" name="name" required="required"
                                            class="form-control ">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-name">Danh Mục
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select id="product-category" name="category_id" required="required"
                                            class="form-control">
                                            <option value="">Chọn danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-price">
                                        Giá <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" id="product-price" name="price" required="required"
                                            class="form-control" placeholder="Nhập giá sản phẩm">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-stock">
                                        Số lượng <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" id="product-stock" name="stock" required="required"
                                            class="form-control" placeholder="Nhập số lượng">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-unit">
                                        Đơn vị <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" id="product-unit" name="unit" required="required"
                                            class="form-control" placeholder="Nhập Đơn vị">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-description">Mô
                                        tả
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" id="product-descripton" name="description" required="required"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-images">Hình
                                        ảnh</label>
                                    <div class="col-md-6 col-sm-6 ">

                                        <label class="custom-file-upload" for="product-images">
                                            Chọn ảnh
                                        </label>
                                        <input type="file" name="images[]" id="product-images" accept="image/" multiple
                                            required>
                                        <div id="image-preview-container"></div>
                                    </div>
                                </div>

                                {{-- ========== PHẦN BIẾN THỂ ========== --}}
                                {{-- <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Biến thể sản phẩm</label>
                                    <div class="col-md-6 col-sm-6">

                                        <table class="table table-bordered" id="variants-table">
                                            <thead>
                                                <tr>
                                                    <th>Màu sắc</th>
                                                    <th>Kích thước</th>
                                                    <th>Giá</th>
                                                    <th>Tồn kho</th>
                                                    <th>Ảnh</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" name="variants[0][color]"
                                                            class="form-control" placeholder="VD: Đỏ"></td>
                                                    <td><input type="text" name="variants[0][size]"
                                                            class="form-control" placeholder="VD: M, L, XL"></td>
                                                    <td><input type="number" name="variants[0][price]"
                                                            class="form-control" placeholder="Giá"></td>
                                                    <td><input type="number" name="variants[0][stock]"
                                                            class="form-control" placeholder="Tồn kho"></td>
                                                    <td><input type="file" name="variants[0][image]" accept="image/*"
                                                            class="form-control"></td>
                                                    <td><button type="button"
                                                            class="btn btn-danger btn-sm remove-variant"><i
                                                                class="fa fa-trash"></i></button></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <button type="button" class="btn btn-info" id="add-variant-btn"><i
                                                class="fa fa-plus"></i> Thêm biến thể</button>
                                    </div>
                                </div> --}}
                                {{-- ========== HẾT PHẦN BIẾN THỂ ========== --}}

                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3">

                                        <button class="btn btn-primary btn_reset" type="reset">Reset</button>
                                        <button type="submit" class="btn btn-success">Thêm Sản Phẩm</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    @endsection
