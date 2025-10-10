@extends('layouts.admin')
@section('title', 'Quản lý danh mục')

@section('content')
    <div class="right_col" role="main">
        <div class="page-title mb-4">
            <div class="title_left">
                <h3>Quản lý danh mục</h3>
                <a href="{{ route('admin.categories.trash') }}" class="btn btn-outline-danger me-2">
                    <i class="fa fa-trash"></i> Thùng rác
                </a>
                </a>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.categories.add') }}" class="btn btn-success">Thêm danh mục</a>
            </div>
        </div>


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>Danh sách tất cả danh mục</span>
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
                                        <tr>
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
                                            <td
                                                style="display: flex; gap: 5px; justify-content: center; align-items: center;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalUpdate-{{ $category->id }}"
                                                    title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-delete-category"
                                                    data-id="{{ $category->id }}" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalUpdate-{{ $category->id }}" tabindex="-1"
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
                                                                <label for="name" class="form-label">Tên danh mục <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" id="name"
                                                                    class="form-control"
                                                                    value="{{ $category->name ?? '' }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="description" class="form-label">Mô tả</label>
                                                                <textarea name="description" id="description" class="form-control" rows="4">{{ $category->description ?? '' }}</textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="image" class="form-label">Ảnh danh
                                                                    mục</label>
                                                                <div class="mb-3">
                                                                    <label for="image-{{ $category->id }}"
                                                                        class="btn btn-primary">Chọn ảnh</label>
                                                                    <input type="file" name="image" class="image-input"
                                                                        data-id="{{ $category->id }}"
                                                                        id="image-{{ $category->id }}" accept="image/*"
                                                                        style="display:none;">

                                                                    <img class="image-preview"
                                                                        id="preview-{{ $category->id }}" src=""
                                                                        alt="Ảnh xem trước"
                                                                        style="height:100px;width:100px; margin-top:10px; display:none;">

                                                                    <img class="old-image" id="old-{{ $category->id }}"
                                                                        src="{{ isset($category) && $category->image ? asset('assets/admin/img/category/' . $category->image) : '' }}"
                                                                        style="height:100px;width:100px; margin-top:10px; {{ isset($category) && $category->image ? '' : 'display:none;' }}">
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Quay lại</button>
                                                        <button type="button"
                                                            class="btn btn-primary btn-update-submit-category"
                                                            data-id="{{ $category->id }}">Chỉnh sửa</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $categories->links() }}
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
