<?php

namespace App\Http\Requests\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.required' => __('validation.required', ['attribute' => 'mật khẩu']),
            'password.string' => __('validation.string', ['attribute' => 'mật khẩu']),
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        $email = $this->email;
        $password = $this->password;
        
        // Tìm user với email và role_id phù hợp, chưa bị xóa
        $user = User::where('email', $email)
            ->where('role_id', Role::ROLE['user'])
            ->whereNull('deleted_at')
            ->first();
        
        // Nếu không tìm thấy user
        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        
        // Kiểm tra email đã được verify chưa
        if (!$user->email_verified_at) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Tài khoản chưa được xác thực email. Vui lòng kiểm tra email và xác thực tài khoản trước khi đăng nhập.',
            ]);
        }
        
        // Kiểm tra password
        if (!Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        
        // Kiểm tra nếu tài khoản bị vô hiệu hóa
        if ($user->active == 0) {
            RateLimiter::hit($this->throttleKey());
            $disableReason = $user->disable_reason ?? '';
            throw ValidationException::withMessages([
                'disable_reason' => trans('auth.locked') . $disableReason,
            ]);
        }
        
        // Đăng nhập thành công
        Auth::guard()->login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}
