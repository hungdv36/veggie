@extends('layouts.client')

@section('title', 'Flash Sale')
@section('breadcrumb', 'Flash Sale')
@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">🔥 Flash Sale Đang Diễn Ra!</h2>

    @if($flashSale)
        <div class="countdown mb-4 text-center fs-5 fw-bold" 
             data-end="{{ $flashSale->end_time }}">
            Kết thúc sau: <span id="countdown"></span>
        </div>

        <div class="row">
            @foreach($flashSale->items as $item)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                             class="card-img-top" alt="{{ $item->product->name }}">
                        <div class="card-body text-center">
                            <h6>{{ $item->product->name }}</h6>
                            <p class="text-danger fw-bold">
                                {{ number_format($item->discount_price) }}đ
                                <del class="text-muted small">
                                    {{ number_format($item->product->price) }}đ
                                </del>
                            </p>
                            <a href="{{ route('products.detail', $item->product->slug) }}" 
                               class="btn btn-outline-primary btn-sm">Xem ngay</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-muted">Hiện chưa có chương trình Flash Sale nào!</p>
    @endif
</div>

<script>
    const endTime = new Date(document.querySelector('.countdown').dataset.end).getTime();
    const countdown = document.getElementById('countdown');
    setInterval(() => {
        const now = new Date().getTime();
        const diff = endTime - now;
        if (diff <= 0) {
            countdown.textContent = "Đã kết thúc!";
            return;
        }
        const h = Math.floor(diff / (1000 * 60 * 60));
        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((diff % (1000 * 60)) / 1000);
        countdown.textContent = `${h}h ${m}m ${s}s`;
    }, 1000);
</script>
@endsection
