@extends('layouts.admin')
@section('title', 'Lịch sử xóa bình luận')

@section('content')
<style>
    /* ==== GIAO DIỆN CHUNG ==== */
    body {
        background-color: #f8f9fb;
        overflow-x: hidden;
    }

    .admin-content-wrapper {
        margin-left: 260px; /* Khớp với chiều rộng sidebar */
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    @media (max-width: 992px) {
        .admin-content-wrapper {
            margin-left: 0;
            padding: 20px;
        }
    }

    /* ==== CARD & BẢNG ==== */
    .card {
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table th {
        background-color: #f1f3f9;
        text-transform: uppercase;
        font-size: 13px;
        color: #555;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f9fbff;
        transition: 0.2s;
    }

    /* ==== NÚT & BADGE ==== */
    .btn-action {
        border-radius: 8px;
        padding: 6px 14px;
        transition: 0.2s;
    }

    .btn-action:hover {
        transform: scale(1.05);
    }

    .badge.bg-primary {
        background: linear-gradient(90deg, #007bff, #0056b3);
        box-shadow: 0 2px 6px rgba(0, 123, 255, 0.2);
    }

    /* ==== ALERT ==== */
    .alert-info {
        background: #eef6ff;
        color: #0c63e4;
        border: 1px solid #b6d4fe;
    }
</style>

<div class="admin-content-wrapper">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            📜 Lịch sử xóa bình luận
        </h2>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-action">
            <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
        </a>
    </div>

    <!-- NỘI DUNG CHÍNH -->
    @if ($logs->isEmpty())
        <div class="alert alert-info text-center py-4 rounded shadow-sm">
            <i class="bi bi-info-circle"></i> Chưa có log xóa nào.
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Sản phẩm</th>
                                <th style="width: 30%">Nội dung bình luận</th>
                                <th>Người xóa</th>
                                <th>Lý do xóa</th>
                                <th>Ngày xóa</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td class="text-center fw-semibold text-secondary">{{ $log->id }}</td>
                                    <td>{{ $log->review->product->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($log->review && $log->review->comment)
                                            <span class="text-muted">{{ Str::limit($log->review->comment, 100) }}</span>
                                        @else
                                            <span class="text-muted fst-italic">[Đã xóa]</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">
                                            {{ $log->admin->name ?? 'Không xác định' }}
                                        </span>
                                    </td>
                                    <td>{{ $log->reason }}</td>
                                    <td class="text-center text-muted">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($log->review && $log->review->trashed())
                                            <form action="{{ route('admin.reviews.restore', $log->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm btn-action"
                                                    onclick="return confirm('Bạn có chắc muốn khôi phục bình luận này không?')">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted fst-italic">Đã khôi phục</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
