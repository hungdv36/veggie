@extends('layouts.admin')
@section('content')

<style>
    .admin-content-wrapper {
        margin-left: 250px;
        padding: 50px 30px;
        display: flex;
        justify-content: center;
        min-height: calc(100vh - 100px);
        background: #f5f7fb;
    }

    .banner-form-card {
        width: 100%;
        max-width: 1100px;
        border-radius: 12px;
        background: #ffffff;
        padding-bottom: 25px;
    }

    .banner-form-card .card-header {
        padding: 18px 25px;
        font-size: 20px;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100% !important;
        padding: 10px 14px;
        font-size: 15px;
        border-radius: 8px;
    }

    .btn-success {
        padding: 10px 25px;
        font-size: 16px;
        border-radius: 8px;
        font-weight: 600;
    }

    /* ảnh preview */
    .preview-img {
        width: 150px;
        border-radius: 8px;
        margin-top: 10px;
        border: 1px solid #ddd;
    }
</style>

<div class="admin-content-wrapper">

    <div class="card shadow-sm banner-form-card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Sửa Banner</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.banners.update', $banner->id) }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="form-group">
                    <label>Tiêu đề</label>
                    <input type="text" name="title" class="form-control" 
                           value="{{ $banner->title }}" required>
                </div>

                <div class="form-group">
                    <label>Ảnh banner</label>
                    <input type="file" name="image" class="form-control">

                    <p class="mt-2"><b>Ảnh hiện tại:</b></p>
                    <img src="{{ asset('uploads/banners/'.$banner->image) }}" class="preview-img">
                </div>

                <div class="form-group">
                    <label>URL</label>
                    <input type="text" name="url" class="form-control" 
                           value="{{ $banner->url }}">
                </div>

                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $banner->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ $banner->status == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                <button class="btn btn-success">Cập nhật Banner</button>
            </form>
        </div>
    </div>

</div>
@endsection
