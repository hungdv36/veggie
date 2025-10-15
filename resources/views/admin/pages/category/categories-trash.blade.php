@extends('layouts.admin')
@section('title', 'Danh mục đã xóa')

@section('content')
    <div class="right_col" role="main">
        <div class="page-title mb-4">
            <div class="title_left">
                <h3>Danh mục đã xóa</h3>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Quay
                    lại</a>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <span>Thùng rác danh mục</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên danh mục</th>
                                        <th>Mô tả</th>
                                        <th>Hình ảnh</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr id="row-{{ $category->id }}">
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description }}</td>
                                            <td>
                                                @if (isset($category) && $category->image)
                                                    <img src="{{ asset('assets/admin/img/category/' . $category->image) }}"
                                                        alt="{{ $category->name }}" style="height:100px;width:100px;">
                                                @else
                                                    <img src="{{ asset('uploads/categories/default.png') }}" alt="Default"
                                                        width="80">
                                                @endif
                                            </td>
                                            <td style="display:flex; gap:5px; justify-content:center;">
                                                <button type="button" class="btn btn-sm btn-success btn-restore-category"
                                                    data-id="{{ $category->id }}">
                                                    <i class="fa fa-undo"></i> Khôi phục
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-force-delete-category"
                                                    data-id="{{ $category->id }}">
                                                    <i class="fa fa-trash"></i> Xóa vĩnh viễn
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
