<?php

namespace App\Services;

use App\Http\Requests\CheckOutRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductSize;
use App\Repository\Eloquent\OrderDetailRepository;
use App\Repository\Eloquent\OrderRepository;
use App\Services\PayOSService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class CheckOutService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderDetailRepository
     */
    private $orderDetailRepository;

    /**
     * @var PayOSService
     */
    private $payOSService;

    /**
     * CheckOutService constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository, OrderDetailRepository $orderDetailRepository, PayOSService $payOSService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->payOSService = $payOSService;
    }

    public function index()
    {
        try
        {
            $city = old('city') ?? Auth::user()->address->city;
            $district = old('district') ?? Auth::user()->address->district;
            $ward = old('ward') ?? Auth::user()->address->ward;
            $apartment_number = old('apartment_number') ?? Auth::user()->address->apartment_number;
            $phoneNumber = old('phone_number') ?? Auth::user()->phone_number;
            $fullName = old('full_name') ?? Auth::user()->name;
            $email = old('email') ?? Auth::user()->email;

            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/province');
            $citys = json_decode($response->body(), true);

            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                'province_id' => $city,
            ]);
            $districts = json_decode($response->body(), true);

            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                'district_id' => $district,
            ]);
            $wards = json_decode($response->body(), true);

            $payments = Payment::where('status', Payment::STATUS['active'])-> get();

            return [
                'citys' => $citys['data'],
                'districts' => $districts['data'],
                'wards' => $wards['data'],
                'city' => $city,
                'district' => $district,
                'ward' => $ward,
                'apartment_number' => $apartment_number,
                'phoneNumber' => $phoneNumber,
                'email' => $email,
                'fullName' => $fullName,
                'payments' => $payments,
            ];
        } catch (Exception) {
            return [];
        }

    }

    public function store(CheckOutRequest $request)
    {
        try {
            if ($this->checkProductUpdateAfterAddCard()) {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm bạn mua đã được thay đổi thông tin');
            }
            // lấy phí vận chuyển
            $fee = $this->getTransportFee($request->district, $request->ward)."";
            
            // Xử lý khuyến mãi
            $discountAmount = 0;
            $promotionId = null;
            if ($request->promotion_id) {
                $promotion = \App\Models\Promotion::find($request->promotion_id);
                if ($promotion && $promotion->isValid()) {
                    $orderTotal = \Cart::getTotal();
                    if ($promotion->canBeUsedByUser(Auth::id())) {
                        $discountAmount = $promotion->calculateDiscount($orderTotal);
                        $promotionId = $promotion->id;
                        
                        Log::info('Promotion applied', [
                            'promotion_id' => $promotionId,
                            'code' => $promotion->code,
                            'order_total' => $orderTotal,
                            'discount_amount' => $discountAmount
                        ]);
                    }
                }
            }
            
            //tạo dữ liệu đơn hàng
            $dataOrder = [
                'id' => time() . mt_rand(111, 999),
                'payment_id' => $request->payment_method,
                'promotion_id' => $promotionId,
                'user_id' => Auth::user()->id,
                'total_money' => \Cart::getTotal() + $fee - $discountAmount,
                'discount_amount' => $discountAmount,
                'order_status' => Order::STATUS_ORDER['wait'],
                'transport_fee' => $fee,
                'note' => null,
                'name' => Session::get('info_order.name'),
                'email' => Session::get('info_order.email'),
                'phone' => Session::get('info_order.phone'),
                'address' => Session::get('info_order.address'),
            ];
            DB::beginTransaction();
            // thêm đơn hàng vào csdl
            $order = $this->orderRepository->create($dataOrder);

            // thêm chi tiết vào đơn hàng mới tạo
            foreach(\Cart::getContent() as $product){
                // data order detail
                $orderDetail = [
                    'order_id' => $order->id,
                    'product_size_id' => $product->id,
                    'unit_price' => $product->price,
                    'quantity' => $product->quantity,
                    'import_price' => $product->attributes->import_price
                ];
                $this->orderDetailRepository->create($orderDetail);
            }
            
            // Lưu lịch sử sử dụng khuyến mãi
            if ($promotionId && $discountAmount > 0) {
                \App\Models\PromotionUsage::create([
                    'promotion_id' => $promotionId,
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'discount_amount' => $discountAmount,
                    'used_at' => now()
                ]);
                
                // Tăng số lần sử dụng
                $promotion->increment('usage_count');
            }
            
            DB::commit();
            // xóa toàn bộ sản phẩm trong giỏ hàng
            \Cart::clear();

            //chuyển hướng người dùng đến trang lịch sử mua hàng
            return redirect()->route('order_history.index');
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            // check quantity product
            foreach(\Cart::getContent() as $product){
                $productSize = ProductSize::where('id', $product->id)->first();
                if($productSize->quantity < $product->quantity) {
                    \Cart::update(
                        $product->id,
                        [
                            'quantity' => [
                                'relative' => false,
                                'value' => $productSize->quantity
                            ],
                        ]
                    );
                }
            }
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra vui lòng kiểm tra lại');
        }
    }

    public function paymentPayOS(CheckOutRequest $request)
    {
        if ($this->checkProductUpdateAfterAddCard()) {
            return redirect()->route('cart.index')->with('error', 'Sản phẩm bạn mua đã được thay đổi thông tin');
        }
        
        $orderId = time() . mt_rand(111, 999);
        $amount = \Cart::getTotal() + $this->getTransportFee($request->district, $request->ward);
        $description = "DH " . $orderId; // PayOS giới hạn 25 ký tự
        $returnUrl = route('checkout.callback_payos');
        $cancelUrl = route('checkout.index');
        
        $result = $this->payOSService->createPaymentLink($orderId, $amount, $description, $returnUrl, $cancelUrl);
        
        if ($result['success']) {
            return redirect($result['checkoutUrl']);
        }
        
        return redirect()->route('checkout.index')->with('error', $result['message']);
    }

    public function getTransportFee($district, $ward)
    {
        //get service id
        $fromDistrict = "2027";
        $shopId = "3577591";
        $response = Http::withHeaders([
            'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
            "shop_id" => $shopId,
            "from_district" => $fromDistrict,
            "to_district" => $district,
        ]);
        $serviceId = $response['data'][0]['service_type_id'];

        //data get fee
        $dataGetFee = [
            "service_type_id" => $serviceId,
            "insurance_value" => 500000,
            "coupon" => null,
            "from_district_id" => $fromDistrict,
            "to_district_id" => $district,
            "to_ward_code" => $ward,
            "height" => 15,
            "length" => 15,
            "weight" => 1000,
            "width" => 15
        ];
        $response = Http::withHeaders([
            'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', $dataGetFee);

        return $response['data']['total'];
    }

    // sau khi thanh toán thành công PayOS sẽ tự động chuyển đến hàm này
    public function callbackPayOS(Request $request)
    {
        try {
            Log::info('PayOS callback processing', $request->all());
            
            // PayOS trả về code=00 khi thành công
            if ($request->code == '00' && $request->status == 'PAID') {
                $district = Session::get('info_order.district');
                $ward = Session::get('info_order.ward');
                
                $dataOrder = [
                    'id' => $request->orderCode,
                    'payment_id' => Payment::METHOD['payos'],
                    'user_id' => Auth::user()->id,
                    'total_money' => $request->amount,
                    'order_status' => Order::STATUS_ORDER['wait'],
                    'transport_fee' => $this->getTransportFee($district, $ward),
                    'note' => null,
                    'name' => Session::get('info_order.name'),
                    'email' => Session::get('info_order.email'),
                    'phone' => Session::get('info_order.phone'),
                    'address' => Session::get('info_order.address'),
                ];
                
                DB::beginTransaction();
                
                // thêm 1 đơn hàng vào database
                $order = $this->orderRepository->create($dataOrder);

                // thêm chi tiết vào đơn hàng mới tạo
                foreach(\Cart::getContent() as $product){
                    $orderDetail = [
                        'order_id' => $order->id,
                        'product_size_id' => $product->id,
                        'unit_price' => $product->price,
                        'quantity' => $product->quantity,
                        'import_price' => $product->attributes->import_price
                    ];
                    $this->orderDetailRepository->create($orderDetail);
                }
                
                DB::commit();
                
                // xóa tất cả sản phẩm trong giỏ hàng
                \Cart::clear();
                
                // xóa session info_order
                Session::forget('info_order');

                return redirect()->route('order_history.index')->with('success', 'Thanh toán thành công!');
            }

            return redirect()->route('checkout.index')->with('error', 'Thanh toán thất bại hoặc bị hủy');
            
        } catch (Exception $e) {
            Log::error('PayOS callback error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            DB::rollBack();
            
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra vui lòng kiểm tra lại');
        }
    }



    private function checkProductUpdateAfterAddCard() 
    {
        $ids = [];
        foreach(\Cart::getContent() as $product){
            $productNew = DB::table('products')->where('id', $product->attributes->product_id)->first();

            if ($productNew->updated_at != $product->attributes->updated_at) {
                $ids[] = $product->id;
            }
        }

        foreach ($ids as $id) {
            \Cart::remove($id);
        }

        return count($ids) > 0;
    }
}
?>
