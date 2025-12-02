@extends('layouts.client')

@section('title', 'Hoàn tiền đơn hàng')

@section('breadcrumb', 'Hoàn tiền đơn hàng')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin tài khoản ngân hàng</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('refund.bank-info.update', $refund->order_id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="bank_name" class="form-label">Tên ngân hàng</label>
                                <select name="bank_name" id="bank_name"
                                    class="form-select @error('bank_name') is-invalid @enderror" required>
                                    <option value="">-- Chọn ngân hàng --</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank['short'] }}"
                                            {{ old('bank_name', $refund->bank_name) == $bank['short'] ? 'selected' : '' }}>
                                            {{ $bank['full'] }} ({{ $bank['short'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="account_number" class="form-label">Số tài khoản</label>
                                <input type="text" class="form-control @error('account_number') is-invalid @enderror"
                                    id="account_number" name="account_number"
                                    value="{{ old('account_number', $refund->account_number) }}" required>
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="account_holder" class="form-label">Chủ tài khoản</label>
                                <input type="text" class="form-control @error('account_holder') is-invalid @enderror"
                                    id="account_holder" name="account_holder"
                                    value="{{ old('account_holder', $refund->account_holder) }}" required>
                                @error('account_holder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Gửi thông tin hoàn tiền</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
