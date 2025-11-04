@extends('layouts.admin')
@section('title', 'Thêm Flash Sale')

@section('content')
<div class="right_col" role="main">
  <div class="x_panel">
    <div class="x_title">
      <h2>Thêm đợt Flash Sale</h2>
      <div class="clearfix"></div>
    </div>

    <div class="x_content">
      <form method="POST" action="{{ route('admin.flash_sales.store') }}">
        @csrf
        <div class="form-group">
          <label>Tên chương trình</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Thời gian bắt đầu</label>
            <input type="datetime-local" name="start_time" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Thời gian kết thúc</label>
            <input type="datetime-local" name="end_time" class="form-control" required>
          </div>
        </div>

        <div class="form-group">
          <label>Trạng thái</label>
          <select name="status" class="form-control">
            <option value="1">Kích hoạt</option>
            <option value="0">Tạm tắt</option>
          </select>
        </div>

        <h5 class="mt-4">Chọn sản phẩm tham gia Flash Sale</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Chọn</th>
              <th>Tên sản phẩm</th>
              <th>Giá gốc</th>
              <th>Giá giảm</th>
              <th>Số lượng</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $product)
            <tr>
              <td><input type="checkbox" name="product_id[]" value="{{ $product->id }}"></td>
              <td>{{ $product->name }}</td>
              <td>{{ number_format($product->price) }}đ</td>
              <td><input type="number" name="discount_price[]" class="form-control" placeholder="Giá sale"></td>
              <td><input type="number" name="quantity[]" class="form-control" value="10"></td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <button class="btn btn-success mt-3">Tạo mới</button>
      </form>
    </div>
  </div>
</div>
@endsection
