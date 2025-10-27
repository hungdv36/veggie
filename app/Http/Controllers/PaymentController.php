<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    /**
     * Tạo yêu cầu thanh toán PayPal
     */
    public function createPayment(Request $request)
    {
        try {
            // Khởi tạo PayPal Client
            $paypal = new PayPalClient;
            $paypal->setApiCredentials(config('paypal'));
            $token = $paypal->getAccessToken();

            // Kiểm tra token hợp lệ
            if (!isset($token['access_token'])) {
                dd('❌ Lỗi lấy access_token từ PayPal:', $token);
            }

            $paypal->setAccessToken($token);

            // Lấy tổng tiền từ request (VND -> USD)
            $totalAmountVND = $request->amount;
            $usdAmount = number_format($totalAmountVND / 25000, 2); // Quy đổi sang USD

            // Tạo đơn hàng PayPal
            $response = $paypal->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $usdAmount
                        ]
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('paypal.cancel'),
                    "return_url" => route('paypal.success')
                ]
            ]);

            // Chuyển hướng đến PayPal nếu thành công
            if (isset($response['links'][1]['href'])) {
                return redirect($response['links'][1]['href']);
            } else {
                return redirect()->back()->with('error', 'Không thể tạo thanh toán PayPal.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi kết nối PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý khi thanh toán thành công
     */
    public function success(Request $request)
    {
        try {
            $paypal = new PayPalClient;
            $paypal->setApiCredentials(config('paypal'));
            $token = $paypal->getAccessToken();

            // Debug lỗi access_token
            if (!isset($token['access_token'])) {
                dd('❌ Lỗi lấy access_token từ PayPal:', $token);
            }

            $paypal->setAccessToken($token);

            // Capture thanh toán
            $response = $paypal->capturePaymentOrder($request->token);

            // Debug nếu capture thất bại
            if (isset($response['error'])) {
                dd('❌ Lỗi khi capture thanh toán:', $response);
            }

            // ✅ Thanh toán thành công → Hiển thị trang success
            return view('checkout.success', compact('response'));

        } catch (\Exception $e) {
            return redirect()->route('checkout')->with('error', 'Lỗi PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý khi thanh toán bị hủy
     */
    public function cancel()
    {
        return view('checkout.cancel');
    }
}
