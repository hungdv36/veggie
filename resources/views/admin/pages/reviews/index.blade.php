@extends('layouts.admin')
@section('title', 'Quản lý bình luận')

@section('content')
<style>
    /* ==== TÙY CHỈNH GIAO DIỆN ==== */
    body {
        background-color: #f8f9fb;
        overflow-x: hidden;
    }

    /* Giúp nội dung không bị che bởi sidebar */
    .admin-content-wrapper {
        margin-left: 260px; /* khớp với chiều rộng sidebar */
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    /* Nếu sidebar có thể ẩn */
    @media (max-width: 992px) {
        .admin-content-wrapper {
            margin-left: 0;
            padding: 20px;
        }
    }

    .table-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }
    .table th {
        background-color: #f1f3f9;
        text-transform: uppercase;
        font-size: 13px;
        color: #555;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .rating-badge {
        background: #ffeeba;
        color: #856404;
        font-weight: 600;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 13px;
    }
    .btn-action {
        border-radius: 8px;
        padding: 6px 14px;
        transition: 0.2s;
    }
    .btn-action:hover {
        transform: scale(1.05);
    }
    .modal-content {
        border-radius: 14px;
    }
</style>

<div class="admin-content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">📋 Quản lý bình luận</h2>
        <span class="text-muted">Tổng số: <strong>{{ $reviews->total() }}</strong> bình luận</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-card">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Sản phẩm</th>
                        <th>Người dùng</th>
                        <th class="text-center">Đánh giá</th>
                        <th>Nội dung</th>
                        <th class="text-center">Ngày tạo</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="text-center fw-semibold text-secondary">{{ $review->id }}</td>
                            <td style="max-width: 220px;">{{ $review->product->name ?? 'N/A' }}</td>
                            <td>{{ $review->user->name ?? 'Ẩn danh' }}</td>
                            <td class="text-center">
                                <span class="rating-badge">{{ $review->rating }} ⭐</span>
                            </td>
                            <td style="max-width: 280px;">
                                <span class="text-muted">{{ Str::limit($review->comment, 80) }}</span>
                            </td>
                            <td class="text-center text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <!-- Nút mở modal -->
                                <button type="button" class="btn btn-danger btn-action btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $review->id }}">
                                    <i class="bi bi-trash3"></i> Xóa
                                </button>

                                <!-- Modal nhập lý do -->
                                <div class="modal fade" id="deleteModal{{ $review->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $review->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('admin.reviews.delete') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $review->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title fw-bold" id="deleteModalLabel{{ $review->id }}">
                                                        Xóa bình luận #{{ $review->id }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-warning small">
                                                        <i class="bi bi-exclamation-triangle-fill"></i> 
                                                        Hành động này không thể hoàn tác. Hãy nhập lý do xóa bên dưới.
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Lý do xóa</label>
                                                        <textarea name="reason" class="form-control" rows="3" required placeholder="Nhập lý do..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle"></i> Hủy
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash"></i> Xác nhận xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Không có bình luận nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
