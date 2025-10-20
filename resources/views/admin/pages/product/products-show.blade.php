@extends('layouts.admin')
@section('title', 'Chi tiết sản phẩm')

@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Chi tiết sản phẩm</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <ul class="nav nav-tabs mb-3" id="productTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                    type="button" role="tab">Thông tin</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="album-tab" data-bs-toggle="tab" data-bs-target="#album"
                                    type="button" role="tab">Album</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants"
                                    type="button" role="tab">Biến thể</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ $product->image ? asset('assets/admin/img/product/' . $product->image) : asset('images/no-image.png') }}"
                                            class="img-fluid rounded" alt="{{ $product->name }}">
                                    </div>
                                    <div class="col-md-8">
                                        <p><strong>Tên:</strong> {{ $product->name }}</p>
                                        <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Không có' }}</p>
                                        <p><strong>Giá:</strong> {{ number_format($product->price) }} VNĐ</p>
                                        <p><strong>Tồn kho:</strong> {{ $product->total_stock }}</p>
                                        <p><strong>Trạng thái:</strong>
                                            @if ($product->total_stock > 0)
                                                <span class="badge bg-success">Còn hàng</span>
                                            @else
                                                <span class="badge bg-danger">Hết hàng</span>
                                            @endif
                                        </p>
                                        <p><strong>Mô tả:</strong><br></p>
                                        <p
                                            style="display: -webkit-box; -webkit-line-clamp: 6; -webkit-box-orient: vertical; overflow: hidden;">
                                            {!! nl2br(e($product->description)) !!}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="album" role="tabpanel">
                                @if ($product->images->count())
                                    <div class="row">
                                        @foreach ($product->images as $img)
                                            <div class="col-md-3 mb-2">
                                                <img src="{{ asset($img->image_path) }}" class="img-fluid rounded shadow-sm"
                                                    style="height:150px; object-fit:cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p>Chưa có ảnh nào.</p>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="variants" role="tabpanel">
                                @if ($product->variants->count())
                                    <table class="table table-bordered mt-2">
                                        <thead>
                                            <tr>
                                                <th>Size</th>
                                                <th>Màu</th>
                                                <th>Giá</th>
                                                <th>Giá KM</th>
                                                <th>Số lượng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->variants as $variant)
                                                <tr>
                                                    <td>{{ $variant->size->name ?? '' }}</td>
                                                    <td>{{ $variant->color->name ?? '' }}</td>
                                                    <td>{{ number_format($variant->price) }}</td>
                                                    <td>{{ $variant->sale_price ? number_format($variant->sale_price) : '-' }}
                                                    </td>
                                                    <td>{{ $variant->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>Chưa có biến thể.</p>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3"><i
                                class="fa fa-arrow-left"></i> Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
