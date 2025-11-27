<?php

namespace App\Http\Controllers;

use App\Models\GiftCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GiftCertificateController extends Controller
{
    // Hiển thị trang giấy chứng nhận quà tặng
    public function index()
    {
        return view('client.gift-certificates');
    }

    // Kiểm tra mã giấy chứng nhận
    public function check(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string'
            ]);

            $certificate = GiftCertificate::where('code', strtoupper(trim($request->code)))->first();

            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giấy chứng nhận không tồn tại!'
                ]);
            }

            if ($certificate->is_used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giấy chứng nhận đã được sử dụng!',
                    'certificate' => [
                        'code' => $certificate->code,
                        'value' => $certificate->value,
                        'used_at' => $certificate->used_at ? $certificate->used_at->format('d/m/Y H:i') : null,
                        'used_by' => $certificate->usedBy ? $certificate->usedBy->name : null
                    ]
                ]);
            }

            if ($certificate->expires_at && $certificate->expires_at->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giấy chứng nhận đã hết hạn!'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Mã giấy chứng nhận hợp lệ!',
                'certificate' => [
                    'code' => $certificate->code,
                    'value' => $certificate->value,
                    'expires_at' => $certificate->expires_at ? $certificate->expires_at->format('d/m/Y') : null
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Gift certificate check error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại!'
            ], 500);
        }
    }

    // Trang lịch sử giấy chứng nhận của user
    public function myGiftCertificates()
    {
        $certificates = GiftCertificate::where('user_id', Auth::id())
            ->orWhere('used_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.my-gift-certificates', compact('certificates'));
    }
}
