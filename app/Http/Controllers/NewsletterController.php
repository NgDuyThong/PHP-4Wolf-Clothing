<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    // Hiển thị trang đăng ký nhận bản tin
    public function index()
    {
        return view('client.newsletter');
    }

    // Đăng ký nhận bản tin
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã đăng ký nhận bản tin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        Newsletter::create([
            'email' => $request->email,
            'subscribed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký nhận bản tin thành công! Cảm ơn bạn đã quan tâm.'
        ]);
    }

    // Hủy đăng ký
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $newsletter = Newsletter::where('email', $request->email)->first();

        if (!$newsletter) {
            return response()->json([
                'success' => false,
                'message' => 'Email không tồn tại trong danh sách'
            ]);
        }

        $newsletter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy đăng ký nhận bản tin'
        ]);
    }
}
