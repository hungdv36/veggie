@extends('layouts.admin')

@section('title', 'Quản lý biến thể')

@section('content')
    <div class="container mt-4" style="max-width: 800px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thêm Biến Thể</h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success py-1 px-2 mb-3">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger py-1 px-2 mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li style="font-size: 14px;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.variants.store') }}" method="POST">
                    @csrf

                    <!-- Chọn sản phẩm 1 lần -->
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Sản phẩm</label>
                        <select name="product_id" id="product_id" class="form-select form-select-sm" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Wrapper các biến thể -->
                    <div id="variants-wrapper">
                        <div class="row g-2 mb-3 variant-row align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small">Size</label>
                                <input type="text" name="variants[0][size]" class="form-control form-control-sm"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Màu sắc</label>
                                <input type="text" name="variants[0][color]" class="form-control form-control-sm"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Số lượng</label>
                                <input type="number" name="variants[0][stock]" class="form-control form-control-sm"
                                    min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Giá (VNĐ)</label>
                                <input type="number" name="variants[0][price]" class="form-control form-control-sm"
                                    min="0" step="1" required>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-sm btn-danger remove-variant">Xóa</button>
                            </div>
                        </div>
                    </div>

                    <!-- Nút thêm biến thể -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-success" id="add-variant">Thêm biến thể khác</button>
                    </div>

                    <!-- Submit & quay lại -->
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.variants.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                        <button type="submit" class="btn btn-primary btn-sm">Thêm biến thể</button>
                    </div>
                </form>

                <!-- JS thêm/xóa dòng -->
                <script>
                    let index = 1;
                    document.getElementById('add-variant').addEventListener('click', function() {
                        const wrapper = document.getElementById('variants-wrapper');
                        const row = document.querySelector('.variant-row').cloneNode(true);

                        row.querySelectorAll('input').forEach(input => {
                            const name = input.getAttribute('name');
                            const newName = name.replace(/\d+/, index);
                            input.setAttribute('name', newName);
                            input.value = (input.type === 'number') ? 0 : '';
                        });

                        wrapper.appendChild(row);
                        index++;
                    });

                    // Xóa dòng
                    document.getElementById('variants-wrapper').addEventListener('click', function(e) {
                        if (e.target && e.target.classList.contains('remove-variant')) {
                            const row = e.target.closest('.variant-row');
                            row.remove();
                        }
                    });
                </script>
            </div>
        </div>
    </div>

    <script>
        let index = 1;
        document.getElementById('add-variant').addEventListener('click', function() {
            const wrapper = document.getElementById('variants-wrapper');
            const row = document.querySelector('.variant-row').cloneNode(true);

            row.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/\d+/, index);
                input.setAttribute('name', newName);
                input.value = (input.type === 'number') ? 0 : '';
            });

            wrapper.appendChild(row);
            index++;
        });
    </script>
@endsection
