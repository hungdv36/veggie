@extends('layouts.client')

@section('title', 'Hoàn trả đơn hàng')
@section('breadcrumb', 'Hoàn trả đơn hàng')

@section('content')
    <div class="card shadow-sm border-0 p-4">
        <h4 class="mb-4 fw-bold">Yêu cầu hoàn hàng</h4>

        <form action="{{ route('return-request.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- hidden -->
            <input type="hidden" name="order_item_id" value="{{ $item->id }}">

            <!-- Lý do -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Lý do hoàn hàng <span class="text-danger">*</span></label>
                <select name="reason" class="form-select" required>
                    <option value="">-- Chọn lý do --</option>
                    <option value="Sản phẩm bị lỗi">Sản phẩm bị lỗi</option>
                    <option value="Sản phẩm không đúng mô tả">Sản phẩm không đúng mô tả</option>
                    <option value="Giao sai mẫu / sai size">Giao sai mẫu / sai size</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>

            <!-- Mô tả chi tiết -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả chi tiết (nếu có)</label>
                <textarea name="detail" rows="3" class="form-control" placeholder="Hãy mô tả rõ hơn lý do hoàn hàng..."></textarea>
            </div>

            <!-- Upload hình ảnh -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Hình ảnh minh họa</label>

                <div class="border rounded p-3 text-center" style="cursor:pointer;">
                    <input type="file" name="images[]" id="imgInput" accept="image/*" multiple hidden>
                    <div class="text-secondary" onclick="document.getElementById('imgInput').click()">
                        <i class="bi bi-image fs-1"></i><br>
                        <span>Nhấn để chọn hình ảnh</span>
                    </div>
                </div>

                <!-- preview -->
                <div id="previewImages" class="d-flex gap-2 mt-3 flex-wrap"></div>
            </div>

            <!-- Upload video -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Video minh họa (tùy chọn)</label>

                <div class="border rounded p-3 text-center" style="cursor:pointer;">
                    <input type="file" name="videos[]" id="videoInput" accept="video/*" multiple hidden>
                    <div class="text-secondary" onclick="document.getElementById('videoInput').click()">
                        <i class="bi bi-camera-video fs-1"></i><br>
                        <span>Nhấn để chọn video</span>
                    </div>
                </div>

                <div id="previewVideos" class="mt-3"></div>
            </div>

            <button class="btn btn-danger w-100 py-2 fw-bold">
                Gửi yêu cầu hoàn hàng
            </button>
        </form>
    </div>


    {{-- Preview JS --}}
    <script>
        const imgInput = document.getElementById('imgInput');
        const previewImages = document.getElementById('previewImages');

        imgInput?.addEventListener('change', function() {
            previewImages.innerHTML = "";
            [...this.files].forEach(file => {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(file);
                img.width = 90;
                img.classList.add("rounded", "border");
                previewImages.appendChild(img);
            });
        });

        const videoInput = document.getElementById('videoInput');
        const previewVideos = document.getElementById('previewVideos');

        videoInput?.addEventListener('change', function() {
            previewVideos.innerHTML = "";
            [...this.files].forEach(file => {
                const vid = document.createElement("video");
                vid.src = URL.createObjectURL(file);
                vid.width = 200;
                vid.controls = true;
                vid.classList.add("rounded", "border", "me-2");
                previewVideos.appendChild(vid);
            });
        });
    </script>
@endsection
