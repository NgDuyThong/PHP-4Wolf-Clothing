<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;

class AuthenticatedSessionController extends Controller
{

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\UserLoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserLoginRequest $request)
    {
        // Thực hiện xác thực thông tin đăng nhập
        $request->authenticate();

        // Kiểm tra trạng thái xác thực email
        $user = Auth::user();

        if (!$user || !$user->hasVerifiedEmail()) {
            // Nếu chưa xác thực email thì đăng xuất và báo lỗi
            Auth::guard('web')->logout();

            return redirect()->route('user.login')
                ->with('error', 'Tài khoản của bạn chưa được xác thực email. Vui lòng kiểm tra hộp thư và xác nhận trước khi đăng nhập.');
        }

        // Đăng nhập thành công và đã xác thực email
        $request->session()->regenerate();
        return redirect()->route('user.home');
    }
    
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        //$request->session()->invalidate();

        //$request->session()->regenerateToken();

        return redirect()->route('user.home');
    }
}
