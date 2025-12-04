<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ShippingAddress;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // Trang thanh toán
    public function index()
    {
        $user = Auth::user();
        $addresses = ShippingAddress::where('user_id', $user->id)->get();
        $defaultAddress = $addresses->where('default', 1)->first();

        if ($addresses->isEmpty()) {
            toastr()->info('Bạn chưa có địa chỉ giao hàng, vui lòng thêm trong bước thanh toán.');
            $defaultAddress = null;
        }

        $cartItems = CartItem::where('user_id', $user->id)->with('product', 'variant')->get();
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * ($item->variant->sale_price ?? $item->product->price));

        return view('clients.pages.checkout', compact('addresses', 'defaultAddress', 'cartItems', 'totalPrice'));
    }

    // Lấy địa chỉ
    public function getAddress(Request $request)
    {
        $address = ShippingAddress::where('id', $request->address_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy địa chỉ!']);
        }

        return response()->json(['success' => true, 'data' => $address]);
    }

    // Đặt hàng
    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product', 'variant')->get();

        if ($cartItems->isEmpty()) {
            toastr()->error('Giỏ hàng trống!');
            return redirect()->route('cart');
        }

        DB::beginTransaction();
        try {
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->quantity * ($item->variant->sale_price ?? $item->product->price);
            }) + 25000;

            $discountAmount = 0;
            if ($request->coupon_id) {
                $coupon = Coupon::find($request->coupon_id);
                if ($coupon) {
                    $discountAmount = $coupon->type === 'fixed'
                        ? $coupon->value
                        : $totalAmount * $coupon->value / 100;
                    $totalAmount -= $discountAmount;
                }
            }

            do {
                $orderCode = 'DH-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            } while (Order::where('order_code', $orderCode)->exists());

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $request->address_id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_code' => $orderCode,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->variant->sale_price ?? $item->product->price,
                ]);
            }

            // Lưu vào order_coupons nếu có
            if (isset($coupon)) {
                DB::table('order_coupons')->insert([
                    'order_id' => $order->id,
                    'coupon_id' => $coupon->id,
                    'discount_amount' => $discountAmount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Tăng số lượt dùng
                $coupon->increment('used');
            }

            // Tạo thanh toán
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $totalAmount,
                'status' => 'pending',
            ]);

            DB::commit();

            // Nếu là MoMo hoặc PayPal
            if ($request->payment_method === 'momo') {
                return response()->json([
                    'redirect' => route('checkout.momo', ['order_id' => $order->id, 'amount' => $totalAmount])
                ]);
            }

            if ($request->payment_method === 'paypal') {
                return response()->json([
                    'redirect' => route('checkout.paypal', ['order_id' => $order->id, 'amount' => $totalAmount])
                ]);
            }

            // COD
            CartItem::where('user_id', $user->id)->delete();
            toastr()->success('Đặt hàng thành công!');
            return redirect()->route('account');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            toastr()->error('Có lỗi xảy ra: ' . $e->getMessage());
            return redirect()->route('checkout');
        }
    }
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $user = Auth::user();

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('status', 1)
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return response()->json(['error' => 'Mã giảm giá đã hết hiệu lực, vui lòng chọn mã giảm giá khác'], 400);
        }

        if ($coupon->usage_limit <= $coupon->used_count) {
            return response()->json(['error' => 'Mã giảm giá đã hết lượt sử dụng'], 400);
        }

        $cartItems = CartItem::where('user_id', $user->id)->with('product', 'variant')->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Giỏ hàng trống'], 400);
        }

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->variant->sale_price ?? $item->product->price);
        }) + 25000;

        if ($cartTotal < ($coupon->min_order ?? 0)) {
            return response()->json(['error' => 'Đơn hàng phải tối thiểu ' . number_format($coupon->min_order, 0, ',', '.') . ' đ để áp dụng mã này'], 400);
        }
        $discountAmount = $coupon->type === 'fixed'
            ? $coupon->value
            : $cartTotal * $coupon->value / 100;

        $newTotal = $cartTotal - $discountAmount;

        return response()->json([
            'success' => true,
            'coupon_id' => $coupon->id,
            'discount_amount' => $discountAmount,
            'new_total' => $newTotal
        ]);
    }

    public function handlePayPal(Request $request)
    {
        $amount = $request->input('amount');
        if (!$amount || $amount <= 0) {
            return response()->json(['error' => 'Invalid amount'], 400);
        }

        return response()->json(['redirect_url' => route('paypal.success')]);
    }

    // Gọi MoMo theo order_id thay vì session
    public function handleMoMo(Request $request)
    {
        try {
            $orderIdRaw = $request->order_id;
            $amount = (string) intval($request->amount);

            if (!$orderIdRaw || !$amount) {
                return response()->json(['error' => 'Thiếu thông tin đơn hàng.'], 400);
            }

            $order = Order::find($orderIdRaw);
            if (!$order) {
                return response()->json(['error' => 'Không tìm thấy đơn hàng.'], 400);
            }

            // ✅ orderId duy nhất
            $orderId = 'ORDER-' . $orderIdRaw . '-' . time();

            // ✅ Config MoMo Sandbox
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = "MOMO";
            $accessKey = "F8BBA842ECF85";
            $secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";

            $orderInfo = "Thanh toán đơn hàng #" . $orderIdRaw;
            $redirectUrl = route('momo.return', ['order_id' => $order->id]);
            $ipnUrl = route('momo.return', ['order_id' => $order->id]);
            $requestId = (string) time();
            $requestType = "payWithATM";
            $extraData = base64_encode(json_encode(['order_id' => $orderId]));

            // 🔒 Đúng thứ tự tham số theo tài liệu MoMo
            $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";

            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "MoMo Test",
                'storeId' => "MoMoStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
            ];

            Log::info('🟢 MoMo request', $data);

            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);
            $result = curl_exec($ch);
            curl_close($ch);

            $jsonResult = json_decode($result, true);
            Log::info('🟣 MoMo response', $jsonResult ?? []);

            if (isset($jsonResult['payUrl'])) {
                return response()->json(['redirect_url' => $jsonResult['payUrl']]);
            }

            // ❌ Nếu thất bại
            $errorMessage = $jsonResult['message'] ?? 'Không rõ nguyên nhân';
            $errorCode = $jsonResult['resultCode'] ?? 'N/A';

            Log::error("❌ MoMo lỗi: [$errorCode] $errorMessage", $jsonResult ?? []);

            return response()->json([
                'error' => true,
                'code' => $errorCode,
                'message' => "Không thể khởi tạo thanh toán MoMo ($errorMessage)",
            ], 400);
        } catch (\Exception $e) {
            Log::error('🔥 MoMo exception: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }





    // Kết quả trả về từ MoMo
    public function momoReturn(Request $request)
    {
        $resultCode = $request->resultCode;
        $message = $request->message ?? null;
        $transId = $request->transId ?? null;
        $extraData = $request->extraData ? json_decode(base64_decode($request->extraData), true) : [];
        $orderIdRaw = $request->order_id ?? ($extraData['order_id'] ?? null);

        // Giải mã order_id thực tế
        if ($orderIdRaw && str_starts_with($orderIdRaw, 'ORDER-')) {
            $orderId = (int) preg_replace('/[^0-9]/', '', $orderIdRaw);
        } else {
            $orderId = (int) $orderIdRaw;
        }

        if (!$orderId) {
            toastr()->error('Không xác định được đơn hàng sau khi thanh toán MoMo.');
            return redirect()->route('checkout');
        }

        $order = Order::find($orderId);
        if (!$order) {
            toastr()->error('Đơn hàng không tồn tại.');
            return redirect()->route('checkout');
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if ($resultCode == 0) {
            DB::transaction(function () use ($order, $payment, $transId) {
                $order->update(['status' => 'pending']);
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $transId,
                    'paid_at' => now(),
                ]);

                CartItem::where('user_id', $order->user_id)->delete();
            });

            toastr()->success('Thanh toán MoMo thành công!');
            return redirect()->route('account');
        } else {
            $order->update(['status' => 'cancelled']);
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'transaction_id' => $transId,
                ]);
            }

            // \Log::warning('MoMo thất bại', [
            //     'order_id' => $orderId,
            //     'resultCode' => $resultCode,
            //     'message' => $message,
            // ]);

            toastr()->error('Thanh toán MoMo thất bại hoặc bị hủy!');
            return redirect()->route('checkout');
        }
    }
}
