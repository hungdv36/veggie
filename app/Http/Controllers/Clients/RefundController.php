<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refund;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    public function bankInfo($orderId)
    {
        $refund = Refund::where('order_id', $orderId)->firstOrFail();

        $banks = [];

        // Gọi API VietQR
        $response = Http::get('https://api.vietqr.io/v2/banks');

        if ($response->successful()) {
            $json = $response->json();

            if (isset($json['data']) && is_array($json['data'])) {
                $banks = collect($json['data'])
                    ->filter(fn($item) => ($item['type'] ?? null) === 'bank')
                    ->map(function ($item) {
                        return [
                            'full'  => $item['name'],
                            'short' => $item['shortName'] ?? $item['code'] ?? $item['name'],
                        ];
                    })
                    ->toArray();
            }
        }

        // fallback khi API fail → vẫn full + short
        if (empty($banks)) {
            $banks = [
                ['full' => 'Ngân hàng TMCP Ngoại thương Việt Nam', 'short' => 'Vietcombank'],
                ['full' => 'Ngân hàng TMCP Công thương Việt Nam', 'short' => 'VietinBank'],
                ['full' => 'Ngân hàng TMCP Kỹ thương Việt Nam', 'short' => 'Techcombank'],
                ['full' => 'Ngân hàng TMCP Quân đội', 'short' => 'MB'],
                ['full' => 'Ngân hàng TMCP Á Châu', 'short' => 'ACB'],
                ['full' => 'Ngân hàng TMCP Tiên Phong', 'short' => 'TPBank'],
                ['full' => 'Ngân hàng TMCP Việt Nam Thịnh Vượng', 'short' => 'VPBank'],
                ['full' => 'Ngân hàng TMCP Sài Gòn Thương Tín', 'short' => 'Sacombank'],
                ['full' => 'Ngân hàng TMCP Đầu tư và Phát triển Việt Nam', 'short' => 'BIDV'],
            ];
        }

        return view('clients.pages.refund.bank-info', compact('refund', 'banks'));
    }
    public function updateBankInfo(Request $request, $orderId)
    {
        $refund = Refund::where('order_id', $orderId)->firstOrFail();
        $validator = Validator::make($request->all(), [
            'bank_name'      => 'required|string|max:255',
            'account_number' => ['required', 'digits_between:8,14'],
            'account_holder' => ['required', 'regex:/^\S+\s+\S+/'], // ít nhất 2 từ
        ], [
            'bank_name.required'      => 'Vui lòng chọn ngân hàng.',
            'account_number.required' => 'Vui lòng nhập số tài khoản.',
            'account_number.digits_between' => 'Số tài khoản phải gồm từ 8 đến 14 chữ số.',
            'account_holder.required' => 'Vui lòng nhập tên chủ tài khoản.',
            'account_holder.regex'    => 'Tên chủ tài khoản phải gồm ít nhất 2 từ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator) // Truyền lỗi
                ->withInput(); // Giữ dữ liệu cũ
        }

        // Cập nhật thông tin ngân hàng
        $refund->update([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'status'         => 'submitted',
        ]);

        return redirect()->route('order.show', $orderId)
            ->with('success', 'Thông tin tài khoản ngân hàng đã được gửi. Vui lòng chờ duyệt hoàn tiền.');
    }
}
