@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{ isset($category) ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới' }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="add-category"
                            action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($category))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Tên danh mục <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $category->name ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Ảnh danh mục</label>
                                <div class="custom-file-wrapper">
                                    <label for="image" class="btn btn-primary">Chọn ảnh</label>
                                    <input type="file" name="image" id="image" accept="image/*"
                                        style="display:none;">

                                    <img id="image-preview"
                                        src="{{ isset($category) && $category->image ? asset($category->image) : '' }}"
                                        alt="Ảnh xem trước"
                                        style="height: 150px; margin-top: 10px; {{ isset($category) && $category->image ? '' : 'display:none;' }}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
                                <button type="submit"
                                    class="btn btn-success">{{ isset($category) ? 'Cập nhật' : 'Thêm mới' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#image').change(function() {
                let file = this.files[0];
                let preview = $('#image-preview');

                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        preview.show(); // Hiển thị ảnh
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.attr('src', '');
                    preview.hide(); // Ẩn ảnh nếu không chọn file
                }
            });
        });
    </script>
@endsection
