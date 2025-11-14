<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;

class AuthenticatedSessionController extends Controller
{

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\AdminLoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminLoginRequest $request)
    {
        Log::info('Admin login - Controller store() called', [
            'email' => $request->email ?? 'NULL',
            'has_password' => !empty($request->password)
        ]);
        
        try {
            $request->authenticate();
            $request->session()->regenerate();
            Log::info('Admin login - Success, redirecting to admin.home');
            return redirect()->route('admin.home');
        } catch (\Exception $e) {
            Log::error('Admin login - Exception in store()', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }
    
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        //$request->session()->invalidate();

        //$request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
