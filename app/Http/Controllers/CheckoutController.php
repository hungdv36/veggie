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

    public function listCoupons()
    {
        $user = Auth::user();

        // Lấy tổng tiền giỏ hàng
        $cartItems = CartItem::where('user_id', $user->id)
            ->with('product', 'variant')
            ->get();

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->variant->sale_price ?? $item->product->price);
        }) + 25000;

        // Lấy TẤT CẢ mã giảm giá
        $coupons = Coupon::orderBy('id', 'desc')->get();

        $result = $coupons->map(function ($c) use ($cartTotal) {

            // Kiểm tra mã có dùng được không
            $usable = true;
            $reason = '';

            if ($c->status != 1) {
                $usable = false;
                $reason = 'Mã đã tắt';
            } elseif (now()->lt($c->start_date)) {
                $usable = false;
                $reason = 'Chưa đến ngày áp dụng';
            } elseif (now()->gt($c->end_date)) {
                $usable = false;
                $reason = 'Mã đã hết hạn';
            } elseif ($c->used_count >= $c->usage_limit) {
                $usable = false;
                $reason = 'Hết lượt sử dụng';
            } elseif ($cartTotal < ($c->min_order ?? 0)) {
                $usable = false;
                $reason = 'Không đạt giá trị tối thiểu';
            }

            return [
                'code' => $c->code,
                'name' => $c->name,
                'value_text' => $c->type === 'percent'
                    ? $c->value . '%'
                    : number_format($c->value, 0, ',', '.') . ' đ',
                'min_text' => number_format($c->min_order ?? 0, 0, ',', '.') . ' đ',
                'usable' => $usable,
                'reason' => $reason,
            ];
        });

        return response()->json($result);
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
            // Lấy Flash Sale hiện tại (nếu có)
            $flashSale = \App\Models\FlashSale::with('items')
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->first();

            $totalAmount = 0;

            // Tạo đơn hàng
            do {
                $orderCode = 'DH-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            } while (Order::where('order_code', $orderCode)->exists());

            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $request->address_id,
                'total_amount' => 0, // cập nhật sau
                'status' => 'pending',
                'order_code' => $orderCode,
            ]);

            // Xử lý từng item trong giỏ
            foreach ($cartItems as $item) {
                $product = $item->product;
                $variant = $item->variant;

                $basePrice = $variant->price ?? $product->price;
                $price = $basePrice;

                // 1. Tính giá Flash Sale nếu có
                if ($flashSale) {
                    $flashItem = $flashSale->items->firstWhere('product_id', $product->id);
                    if ($flashItem) {
                        $price = round($basePrice * (1 - $flashItem->discount_price / 100));
                    }
                } else {
                    // 2. Dùng sale_price nếu không có Flash Sale
                    if ($variant && $variant->sale_price > 0) {
                        $price = $variant->sale_price;
                    } elseif (!$variant && $product->sale_price > 0) {
                        $price = $product->sale_price;
                    }
                }

                $totalAmount += $price * $item->quantity; // 3. Tính totalAmount

                // 4. Lưu giá đúng vào OrderItem
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                ]);

                // Giảm tồn kho giữ nguyên logic cũ
                if ($item->variant_id) {
                    $item->variant->decrement('quantity', $item->quantity);
                } else {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            // Cộng phí vận chuyển (cũ)
            $totalAmount += 25000;

            // Cập nhật tổng tiền đơn hàng
            $order->update(['total_amount' => $totalAmount]);

            // Giữ nguyên phần coupon, payment, xóa giỏ hàng, redirect
            if ($request->coupon_id) {
                $coupon = Coupon::find($request->coupon_id);
                if ($coupon) {
                    $discountAmount = $coupon->type === 'fixed'
                        ? $coupon->value
                        : $totalAmount * $coupon->value / 100;
                    $totalAmount -= $discountAmount;

                    DB::table('order_coupons')->insert([
                        'order_id' => $order->id,
                        'coupon_id' => $coupon->id,
                        'discount_amount' => $discountAmount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $coupon->increment('used');
                }
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $totalAmount,
                'status' => 'pending',
            ]);

            DB::commit();

            // Redirect theo payment method
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
        Log::info('Request voucher:', $request->all());

        $couponCode = trim($request->coupon_code);
        $user = auth()->user();
        $shipFee = 25000; // phí ship cố định (nếu có thể thay đổi tùy logic)

        // Tìm coupon hợp lệ
        $coupon = Coupon::where('code', $couponCode)
            ->where('status', 1)
            ->where('start_date', '<=', now()->timezone('Asia/Ho_Chi_Minh'))
            ->where('end_date', '>=', now()->timezone('Asia/Ho_Chi_Minh'))
            ->first();

        Log::info('Coupon tìm được:', ['coupon' => $coupon]);

        if (!$coupon) {
            return response()->json(['error' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'], 400);
        }

        // Kiểm tra lượt dùng
        Log::info('Usage check:', ['used' => $coupon->used, 'limit' => $coupon->usage_limit]);
        if ($coupon->used >= $coupon->usage_limit) {
            return response()->json(['error' => 'Mã giảm giá đã hết lượt sử dụng'], 400);
        }

        // Lấy giỏ hàng user
        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Giỏ hàng trống'], 400);
        }

        // Tính tổng tiền sản phẩm
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $price = $item->variant->sale_price ?? $item->product->price;
            $totalAmount += ($price * $item->quantity);
        }

        // Check min order
        if ($totalAmount < $coupon->min_order) {
            return response()->json([
                'error' => 'Đơn hàng chưa đạt mức tối thiểu để áp dụng mã giảm giá'
            ], 400);
        }

        // Tính discount chỉ trên tiền sản phẩm
        $discount = 0;
        if ($coupon->type === 'amount') {
            $discount = $coupon->value;
        } elseif ($coupon->type === 'percent') {
            $discount = ($totalAmount * $coupon->value) / 100;
        }

        // Không để discount > tổng tiền sản phẩm
        if ($discount > $totalAmount) {
            $discount = $totalAmount;
        }

        // Tổng tiền sau khi áp voucher + phí ship
        $totalAfterDiscount = ($totalAmount - $discount) + $shipFee;

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'total_after_discount' => $totalAfterDiscount,
            'ship_fee' => $shipFee,
            'message' => 'Áp dụng mã giảm giá thành công'
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
