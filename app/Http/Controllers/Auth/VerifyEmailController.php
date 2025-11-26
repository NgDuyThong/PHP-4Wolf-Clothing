<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\UserVerify;
use App\Models\User;
use App\Models\Role;
use App\Repository\Eloquent\AddressRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class VerifyEmailController extends Controller
{
    /**
     * @var AddressRepository
     */
    private $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }
    /**
     * Show user's email address as verified.
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        if (session('status')) {
            return view('admin.auth.verify-success');
        }

        return redirect()->route('user.login');
    }

    /**
     * Mark the authenticated user's email address as verified and remove user_verifies after verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyAccount(Request $request)
    {
        if ($request->token) {
            $token = $request->token;
            $verifyUser = UserVerify::where('token', $token)->first();
            if (empty($verifyUser)) {
                return redirect()->route('user.login')->with('error', __('message.token_is_invalid'));
            }
            $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $verifyUser->expires_at);
            $date2 = Carbon::now();
            $result = $date1->gt($date2);
            if (!$result) {
                return redirect()->route('user.login')->with('error', __('message.token_is_invalid'));
            }
            DB::transaction(function () use ($verifyUser) {
                // Lấy dữ liệu đăng ký tạm từ cột data
                $data = json_decode($verifyUser->data, true);

                // Tạo tài khoản user chính thức
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $verifyUser->email_verify ?? $data['email'],
                    'password' => Hash::make($data['password']),
                    'phone_number' => $data['phone_number'],
                    'role_id' => Role::ROLE['user'],
                    'active' => 1,
                    'email_verified_at' => Carbon::now(),
                ]);

                // Lưu địa chỉ cho khách hàng
                $addressData = [
                    'user_id' => $user->id,
                    'city' => $data['city'] ?? null,
                    'district' => $data['district'] ?? null,
                    'ward' => $data['ward'] ?? null,
                    'apartment_number' => $data['apartment_number'] ?? null,
                ];

                // Sử dụng AddressRepository giống RegisterController
                app(AddressRepository::class)->updateOrCreate($addressData);

                // Cập nhật user_id vào bản ghi verify rồi xóa để dọn dẹp
                $verifyUser->user_id = $user->id;
                $verifyUser->save();
                $verifyUser->delete();
            });
            return redirect()->route('user.verify.success')->with('status', 'verifify-success');
        }
        return redirect()->route('login');
    }
}
