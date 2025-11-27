<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\TextSystemConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserVerify;
use App\Notifications\VerifyUserRegister;
use App\Repository\Eloquent\AddressRepository;
use App\Repository\Eloquent\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     * @param AddressRepository $addressRepository
     */
    public function __construct(UserRepository $userRepository, AddressRepository $addressRepository)
    {
        $this->userRepository = $userRepository;
        $this->addressRepository = $addressRepository;
    }
    /**
     * Hiển thị màn hình đăng kí
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            // lấy tỉnh thành phố
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/province');
            $citys = json_decode($response->body(), true);

            // lấy quận huyện
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                'province_id' => old('city') ?? $citys['data'][0]['ProvinceID'],
            ]);
            $districts = json_decode($response->body(), true);

            //lấy phường xã
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                'district_id' => old('district') ?? $districts['data'][0]['DistrictID'],
            ]);
            $wards = json_decode($response->body(), true);
            
            // kiểm tra xem người dùng đã nhập đầy thông tin hay chưa
            $rules = [
                'email' => [
                    'required' => true,
                    'email' => true,
                ],
                'password' => [
                    'required' => true,
                    'minlength' => 8,
                    'maxlength' => 24,
                    'checklower' => true,
                    'checkupper' => true,
                    'checkdigit' => true,
                    'checkspecialcharacter' => true,
                ],
                'password_confirm'=> [
                    'required' => true,
                    'minlength' => 8,
                    'maxlength' => 24,
                    'checklower' => true,
                    'checkupper' => true,
                    'checkdigit' => true,
                    'checkspecialcharacter' => true,
                    'equalTo'=> "#password"
                ],
                'name' => [
                    'required' => true,
                    'minlength' => 1,
                    'maxlength' => 30,
                ],
                'apartment_number' => [
                    'required' => true,
                ],
                'city' => [
                    'required' => true,
                ],
                'district' => [
                    'required' => true,
                ],
                'ward' => [
                    'required' => true,
                ],
                'phone_number' => [
                    'required' => true,
                    'validPhone' => true
                ],
            ];
    
            // Hiển thị thông báo lỗi khi người dùng chưa nhập đủ thông tin
            $messages = [
                'name' => [
                    'required' => __('message.required', ['attribute' => 'Họ và tên']),
                    'minlength' => __('message.min', ['min' => 1, 'attribute' => 'Họ và tên']),
                    'maxlength' => __('message.max', ['max' => 30, 'attribute' => 'Họ và tên']),
                ],
                'email' => [
                    'required' => __('message.required', ['attribute' => 'email']),
                    'email' => __('message.email'),
                ],
                'password' => [
                    'required' => __('message.required', ['attribute' => 'mật khẩu']),
                    'minlength' => __('message.min', ['attribute' => 'Mật khẩu', 'min' => 8]),
                    'maxlength' => __('message.max', ['attribute' => 'Mật khẩu', 'max' => 24]),
                    'checklower' => __('message.password.at_least_one_lowercase_letter_is_required'),
                    'checkupper' => __('message.password.at_least_one_uppercase_letter_is_required'),
                    'checkdigit' => __('message.password.at_least_one_digit_is_required'),
                    'checkspecialcharacter' => __('message.password.at_least_special_characte_is_required'),
                ],
                'password_confirm' => [
                    'required' => __('message.required', ['attribute' => 'mật khẩu']),
                    'minlength' => __('message.min', ['attribute' => 'Mật khẩu', 'min' => 8]),
                    'maxlength' => __('message.max', ['attribute' => 'Mật khẩu', 'max' => 24]),
                    'checklower' => __('message.password.at_least_one_lowercase_letter_is_required'),
                    'checkupper' => __('message.password.at_least_one_uppercase_letter_is_required'),
                    'checkdigit' => __('message.password.at_least_one_digit_is_required'),
                    'checkspecialcharacter' => __('message.password.at_least_special_characte_is_required'),
                    'equalTo' => 'Xác nhận mật khẩu không đúng',
                ],
                'phone_number' => [
                    'required' => __('message.required', ['attribute' => 'số điện thoại']),
                    'validPhone' => "Số điện thoại không hợp lệ"
                ],
                'city' => [
                    'required' =>  __('message.required', ['attribute' => 'tỉnh, thành phố']),
                ],
                'district' =>[
                    'required' =>  __('message.required', ['attribute' => 'quận, huyện']),
                ],
                'ward' => [
                    'required' => __('message.required', ['attribute' => 'phường, xã']),
                ],
                'apartment_number' => [
                    'required' =>  __('message.required', ['attribute' => 'số nhà']),
                ],
            ];
    
            return view('auth.register', [
                'citys' => $citys['data'],
                'districts' => $districts['data'],
                'wards' => $wards['data'],
                'rules' => $rules,
                'messages' => $messages,
            ]);
        } catch (Exception) {
            return redirect()->route('user.login');
        }
        
    }

    // hàm store dùng để xử lý logic đăng ký (chỉ gửi mail, chưa tạo tài khoản)
    public function store(UserRegisterRequest $request)
    {
        try {
            // lấy tất cả dữ liệu từ phía người dùng gửi lên
            $data = $request->validated();
            
            // tạo ra token để gửi mail xác thực tài khoản
            $token = Str::random(64);
            // tạo thời gian cho token
            $time = Config::get('auth.verification.expire.resend', 60);

            
            DB::beginTransaction();
            // Xóa bản ghi cũ nếu có (cùng email)
            UserVerify::where('email_verify', $data['email'])->delete();
            
            // Lưu thông tin đăng ký tạm thời vào bảng user_verifies (chưa tạo tài khoản)
            UserVerify::create([
                'email_verify' => $data['email'],
                'user_id' => null,
                'token' => $token,
                'expires_at' => Carbon::now()->addMinutes($time),
                'data' => json_encode($data),
            ]);
            
            DB::commit();

            // Gửi email xác thực tài khoản cho người dùng (chỉ khi lưu tạm thành công)
            try {
                // Gửi tới email khách hàng với token xác thực
                (new User(['email' => $data['email'], 'name' => $data['name']]))
                    ->notify(new VerifyUserRegister($token));
            } catch (\Exception $emailException) {
                Log::warning('Failed to send verification email to user: ' . $data['email']);
                Log::warning('Email error: ' . $emailException->getMessage());
                return redirect()->route('user.login')
                    ->with('error', 'Đăng ký thành công nhưng gửi email xác thực thất bại, vui lòng thử lại sau.');
            }

            // Sau khi đăng ký thành công, yêu cầu người dùng kiểm tra email để xác thực
            return redirect()->route('user.login')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản trước khi đăng nhập.');
        } catch (Exception $e) {
            // khi có lỗi xảy ra thì xóa bỏ dữ thêm vào database trước đó
            Log::error('Register Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            DB::rollBack();
            // hiển thị lỗi chi tiết hơn để debug
            return back()->with('error', 'Đăng ký thất bại: ' . $e->getMessage())->withInput();
        }
    }

    // đây là view xác thực tài khoản khi người dùng đăng kí tài khoản
    public function verifyEmail(User $user)
    {
        return view('auth.verify-email', [
            'user' => $user,
        ]);
    }

    // gửi lại email xác thực tài khoản
    public function resendEmail(Request $request)
    {
        try {
            // tìm kiếm user mà người yêu cầu gửi lại liên kết có tồn tại trong csdl hay không
            $user = $this->userRepository->find($request->id);
            // nếu không có thì chuyển người dùng đến trang home
            if(! $user) {
                return redirect('user.home');
            }
            //nếu tài khoản người dùng đã được xác nhận
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('user.home');
            }
            $token = Str::random(64);
            $time = Config::get('auth.verification.expire.resend', 60);
            DB::beginTransaction();
            UserVerify::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'token' => $token,
                    'expires_at' => Carbon::now()->addMinutes($time),
                ]
            );
            $user->notify(new VerifyUserRegister($token));
            DB::commit();
            return back()->with('status', 'verification-link-sent');
        } catch (Exception $e) {
            Log::error($e);
            return back()->with('error', $e->getMessage());
        }
    }

    // hiển thị màn hình xác thực tài khoản thành công
    public function success()
    {
        if (session('status')) {
            return view('auth.verify-success')->with('verify_user_success');
        }
        return back();
    }

}
