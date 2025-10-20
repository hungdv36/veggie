@extends('layouts.admin')
@section('title', 'Thêm Sản Phẩm')

@section('content')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thêm Sản Phẩm</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_content">
                            <br />
                            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <!-- Cột trái 7/12 -->
                                    <div class="col-md-7">
                                        <div class="mb-3">
                                            <label class="form-label">Tên sản phẩm</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Nhập tên sản phẩm..." value="{{ old('name') }}" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thương hiệu</label>
                                            <input type="text" name="brand" class="form-control"
                                                placeholder="Thương hiệu..." value="{{ old('brand') }}">
                                            @error('brand')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Mô tả sản phẩm</label>
                                            <textarea name="description" id="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Cột phải 5/12 -->
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label for="categorySelect" class="form-label">Danh mục sản phẩm</label>
                                            <select id="categorySelect" name="category_id" class="form-select" required>
                                                <option value="" hidden>Category</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Hình ảnh đại diện</label>
                                            <input type="file" name="image" id="imageInput" class="d-none">
                                            <label for="imageInput"
                                                class="d-flex flex-column align-items-center justify-content-center gap-2"
                                                style="position: relative; background-color: #fff; border:2px solid #ccc; height:100px; width:100%; cursor:pointer; border-radius:6px; overflow:hidden;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                                    <path
                                                        d="M14.002 3.002a1 1 0 0 1 1 1v8.002a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4.002a1 1 0 0 1 1-1h12.002zm0-1H2a2 2 0 0 0-2 2v8.002a2 2 0 0 0 2 2h12.002a2 2 0 0 0 2-2V4.002a2 2 0 0 0-2-2z" />
                                                    <path
                                                        d="M10.648 8.646a.5.5 0 0 1 .707 0l2.647 2.647V5.5a.5.5 0 0 0-.5-.5H2.5a.5.5 0 0 0-.5.5v7.293l3.646-3.647a.5.5 0 0 1 .707 0l2.647 2.647 1.648-1.647z" />
                                                </svg>
                                                <span>Chọn 1 ảnh</span>
                                                <img id="imagePreview" src="#" alt="Preview"
                                                    style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); max-width:100%; max-height:100%; object-fit:cover; display:none; border-radius:4px;">
                                            </label>
                                            @error('image')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Album ảnh</label>
                                            <input type="file" name="images[]" id="imagesInput" class="d-none" multiple>
                                            <label for="imagesInput"
                                                style="position: relative; width:100%; min-height:120px; border:2px solid #ccc; border-radius:6px; background:#fff; cursor:pointer; overflow:auto; padding:20px; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; gap:5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                                                    <path d="M4.502 9a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z" />
                                                    <path
                                                        d="M14 3a1 1 0 0 1 1 1v8.002a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4.002a1 1 0 0 1 1-1h12zm0-1H2a2 2 0 0 0-2 2v8.002a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4.002a2 2 0 0 0-2-2z" />
                                                </svg>
                                                <span style="width:100%; text-align:center;">Chọn 1 hoặc nhiều ảnh</span>
                                                <div id="imagesPreview"
                                                    style="display:flex; flex-wrap:wrap; justify-content:center; gap:5px; margin-top:5px;">
                                                </div>
                                            </label>
                                            @error('images.*')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Đơn vị</label>
                                            <input type="text" name="unit" class="form-control"
                                                placeholder="Đơn vị..." value="{{ old('unit') }}">
                                            @error('unit')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Biến thể sản phẩm -->
                                <h5>Biến thể sản phẩm</h5>
                                <div id="variants-wrapper">
                                    @if (old('variants'))
                                        @foreach (old('variants') as $i => $variant)
                                            <div class="row g-2 mb-2 variant-row align-items-end">
                                                <!-- input old() ở đây -->
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Row mặc định khi form mới -->
                                        <div class="row g-2 mb-2 variant-row align-items-end">
                                            <div class="col-md-2">
                                                <label class="form-label">Size</label>
                                                <select name="variations[0][size_id]" class="form-select">
                                                    <option value="">Chọn size</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Màu sắc</label>
                                                <select name="variations[0][color_id]" class="form-select">
                                                    <option value="">Chọn màu</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Giá gốc</label>
                                                <input type="number" name="variations[0][price]" class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Giá khuyến mãi</label>
                                                <input type="number" name="variations[0][sale_price]"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Số lượng</label>
                                                <input type="number" name="variations[0][quantity]"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-variant">🗑</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3"> <button type="button" class="btn btn-primary btn-sm mt-2"
                                        id="add-variant-btn">+ Thêm biến thể</button>
                                </div>
                                <input type="hidden" name="status" value="in_stock">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .imagesPreview img {
                width: 80px;
                height: 80px;
                object-fit: cover;
                border-radius: 4px;
            }

            /* Khi select chưa chọn gì, áp dụng màu mờ và font nhỏ */
            #categorySelect:invalid {
                color: #5d5c5c;
                font-size: 0.85rem;
            }

            /* Khi người dùng chọn, màu và cỡ chữ bình thường */
            #categorySelect option {
                color: #000;
                font-size: 0.875rem;
                /* hoặc 1rem */
            }

            input::placeholder {
                font-size: 0.85rem;
                /* chữ nhỏ */
                color: #999;
                /* chữ mờ */
            }
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // ======= ẢNH ĐẠI DIỆN =======
                const imageInput = document.getElementById('imageInput');
                const imagePreview = document.getElementById('imagePreview');
                const imageLabel = imageInput.closest('label');

                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            imagePreview.src = event.target.result;
                            imagePreview.style.display = 'block';
                            imageLabel.querySelector('svg').style.display = 'none';
                            imageLabel.querySelector('span').style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // ======= ALBUM ẢNH =======
                const imagesInput = document.getElementById('imagesInput');
                const imagesPreview = document.getElementById('imagesPreview');
                const albumLabel = imagesInput.closest('label');

                imagesInput.addEventListener('change', function(e) {
                    imagesPreview.innerHTML = '';
                    const files = Array.from(e.target.files);
                    if (albumLabel && files.length > 0) {
                        const svg = albumLabel.querySelector('svg');
                        const span = albumLabel.querySelector('span');
                        if (svg) svg.style.display = 'none';
                        if (span) span.style.display = 'none';
                    }

                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const img = document.createElement('img');
                            img.src = event.target.result;
                            img.style.width = '80px';
                            img.style.height = '80px';
                            img.style.objectFit = 'cover';
                            img.style.borderRadius = '4px';
                            imagesPreview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                });

                // ======= BIẾN THỂ SẢN PHẨM =======
                let variantIndex = 1;

                $('#add-variant-btn').click(function() {
                    let newRow = $('.variant-row:first').clone();
                    newRow.find('input, select').each(function() {
                        let name = $(this).attr('name');
                        name = name.replace(/\d+/, variantIndex);
                        $(this).attr('name', name).val('');
                    });
                    newRow.appendTo('#variants-wrapper');
                    variantIndex++;
                });

                $(document).on('click', '.remove-variant', function() {
                    if ($('.variant-row').length > 1) {
                        $(this).closest('.variant-row').remove();
                    } else {
                        alert('Phải có ít nhất 1 biến thể!');
                    }
                });
            });
        </script>
    </div>
@endsection
