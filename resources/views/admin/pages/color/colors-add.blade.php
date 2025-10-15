@extends('layouts.admin')

@section('title', 'Thêm màu sắc')

@section('content')
    <div class="container mt-4" style="max-width: 800px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thêm màu sắc</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('admin.colors.store') }}" method="POST">
                    @csrf

                    <div class="card shadow-sm border-0">

                        <div class="card-body">
                            <!-- Danh sách các màu -->
                            <div id="colors-wrapper">
                                <div class="row g-2 mb-3 color-row align-items-end">
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Tên màu sắc</label>
                                        <input type="text" name="colors[0][name]" class="form-control form-control-sm"
                                            placeholder="Nhập tên màu (VD: Đỏ, Xanh...)" required>
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Mã màu (HEX)</label>
                                        <input type="color" name="colors[0][hex_code]"
                                            class="form-control form-control-color w-100" title="Chọn mã màu" required>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-color px-3">x</button>
                                    </div>

                                </div>
                            </div>

                            <!-- Nút thêm màu -->
                            <div class="mt-2">
                                <button type="button" id="add-color" class="btn btn-success btn-sm">
                                    + Thêm màu
                                </button>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                            <button type="submit" class="btn btn-primary btn-sm">Lưu màu sắc</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            let colorIndex = 1;

            // Thêm màu mới
            document.getElementById('add-color').addEventListener('click', function() {
                const wrapper = document.getElementById('colors-wrapper');

                const row = document.createElement('div');
                row.classList.add('row', 'g-2', 'mb-3', 'color-row', 'align-items-end');

                row.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tên màu sắc</label>
                    <input type="text" name="colors[${colorIndex}][name]" 
                        class="form-control form-control-sm" 
                        placeholder="Nhập tên màu (VD: Đỏ, Xanh...)" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Mã màu (HEX)</label>
                    <input type="color" name="colors[${colorIndex}][hex_code]" 
                        class="form-control form-control-color" 
                        title="Chọn mã màu" 
                        value="#000000" 
                        required>
                </div>

                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-danger remove-color w-100">X</button>
                </div>
            `;

                wrapper.appendChild(row);
                colorIndex++;
            });

            // Xóa màu
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-color')) {
                    e.target.closest('.color-row').remove();
                }
            });
        </script>
    @endpush

@endsection
