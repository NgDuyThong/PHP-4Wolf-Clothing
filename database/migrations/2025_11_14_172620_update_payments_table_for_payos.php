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
        // Cập nhật payment method ID 2 thành PayOS
        DB::table('payments')->where('id', 2)->update([
            'name' => 'PayOS',
            'status' => 1,
            'img' => 'payos.png',
            'updated_at' => now(),
        ]);
        
        // Vô hiệu hóa VNPay (ID 3) nếu tồn tại
        DB::table('payments')->where('id', 3)->update([
            'status' => 0,
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Xóa PayOS
        DB::table('payments')->where('id', 2)->delete();
    }
};
