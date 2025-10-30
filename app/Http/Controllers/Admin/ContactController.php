<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
   public function index()
{
    // Lấy danh sách liên hệ chưa được phản hồi (is_reply = 0)
    $contacts = Contact::orderByDesc('id', 0)->get();

    // Trả về view cùng dữ liệu
    return view('admin.pages.contact', compact('contacts'));
}

public function reply(Request $request)
{
    $contact = Contact::find($request->id);

    if (!$contact) {
        return response()->json(['status' => false, 'message' => 'Không tìm thấy liên hệ!']);
    }

    // Cập nhật trạng thái đã phản hồi
    $contact->is_replied = 1;
    $contact->save();

    // Gửi mail phản hồi (tùy chọn)
    Mail::raw($request->message, function ($mail) use ($contact) {
        $mail->to($contact->email)
             ->subject('Phản hồi từ cửa hàng');
    });

    return response()->json(['status' => true, 'message' => 'Phản hồi thành công!']);
}


}
