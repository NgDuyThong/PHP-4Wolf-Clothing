<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách ID từ query string (sẽ được gửi từ JavaScript)
        $wishlistIds = $request->input('ids', '');
        $products = [];
        
        if ($wishlistIds) {
            $ids = explode(',', $wishlistIds);
            $products = Product::whereIn('id', $ids)->get();
        }
        
        return view('client.wishlist', compact('products'));
    }
    
    public function remove(Request $request)
    {
        // API endpoint để xóa sản phẩm khỏi wishlist
        return response()->json(['success' => true]);
    }
}
