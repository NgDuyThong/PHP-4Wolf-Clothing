<?php

namespace App\Services;

use App\Repository\Eloquent\OrderRepository;
use App\Repository\Eloquent\UserRepository;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use SebastianBergmann\Invoker\Exception;

class DashboardService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * DashboardService constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository, UserRepository $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($request)
    {
        //tổng doanh thu
        $revenue = $this->orderRepository->getRevenue();
        $revenue = $revenue[0]->total ?? 0;
        //tổng đơn hàng
        $orders = $this->orderRepository->getOrderTotal();
        //sản phẩm tồn kho
        $products = $this->orderRepository->getProductTotal();
        //lợi nhuận
        $profit =  $this->orderRepository->getProfit();
        //tổng khách hàng
        $users = count($this->userRepository->all());
        //tổng nhân sự
        $admins = count($this->userRepository->admins());

        //Ngày đầu tiên của tháng hiện tại
        $startDay = Carbon::now()->startOfMonth()->toDateString();
        $endDay = Carbon::now()->endOfMonth()->toDateString();
        $param = $request->get('reservation');
        $reservation = explode(" - ", $param);
        $this->isValidDate($reservation[1] ?? '');
        if ($param != null && $this->isValidDate($reservation[0] ?? '') && $this->isValidDate($reservation[1] ?? '')) {
            $startDay = Carbon::createFromFormat('d/m/Y', $reservation[0])->format('Y-m-d');
            $endDay = Carbon::createFromFormat('d/m/Y', $reservation[1])->format('Y-m-d');
        }

        //thống kê theo ngày trong tháng
        $salesStatisticsByDays = $this->orderRepository->salesStatisticsByFromTo($startDay, $endDay);
        $daysArray = [];
        $parameters = [];

        foreach ($salesStatisticsByDays as $day) {
            $daysArray[] = $day->report_date;
            $parameters[$day->report_date] = $day->total_revenue;
        }

        //lấy sản phẩm bán chạy
        $bestSellProducts = $this->orderRepository->bestSellProducts();
        $labelBestSellProduct = [];
        $parameterBestSellProduct = [];
        foreach ($bestSellProducts as $product) {
            $labelBestSellProduct[] = $product->name;
            $parameterBestSellProduct[] = $product->sum;
        }

        // lấy sản phẩm được đánh giá nhiều nhất
        $bestProductReviews = $this->orderRepository->bestProductReviews();
        $labelBestProductReview = [];
        $parameterBestProductReview = [];
        foreach ($bestProductReviews as $product) {
            $labelBestProductReview[] = $product->name;
            $parameterBestProductReview[] = $product->sum;
        }
        // lấy đơn hàng gần đây
        $list = $this->orderRepository->getNewOrders();
        $tableCrud = [
            'headers' => [
                [
                    'text' => 'Mã HD',
                    'key' => 'id',
                ],
                [
                    'text' => 'Tên KH',
                    'key' => 'user_name',
                ],
                [
                    'text' => 'Email',
                    'key' => 'user_email',
                ],
                [
                    'text' => 'Tổng Tiền',
                    'key' => 'total_money',
                    'format' => true,
                ],
                [
                    'text' => 'PT Thanh Toán',
                    'key' => 'payment_name',
                ],
                [
                    'text' => 'Ngày Đặt Hàng',
                    'key' => 'created_at',
                ],
                [
                    'text' => 'Trạng Thái',
                    'key' => 'order_status',
                    'status' => [
                        [
                            'text' => 'Chờ Xử Lý',
                            'value' => 0,
                            'class' => 'badge bg-warning'
                        ],
                        [
                            'text' => 'Đã xác nhận',
                            'value' => 1,
                            'class' => 'badge bg-info'
                        ],
                        [
                            'text' => 'Đã Hủy',
                            'value' => 2,
                            'class' => 'badge bg-danger'
                        ],
                        [
                            'text' => 'Đã Nhận Hàng',
                            'value' => 3,
                            'class' => 'badge bg-success'
                        ],
                        [
                            'text' => 'Đang Giao Hàng',
                            'value' => 4,
                            'class' => 'badge bg-info'
                        ],
                    ],
                ],
            ],
            'actions' => [
                'text'          => "Thao Tác",
                'create'        => false,
                'createExcel'   => false,
                'edit'          => true,
                'deleteAll'     => false,
                'delete'        => true,
                'viewDetail'    => false,
            ],
            'routes' => [
                'delete' => 'admin.orders_delete',
                'edit' => 'admin.orders_edit',
            ],
            'list' => $list,
        ];
        return [
            'title' => TextLayoutTitle("dashboard"),
            'revenue' => $revenue,
            'orders' => $orders,
            'products' => $products,
            'profit' => $profit,
            'users' => $users,
            'days' => json_encode($daysArray),
            'parameters' => json_encode($parameters),
            'tableCrud' => $tableCrud,
            'labelBestSellProduct' => json_encode($labelBestSellProduct),
            'parameterBestSellProduct' => json_encode($parameterBestSellProduct),
            'labelBestProductReview' => json_encode($labelBestProductReview),
            'parameterBestProductReview' => json_encode($parameterBestProductReview),
            'admins' => $admins
        ];
    }

    public function statistical($request)
    {
        //Ngày đầu tiên của tháng hiện tại
        $startDay = Carbon::now()->subMonths(3)->startOfMonth()->toDateString();
        $endDay = Carbon::now()->endOfMonth()->toDateString();
        $param = $request->get('reservation');
        $reservation = explode(" - ", $param);
        if ($param != null && $this->isValidDate($reservation[0] ?? '') && $this->isValidDate($reservation[1] ?? '')) {
            $reservation = explode(" - ", $request->get('reservation'));
            $startDay = Carbon::createFromFormat('d/m/Y', $reservation[0])->format('Y-m-d');
            $endDay = Carbon::createFromFormat('d/m/Y', $reservation[1])->format('Y-m-d');
        }
        $statisticalRevenueAndProfit = $this->orderRepository->statistical($startDay, $endDay);
        $revenue = 0;
        $profit = 0;
        $fee = 0;
        $totalImport = 0;

        foreach ($statisticalRevenueAndProfit as $item) {
            $revenue += $item->revenue;
            $profit += $item->profit;
            $fee += $item->transport_fee;
            $totalImport += $item->total_import;
        }
        $tableStatisRevAndPro = [
            'headers' => [
                [
                    'text' => 'Mã HD',
                    'key' => 'id',
                ],
                [
                    'text' => 'Phương Thức TT',
                    'key' => 'name',
                ],
                [
                    'text' => 'Tổng Tiền',
                    'key' => 'revenue',
                    'format' => true,
                ],
                [
                    'text' => 'Tổng Tiền Nhập Hàng',
                    'key' => 'total_import',
                    'format' => true,
                ],
                [
                    'text' => 'Phí Vận Chuyển',
                    'key' => 'transport_fee',
                    'format' => true,
                ],
                [
                    'text' => 'Lợi Nhuận',
                    'key' => 'profit',
                    'format' => true,
                ],
                [
                    'text' => 'Ngày Đặt Hàng',
                    'key' => 'created_at',
                ],
            ],
            'actions' => [
                'text'          => "Thao Tác",
                'create'        => false,
                'createExcel'   => false,
                'edit'          => false,
                'deleteAll'     => false,
                'delete'        => false,
                'viewDetail'    => false,
            ],
            'routes' => [

            ],
            'list' => $statisticalRevenueAndProfit,
        ];

        return [
            'tableStatisRevAndPro' => $tableStatisRevAndPro,
            'revenue' => $revenue,
            'profit' => $profit,
            'fee' => $fee,
            'total_import' =>$totalImport,
            'title' => "Thống Kê Chi Tiết",
        ];
    }
    function isValidDate($date) {
        try {
            $carbonDate = Carbon::createFromFormat('d/m/Y', $date);
            $date = $carbonDate->format('Y/m/d');
            return strtotime($date) !== false;
        } catch (InvalidFormatException $e) {
            return false;
        }
    }

}
?>
