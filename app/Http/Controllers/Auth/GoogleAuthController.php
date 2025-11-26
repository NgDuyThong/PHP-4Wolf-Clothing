<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Chuyển hướng người dùng đến trang đăng nhập Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Tìm user theo email hoặc google_id
            $user = User::where('email', $googleUser->email)
                        ->orWhere('google_id', $googleUser->id)
                        ->first();
            
            if ($user) {
                // Nếu user đã tồn tại, cập nhật thông tin Google
                $user->update([
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                    'active' => 1,
                ]);
                
                // Đăng nhập user
                Auth::login($user);
                
                return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
            } else {
                // User mới - lưu thông tin vào session và chuyển đến trang hoàn thiện thông tin
                session([
                    'google_user' => [
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                    ]
                ]);
                
                return redirect()->route('auth.google.complete')->with('info', 'Vui lòng hoàn thiện thông tin để tiếp tục!');
            }
            
        } catch (Exception $e) {
            return redirect()->route('user.login')->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại!');
        }
    }
    
    /**
     * Hiển thị form hoàn thiện thông tin
     */
    public function showCompleteForm()
    {
        if (!session('google_user')) {
            return redirect()->route('user.login');
        }
        
        return view('auth.google-complete');
    }
    
    /**
     * Xử lý hoàn thiện thông tin và tạo tài khoản
     */
    public function completeRegistration(\Illuminate\Http\Request $request)
    {
        $googleUser = session('google_user');
        
        if (!$googleUser) {
            return redirect()->route('user.login')->with('error', 'Phiên đăng nhập đã hết hạn!');
        }
        
        // Validate thông tin bổ sung
        $request->validate([
            'phone_number' => 'required|string|max:20',
        ], [
            'phone_number.required' => 'Vui lòng nhập số điện thoại',
        ]);
        
        try {
            // Tạo user mới với đầy đủ thông tin
            $user = User::create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['google_id'],
                'phone_number' => $request->phone_number,
                'password' => '', // Không cần password cho Google login
                'email_verified_at' => now(),
                'active' => 1,
                'role_id' => 2, // Role user thường
            ]);
            
            // Xóa session
            session()->forget('google_user');
            
            // Đăng nhập user
            Auth::login($user);
            
            return redirect('/')->with('success', 'Đăng ký thành công!');
            
        } catch (Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!')->withInput();
        }
    }
}
