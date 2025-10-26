@extends('layouts.client')

@section('title', 'Thanh toán bị hủy')

@section('breadcrumb', 'Thanh toán bị hủy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="card shadow-lg p-4">
                <div class="card-body">
                    {{ asset(
                    <h2 class="text-danger mb-3">Thanh toán bị hủy!</h2>
                    <p class="lead mb-4">Bạn đã hủy giao dịch. Vui lòng thử lại hoặc chọn phương thức khác.</p>

                    <div class="mt-4">
                        <a route(Quay lại thanh toán</a>
                        {{ route(Trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection