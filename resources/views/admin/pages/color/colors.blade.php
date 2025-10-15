@extends('layouts.admin')

@section('title', 'Quản lý biến thể')

@section('content')

    <div class="right_col" role="main">
        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success py-1 px-2 mb-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-title mb-4">
            <div class="title_left">
                <div class="page-title">
                    <h3>
                        <small>Quản lý biến thể</small>
                        <strong class="text-purple" style="font-style: italic;">Màu sắc</strong>
                    </h3>
                </div>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.colors.add') }}" class="btn btn-success">Thêm màu sắc</a>
            </div>
        </div>


        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>Danh sách biến thể/<strong class="text-purple" style="font-style: italic;">Màu
                                sắc</strong></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0" style="text-align: center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($colors as $color)
                                        <tr>
                                            <td>{{ $color->id }}</td>
                                            <td>{{ $color->name }}</td>
                                            <td style="text-align: center;">
                                                <div style="display: flex; align-items: center; justify-content: center;">
                                                    <!-- Ô màu -->
                                                    <div
                                                        style="width: 25px; height: 25px; background-color: {{ $color->hex_code }}; border: 1px solid #ccc; margin-right: 8px;">
                                                    </div>
                                                    <!-- Mã màu -->
                                                    <span>{{ $color->hex_code }}</span>
                                                </div>
                                            </td>
                                            <td
                                                style="display: flex; gap: 5px; justify-content: center; align-items: center;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalUpdate-{{ $color->id }}"
                                                    title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-delete-color"
                                                    data-id="{{ $color->id }}" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal cập nhật biến thể -->
                                        <div class="modal fade" id="modalUpdate-{{ $color->id }}" tabindex="-1"
                                            aria-labelledby="colorModalLabel-{{ $color->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="colorModalLabel-{{ $color->id }}">
                                                            Chỉnh sửa màu
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Đóng"></button>
                                                    </div>

                                                    <form action="{{ route('admin.colors.update') }}" method="POST">
                                                        @csrf
                                                        <!-- Hidden input chứa ID biến thể -->
                                                        <input type="hidden" name="color_id" value="{{ $color->id }}">

                                                        <div class="modal-body">

                                                            <div class="mb-3">
                                                                <label class="form-label">Màu sắc</label>
                                                                <input type="text" name="color" class="form-control"
                                                                    value="{{ $color->name }}" required>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Mã màu</label>
                                                                    <input type="color" name="hex_code"
                                                                        class="form-control form-control-color"
                                                                        value="{{ $color->hex_code }}" required>
                                                                </div>
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
                                {{ $colors->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
