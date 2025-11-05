@extends('layouts.admin')
@section('title', 'Quản lý Flash Sale')

@section('content')
<div class="right_col" role="main">
  <div class="x_panel">
    <div class="x_title d-flex justify-content-between align-items-center">
      <h2>Danh sách Flash Sale</h2>
      <a href="{{ route('admin.flash_sales.create') }}" class="btn btn-success btn-sm">+ Thêm mới</a>
      <div class="clearfix"></div>
    </div>

    <div class="x_content">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Tên chương trình</th>
            <th>Bắt đầu</th>
            <th>Kết thúc</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($flashSales as $sale)
          <tr>
            <td>{{ $sale->id }}</td>
            <td>{{ $sale->name }}</td>
            <td>{{ $sale->start_time }}</td>
            <td>{{ $sale->end_time }}</td>
            <td>
              @if($sale->isActive())
                <span class="badge badge-success">Đang diễn ra</span>
              @else
                <span class="badge badge-secondary">Tạm dừng</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.flash_sales.edit', $sale->id) }}" class="btn btn-sm btn-primary">Sửa</a>
              <form action="{{ route('admin.flash_sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa chương trình này?')">Xóa</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted">Chưa có chương trình flash sale nào.</td></tr>
          @endforelse
        </tbody>
      </table>

      <div class="d-flex justify-content-center">
        {{ $flashSales->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
