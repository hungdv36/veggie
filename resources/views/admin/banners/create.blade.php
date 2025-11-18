@extends('layouts.admin')
@section('content')
<style>
    /* Nơi content né sidebar và căn giữa */
    .admin-content-wrapper {
        margin-left: 250px;
        padding: 50px 30px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: calc(100vh - 100px);
        background: #f5f7fb; 
    }

    /* Card form */
    .banner-form-card {
        width: 100%;
        max-width: 1100px; /* mở rộng hơn */
        border-radius: 12px;
        background: #ffffff;
        padding-bottom: 25px;
    }

    .banner-form-card .card-header {
        padding: 18px 25px;
        font-size: 20px;
        font-weight: bold;
    }

    /* Chỉnh khoảng cách form đẹp hơn */
    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    /* Input full width thật sự */
    .form-control {
        width: 100% !important;
        padding: 10px 14px;
        font-size: 15px;
        border-radius: 8px;
    }

    /* Nút đẹp hơn */
    .btn-success {
        padding: 10px 25px;
        font-size: 16px;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Để form nhìn thoáng hai bên */
    .card-body {
        padding: 25px 35px;
    }
</style>
<div class="admin-content-wrapper">

    <div class="card shadow-sm banner-form-card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Thêm Banner</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label>Tiêu đề</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Ảnh banner</label>
                    <input type="file" name="image" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>URL</label>
                    <input type="text" name="url" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label>Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>

                <button class="btn btn-success px-4">Tạo Banner</button>
            </form>
        </div>
    </div>

</div>
@endsection
