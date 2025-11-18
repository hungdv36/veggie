@extends('layouts.admin')

@section('content')

<style>
    /* Phần này ép table không bị thu gọn */
    .admin-content table {
        min-width: 1000px;
    }
    /* Cho phép kéo ngang */
    .admin-content {
        overflow-x: auto !important;
        padding-bottom: 20px;
        width: 100%;
        display: block;
        padding-left: 230px;
    }

    /* Hiển thị ảnh */
    .admin-content table td img {
        width: 80px;
        height: auto;
        border-radius: 4px;
    }
</style>


<div class="admin-content">
   
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tiêu đề</th>
                <th>URL</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
             
<th class="d-flex justify-content-between align-items-center">
    Hành động
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
        + Tạo Banner
    </a>
</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($banners as $banner)
         <tr>
    <td>
        <img src="{{ asset('uploads/banners/' . $banner->image) }}" width="80">
    </td>
    <td>{{ $banner->title }}</td>
    <td>{{ $banner->url }}</td>
    <td>{{ $banner->status ? 'Hiển thị' : 'Ẩn' }}</td>
    <td>{{ $banner->created_at->format('d/m/Y') }}</td>
    <td>
        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-success">Sửa</a>
        <a href="{{ route('admin.banners.delete', $banner->id) }}" class="btn btn-sm btn-danger"
           onclick="return confirm('Xóa banner?')">Xóa</a>
    </td>
</tr>

            @endforeach
        </tbody>
    </table>
</div>

@endsection
