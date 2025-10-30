@extends('layouts.admin')
@section('title', 'Quản lý bình luận')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Danh sách bình luận</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Người dùng</th>
                <th>Đánh giá</th>
                <th>Bình luận</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review->id }}</td>
                <td>{{ $review->product->name ?? 'N/A' }}</td>
                <td>{{ $review->user->name ?? 'Ẩn danh' }}</td>
                <td>{{ $review->rating }} ⭐</td>
                <td>{{ $review->comment }}</td>
                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <!-- Nút mở modal -->
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $review->id }}">
                        Xóa
                    </button>

                    <!-- Modal nhập lý do -->
                    <div class="modal fade" id="deleteModal{{ $review->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $review->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('admin.reviews.delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $review->id }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $review->id }}">Xóa bình luận #{{ $review->id }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Bạn có chắc chắn muốn xóa bình luận này?</p>
                                        <div class="form-group">
                                            <label>Lý do xóa</label>
                                            <textarea name="reason" class="form-control" rows="3" required placeholder="Nhập lý do..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $reviews->links() }}
</div>
@endsection

