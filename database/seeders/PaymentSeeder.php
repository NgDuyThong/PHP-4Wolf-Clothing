<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kiểm tra xem đã có dữ liệu chưa
        if (DB::table('payments')->count() > 0) {
            return;
        }

        DB::table('payments')->insert([
            [
                'id' => 1,
                'name' => 'Thanh toán khi nhận hàng',
                'status' => 1,
                'img' => 'cash.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'PayOS',
                'status' => 1,
                'img' => 'payos.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
