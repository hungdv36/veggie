@extends('layouts.admin')

@section('title', 'Thêm kích thước')

@section('content')
    <div class="container mt-4" style="max-width: 800px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thêm kích thước</h5>
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

                <form action="{{ route('admin.sizes.store') }}" method="POST">
                    @csrf

                    <div id="sizes-wrapper">
                        <div class="row g-2 mb-3 size-row align-items-end">
                            <div class="col-md-10">
                                <label class="form-label fw-semibold">Tên kích thước</label>
                                <input type="text" name="sizes[0]" class="form-control form-control-sm"
                                    placeholder="Nhập kích thước" required>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-sm btn-danger remove-size">X</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="button" id="add-size" class="btn btn-success btn-sm">+ Thêm kích thước</button>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                        <button type="submit" class="btn btn-primary btn-sm">Lưu kích thước</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let sizeIndex = 1;

            // Thêm kích thước mới
            document.getElementById('add-size').addEventListener('click', function() {
                const wrapper = document.getElementById('sizes-wrapper');

                const row = document.createElement('div');
                row.classList.add('row', 'g-2', 'mb-3', 'size-row', 'align-items-end');

                row.innerHTML = `
                    <div class="col-md-10">
                        <label class="form-label fw-semibold">Tên kích thước</label>
                        <input type="text" name="sizes[${sizeIndex}]" class="form-control form-control-sm" placeholder="Nhập kích thước" required>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-sm btn-danger remove-size">X</button>
                    </div>
                    `;


                wrapper.appendChild(row);
                sizeIndex++;
            });

            // Xóa kích thước
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-size')) {
                    e.target.closest('.size-row').remove();
                }
            });
        </script>
    @endpush

@endsection
