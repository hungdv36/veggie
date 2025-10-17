<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index()
    {
        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        $addresses = ShippingAddress::where('user_id', Auth::id())->get();

        // Truyền biến $user sang view
        return view('clients.pages.account', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $request->validate([
          'ltn__name' => 'required|string|max:255',
          'ltn__phone_number' => 'nullable|string|max:15',
          'ltn__address' => 'nullable|string|max:255',
          'avata' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

      /** @var \App\Models\User $user */
$user = Auth::user();

        //handle avata
        if($request->hasFile('avata ')){
            //delete số điện thoại cũ
            if($user->avatar && Storage::disk('public')->exists($user->avatar)){
                Storage::disk('public')->delete($user->avatar);
            }
            $file = $request->file('avatar');
            //Create new name with timestamp
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalExtension();

            //save image to folder storage/app/uploads/users/tenfile.jpg
            $avatarPath = $file->storeAs('uploads/users', $filename, 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $request->input('ltn__name');
        $user->phone_number = $request->input('ltn__phone_number');
        $user->address = $request->input('ltn__address');
        $user->save();

        return response()->json([
             'success' =>true,
             'message' => 'Cập nhật thông tin thành công',
             'avatar' => asset('storage/' . $user->avatar)
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate(
            [
                'current_password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ],
            [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
                'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
                'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
                'confirm_password.required' => 'Vui lòng xác nhận mật khẩu mới.',
                'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu mới.',
            ]
        );
        /** @var \App\Models\User $user */
        $user = Auth::user();

        //Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'error' => ['current_password' => ['Mật khẩu hiện tại không đúng.']]
            ], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu đã được thay đổi thành công.',
        ]);
    }
    public function addAddress(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
        ]);

        // If the new address is marked as default, unset previous default addresses = 0
        if ($request->has('default')) {
            ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);
        }

        ShippingAddress::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'default' => $request->has('default') ? 1 : 0,
        ]);
        return back()->with('success', 'Địa chỉ đã được thêm thành công.');
    }
    public function updatePrimaryAddress($id)
    {
        $address = ShippingAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Set all addresses this user default = 0
        ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);

        // Update address selected default = 1
        $address->update(['default' => 1]);

        toastr()->success('Địa chỉ mặc định đã được cập nhật thành công.');
        return back();
    }
    public function deleteAddress($id)
    {
        $address = ShippingAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Prevent deleting the default address
        if ($address->default) {
            return back()->with('error', 'Không thể xóa địa chỉ mặc định.');
        }

        $address->delete();
        return back()->with('success', 'Địa chỉ đã được xóa thành công.');
    }
}
