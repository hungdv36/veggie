@extends('layouts.admin')
@section('title', 'Sản phẩm đã xóa')

@section('content')
    <div class="right_col" role="main">
        <div class="page-title mb-4">
            <div class="title_left">
                <h3>Sản phẩm đã xóa</h3>
            </div>
            <div class="title_right text-right">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <span>Thùng rác sản phẩm</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Giá</th>
                                        <th>Tồn kho</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $index => $product)
                                        <tr id="row-{{ $product->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if ($product->image)
                                                    <img src="{{ asset('assets/admin/img/product/' . $product->image) }}"
                                                        alt="{{ $product->name }}"
                                                        style="height:100px;width:100px; object-fit:cover;">
                                                @else
                                                    <img src="{{ asset('assets/img/product/default.png') }}" alt="Default"
                                                        width="80">
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name ?? 'Không có' }}</td>
                                            <td>
                                                @if ($product->variants->count())
                                                    {{ number_format($product->variants->min('price')) }} -
                                                    {{ number_format($product->variants->max('price')) }}
                                                @else
                                                    {{ number_format($product->price) }}
                                                @endif
                                            </td>
                                            <td>{{ $product->stock }}</td>
                                            <td>{{ $product->status == 'active' ? 'Còn hàng' : 'Hết hàng' }}</td>
                                            <td style="display:flex; gap:5px; justify-content:center;">
                                                <button type="button" class="btn btn-sm btn-success btn-restore-product"
                                                    data-id="{{ $product->id }}">
                                                    <i class="fa fa-undo"></i> Khôi phục
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-force-delete-product"
                                                    data-id="{{ $product->id }}">
                                                    <i class="fa fa-trash"></i> Xóa vĩnh viễn
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // 🔄 KHÔI PHỤC SẢN PHẨM
            $(document).on('click', '.btn-restore-product', function() {
                const id = $(this).data('id');

                if (!confirm('Bạn có chắc muốn khôi phục sản phẩm này không?')) return;

                $.ajax({
                    url: `{{ route('admin.products.restore') }}`, // KHÔNG dùng /${id} nữa
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: id
                    },
                    success: function(res) {
                        if (res.status) {
                            alert('✅ ' + res.message);
                            $(`#row-${id}`).fadeOut(400, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('⚠️ ' + (res.message || 'Khôi phục thất bại.'));
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('❌ Có lỗi xảy ra khi khôi phục sản phẩm.');
                    }
                });
            });
            // 🗑️ XÓA VĨNH VIỄN SẢN PHẨM
            $(document).on('click', '.btn-force-delete-product', function() {
                const id = $(this).data('id');

                if (!confirm(
                        '⚠️ Bạn có chắc muốn xóa VĨNH VIỄN sản phẩm này không?\nHành động này KHÔNG THỂ hoàn tác!'
                        )) return;

                $.ajax({
                    url: `{{ route('admin.products.forceDelete') }}`, // dùng route có sẵn
                    type: 'POST', // vì route hiện tại dùng POST
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: id
                    },
                    success: function(res) {
                        if (res.status) {
                            alert('🗑️ ' + res.message);
                            $(`#row-${id}`).fadeOut(400, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('⚠️ ' + (res.message || 'Không thể xóa vĩnh viễn.'));
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('❌ Lỗi khi xóa vĩnh viễn sản phẩm.');
                    }
                });
            });

        });
    </script>
@endpush
