<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    // Hiển thị danh sách khuyến mãi
    public function index()
    {
        $promotions = Promotion::active()
            ->orderBy('end_date', 'asc')
            ->paginate(12);

        return view('client.promotions', compact('promotions'));
    }

    // Kiểm tra mã khuyến mãi (AJAX)
    public function validatePromotion(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string',
                'order_total' => 'required|numeric|min:0'
            ]);

            $promotion = Promotion::where('code', strtoupper(trim($request->code)))->first();

            if (!$promotion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã khuyến mãi không tồn tại!'
                ]);
            }

            if (!$promotion->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã khuyến mãi đã hết hạn hoặc không còn hiệu lực!'
                ]);
            }

            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để sử dụng mã khuyến mãi!'
                ]);
            }

            if (!$promotion->canBeUsedByUser($userId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã sử dụng hết số lần cho phép của mã này!'
                ]);
            }

            if ($request->order_total < $promotion->min_order_value) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng tối thiểu ' . number_format($promotion->min_order_value) . 'đ để áp dụng mã này!'
                ]);
            }

            $discount = $promotion->calculateDiscount($request->order_total);

            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã khuyến mãi thành công!',
                'promotion' => [
                    'id' => $promotion->id,
                    'code' => $promotion->code,
                    'name' => $promotion->name,
                    'type' => $promotion->type,
                    'value' => $promotion->value,
                    'discount' => $discount
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Promotion validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại!'
            ], 500);
        }
    }
}
