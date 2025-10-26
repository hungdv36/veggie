@extends('layouts.client')

@section('title', 'Thanh toán thành công')

@section('breadcrumb', 'Thanh toán thành công')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="card shadow-lg p-4">
                <div class="card-body">
                    <img src="{{ asset('assets/clients/img/icons/success.png') }}" alt="Successh công!</h2>
                    <p class="lead mb-4">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn sẽ được xử lý sớm.</p>

                    @if(isset($response['id']))
                        <p><strong>Mã giao dịch:</strong> {{ $response['id'] }}</p>
                    @endif

                    @if(isset($response['status']))
                        <p><strong>Trạng thái:</strong> {{ ucfirst(strtolower($response['status'])) }}</p>
                    @endif

                    <div class="mt-4">
                        {{ route(Quay về trang chủ</a>
                        {{ route(Xem đơn hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
