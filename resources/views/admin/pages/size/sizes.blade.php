@extends('layouts.admin')

@section('title', 'Quản lý kích thước')

@section('content')
    <div class="right_col" role="main">
        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success py-1 px-2 mb-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Lỗi validate --}}
        @if ($errors->any())
            <div class="alert alert-danger py-1 px-2 mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li style="font-size: 14px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="page-title mb-4">
            <div class="title_left">
                <div class="page-title">
                    <h3>
                        <small>Quản lý biến thể</small>
                        <strong class="text-purple" style="font-style: italic;">Kích thước</strong>
                    </h3>
                </div>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.sizes.add') }}" class="btn btn-success">Thêm kích thước</a>
            </div>
        </div>


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>Danh sách biến thể/<strong class="text-purple" style="font-style: italic;">Kích
                                thước</strong></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0" style="text-align: center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Size</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sizes as $size)
                                        <tr>
                                            <td>{{ $size->id }}</td>
                                            <td>{{ $size->name }}</td>
                                            <td
                                                style="display: flex; gap: 5px; justify-content: center; align-items: center;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalUpdate-{{ $size->id }}"
                                                    title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-size"
                                                    data-id="{{ $size->id }}" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal cập nhật biến thể -->
                                        <div class="modal fade" id="modalUpdate-{{ $size->id }}" tabindex="-1"
                                            aria-labelledby="sizeModalLabel-{{ $size->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="sizeModalLabel-{{ $size->id }}">
                                                            Chỉnh sửa kích thước
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Đóng"></button>
                                                    </div>

                                                    <form action="{{ route('admin.sizes.update') }}" method="POST">
                                                        @csrf
                                                        <!-- Hidden input chứa ID biến thể -->
                                                        <input type="hidden" name="size_id" value="{{ $size->id }}">

                                                        <div class="modal-body">

                                                            <div class="mb-3">
                                                                <label class="form-label">Size</label>
                                                                <input type="text" name="size" class="form-control"
                                                                    value="{{ $size->name }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Hủy</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                Chỉnh sửa
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-3">
                                {{ $sizes->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
