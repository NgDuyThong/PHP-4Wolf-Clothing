<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Kiểm tra xem đã có payment với id=1 chưa
        $payment = DB::table('payments')->where('id', 1)->first();
        
        if (!$payment) {
            DB::table('payments')->insert([
                'id' => 1,
                'name' => 'Thanh toán khi nhận hàng',
                'status' => 1,
                'img' => 'cash.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Cập nhật nếu đã tồn tại
            DB::table('payments')->where('id', 1)->update([
                'name' => 'Thanh toán khi nhận hàng',
                'status' => 1,
                'img' => 'cash.png',
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Không xóa payment vì có thể đã có đơn hàng sử dụng
    }
};
