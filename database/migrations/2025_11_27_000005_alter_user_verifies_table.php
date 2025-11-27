<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_verifies', function (Blueprint $table) {
            // Cho phép user_id null
            $table->integer('user_id')->nullable()->change();
            
            // Thêm cột data nếu chưa có
            if (!Schema::hasColumn('user_verifies', 'data')) {
                $table->text('data')->nullable()->after('email_verify')->comment('Dữ liệu đăng ký tạm thời');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_verifies', function (Blueprint $table) {
            $table->integer('user_id')->nullable(false)->change();
            
            if (Schema::hasColumn('user_verifies', 'data')) {
                $table->dropColumn('data');
            }
        });
    }
};
