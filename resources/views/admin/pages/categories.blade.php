@extends('layouts.admin')
@section('title', 'Quản lý người dùng')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách danh mục</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thêm Danh Mục <small>different form elements</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>

                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <p class="text-muted font-13 m-b-30">
                                            The Buttons extension for DataTables provides a common set of options, API
                                            methods and styling to display buttons on a page that will interact with a
                                            DataTable. The core library provides the based framework upon which plug-ins can
                                            built.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Hình ảnh</th>
                                                    <th>Tên danh mục</th>
                                                    <th>Mô tả</th>
                                                    <th>Sửa</th>
                                                    <th>Xóa</th>
                                                </tr>
                                            </thead>


                                            <tbody>
                                                @foreach ($categories as $category)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ asset('storage/' . $category->image) }}"
                                                                alt="{{ $category->name }}" class="image-category">
                                                        </td>
                                                        <td>{{ $category->name }}</td>
                                                        
                                                        <td>{{ $category->description }}</td>
                                                        <td>
                                                            <i class="fa fa-edit"></i> Chỉnh sửa
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-close"></i> Xóa
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
        <!-- /page content -->
    @endsection
