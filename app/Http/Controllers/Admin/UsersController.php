<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')->paginate(9);

        return view('admin.pages.users', compact('users'));
    }
    public function upgrade(Request $request)
    {
        $userId = $request->user_id;

        $user = User::find($userId);

        if (!$user) {
            return redirect()->json([
                'status' => false,
                'message' => 'Không tìm thấy người dùng',
            ]);
        }
        $user->role_id = 2; // Role = 2 same as "staff"
        $user->save();

        // Redirect back with success message
        return response()->json([
            'status' => true,
            'message' => 'Đã update thành nhân viên',
        ]);
    }
    // Chặn / Bỏ chặn
    public function updateStatus(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user) return response()->json(['status' => false, 'message' => 'Không tìm thấy user'], 404);

        if ($user->status === 'active' || $user->status === 'pending') $user->status = 'banned';
        elseif ($user->status === 'banned') $user->status = 'active';

        $user->save();

        return response()->json(['status' => true, 'new_status' => $user->status, 'message' => 'Cập nhật trạng thái người dùng thành công']);
    }

    // Xóa / Khôi phục
    public function toggleDelete(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user) return response()->json(['status' => false, 'message' => 'Không tìm thấy user'], 404);

        $user->status = ($user->status === 'deleted') ? 'active' : 'deleted';
        $user->save();

        return response()->json(['status' => true, 'new_status' => $user->status, 'message' => 'Cập nhật trạng thái người dùng thành công']);
    }
}
