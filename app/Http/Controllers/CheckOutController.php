<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckOutRequest;
use App\Models\Payment;
use App\Services\CheckOutService;
use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    /**
     * @var CheckOutService
     */
    private $checkOutService;

    /**
     * CheckOutController constructor.
     *
     * @param CheckOutService $checkOutService
     */
    public function __construct(CheckOutService $checkOutService)
    {
        $this->checkOutService = $checkOutService;
    }
    /**
     * hiển thị trang thanh toán
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // nếu giỏ hàng trống thì không cho vào trang thanh toán
        if (count(\Cart::getContent()) <= 0) {
            return back();
        }
        // trả về cho phía khách hàng
        if (count($this->checkOutService->index()) == 0) {
            return redirect()->route('user.home')->with('error', 'Có lỗi xảy ra vui lòng kiểm tra lại');
        }
        return view('client.checkout', $this->checkOutService->index());
    }

    // xử lý khi người dùng bấm nút thanh toán đơn hàng
    public function store(CheckOutRequest $request)
    {
        // nếu khách hàng chọn thanh toán online momo
        if ($request->payment_method == Payment::METHOD['momo']) {
            return $this->checkOutService->paymentMomo();
        }
        // nếu khách hàng chọn thanh toán online vnpay
        if ($request->payment_method == Payment::METHOD['vnpay']) {
            return $this->checkOutService->paymentVNPAY();
        }

        return $this->checkOutService->store($request);
    }

    public function callbackMomo(Request $request)
    {
        return $this->checkOutService->callbackMomo($request);
    }
}
