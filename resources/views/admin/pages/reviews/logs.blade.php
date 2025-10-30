@extends('layouts.admin')
@section('title', 'Lịch sử xóa bình luận')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            📜 Lịch sử xóa bình luận
        </h2>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            ⬅️ Quay lại danh sách bình luận
        </a>
    </div>

    @if ($logs->isEmpty())
        <div class="alert alert-info text-center py-4 rounded shadow-sm">
            <i class="bi bi-info-circle"></i> Chưa có log xóa nào.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
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
                                    <td class="text-center fw-semibold">{{ $log->id }}</td>
                                    <td>{{ $log->review->product->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($log->review && $log->review->comment)
                                            {{ $log->review->comment }}
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
                                                <button type="submit" class="btn btn-success btn-sm px-3"
                                                    onclick="return confirm('Bạn có chắc muốn khôi phục bình luận này không?')">
                                                    🔄 Khôi phục
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
