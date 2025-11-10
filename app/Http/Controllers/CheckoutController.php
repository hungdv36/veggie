<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ShippingAddress;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = ShippingAddress::where('user_id', $user->id)->get();
        $defaultAddress = $addresses->where('default', 1)->first();
        if ($addresses->isEmpty()) {
            toastr()->info('Bạn chưa có địa chỉ giao hàng, vui lòng thêm trong bước thanh toán.');
            $defaultAddress = null;
        }
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        return view('clients.pages.checkout', compact('addresses', 'defaultAddress', 'cartItems', 'totalPrice'));
    }

    public function getAddress(Request $request)
    {
        $address = ShippingAddress::where('id', $request->address_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy địa chỉ!']);
        }

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product', 'variant')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng trống!');
        }

        if ($request->payment_method === 'paypal') {
            return redirect()->route('paypal.create')->withInput();
        }

        DB::beginTransaction();

        try {
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->quantity * ($item->variant->sale_price ?? $item->product->price);
            }) + 25000;

            $discountAmount = 0;

            // Nếu có coupon
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
                    'variant_id' => $item->variant_id ?? null,
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
                'payment_method' => 'cash',
                'amount' => $totalAmount,
                'status' => 'pending',
                'paid_at' => null,
            ]);

            // Xóa giỏ hàng
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();
            toastr()->success('Đặt hàng thành công!');
            return redirect()->route('account');
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error('Có lỗi xảy ra, vui lòng thử lại!');
            return redirect()->route('checkout');
        }
    }
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $user = Auth::user();
        $coupon = Coupon::where('code', $request->coupon_code)->first();

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

        // ✅ Test: giả lập redirect sang trang success
        return response()->json([
            'redirect_url' => route('paypal.success')
        ]);
    }
}
