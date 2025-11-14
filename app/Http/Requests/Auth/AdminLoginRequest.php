<?php

namespace App\Http\Requests\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AdminLoginRequest extends FormRequest
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
        Log::info('Admin login - Validation rules called');
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
    
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            Log::info('Admin login - Validation passed', [
                'email' => $this->email ?? 'NULL',
                'has_password' => !empty($this->password)
            ]);
        });
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
        // Log ngay từ đầu để đảm bảo method được gọi
        Log::info('Admin login - authenticate() method called', [
            'email' => $this->email ?? 'NULL',
            'has_password' => !empty($this->password)
        ]);
        
        $this->ensureIsNotRateLimited();
        $email = $this->email;
        $password = $this->password;
        
        // Debug: Log query trước khi thực thi
        Log::info('Admin login - Starting query', [
            'email' => $email,
            'admin_role' => Role::ROLE['admin'],
            'staff_role' => Role::ROLE['staff']
        ]);
        
        // Tìm user với email trước (không filter role_id ngay)
        $userQuery = User::where('email', $email)->whereNull('deleted_at');
        
        // Debug: Log SQL query
        $sql = $userQuery->toSql();
        $bindings = $userQuery->getBindings();
        Log::info('Admin login - User query', [
            'sql' => $sql,
            'bindings' => $bindings
        ]);
        
        $userExists = $userQuery->first();
        
        // Kiểm tra xem user có tồn tại không
        if (!$userExists) {
            Log::info('Admin login failed - User not found', ['email' => $email]);
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        
        // Kiểm tra role_id
        if (!in_array($userExists->role_id, [Role::ROLE['admin'], Role::ROLE['staff']])) {
            Log::info('Admin login failed - Wrong role', [
                'email' => $email,
                'role_id' => $userExists->role_id,
                'active' => $userExists->active ?? 'NULL'
            ]);
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Tài khoản này không có quyền truy cập admin. Role ID hiện tại: ' . $userExists->role_id . ' (Cần: 1 hoặc 2)',
            ]);
        }
        
        // User hợp lệ
        $user = $userExists;
        
        // Debug: Log thông tin user tìm thấy
        Log::info('Admin login - User found', [
            'email' => $user->email,
            'role_id' => $user->role_id,
            'active' => $user->active ?? 'NULL',
            'has_password' => !empty($user->password)
        ]);
        
        // Kiểm tra password
        $passwordValid = Hash::check($password, $user->password);
        
        // Debug: Log thông tin password
        Log::info('Admin login - Password check', [
            'email' => $email,
            'password_valid' => $passwordValid,
            'password_length' => strlen($password),
            'hashed_password_start' => substr($user->password, 0, 10) . '...',
            'hashed_password_length' => strlen($user->password)
        ]);
        
        if (!$passwordValid) {
            Log::warning('Admin login failed - Wrong password', [
                'email' => $email,
                'password_length' => strlen($password),
                'hashed_password_start' => substr($user->password, 0, 10) . '...'
            ]);
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        
        // Kiểm tra nếu tài khoản bị vô hiệu hóa
        if (isset($user->active) && $user->active == 0) {
            Log::info('Admin login failed - Account disabled', [
                'email' => $email,
                'disable_reason' => $user->disable_reason ?? 'NULL'
            ]);
            RateLimiter::hit($this->throttleKey());
            $disableReason = $user->disable_reason ?? '';
            throw ValidationException::withMessages([
                'disable_reason' => trans('auth.locked') . $disableReason,
            ]);
        }
        
        // Đăng nhập thành công
        Auth::guard('admin')->login($user, $this->boolean('remember'));
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
