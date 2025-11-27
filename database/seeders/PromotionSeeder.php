<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'code' => 'WELCOME2025',
                'name' => 'Chào mừng khách hàng mới',
                'description' => 'Giảm 10% cho đơn hàng đầu tiên, áp dụng cho đơn hàng từ 500.000đ',
                'type' => 'percentage',
                'value' => 10,
                'min_order_value' => 500000,
                'max_discount' => 100000,
                'usage_limit' => 100,
                'usage_per_user' => 1,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP50K',
                'name' => 'Miễn phí vận chuyển',
                'description' => 'Giảm 50.000đ phí vận chuyển cho đơn hàng từ 300.000đ',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_value' => 300000,
                'max_discount' => null,
                'usage_limit' => null,
                'usage_per_user' => 3,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER2025',
                'name' => 'Khuyến mãi mùa hè',
                'description' => 'Giảm 15% cho tất cả sản phẩm, áp dụng cho đơn hàng từ 1.000.000đ',
                'type' => 'percentage',
                'value' => 15,
                'min_order_value' => 1000000,
                'max_discount' => 200000,
                'usage_limit' => 50,
                'usage_per_user' => 2,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
                'is_active' => true,
            ],
            [
                'code' => 'MEGA100K',
                'name' => 'Giảm giá khủng',
                'description' => 'Giảm ngay 100.000đ cho đơn hàng từ 800.000đ',
                'type' => 'fixed',
                'value' => 100000,
                'min_order_value' => 800000,
                'max_discount' => null,
                'usage_limit' => 200,
                'usage_per_user' => 1,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addWeeks(2),
                'is_active' => true,
            ],
            [
                'code' => 'VIP20',
                'name' => 'Ưu đãi khách hàng VIP',
                'description' => 'Giảm 20% cho đơn hàng từ 2.000.000đ, dành cho khách hàng thân thiết',
                'type' => 'percentage',
                'value' => 20,
                'min_order_value' => 2000000,
                'max_discount' => 500000,
                'usage_limit' => 30,
                'usage_per_user' => 5,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}
