<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Mã giấy chứng nhận');
            $table->decimal('value', 15, 2)->comment('Giá trị');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->comment('Người nhận');
            $table->boolean('is_used')->default(false)->comment('Đã sử dụng');
            $table->foreignId('used_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người sử dụng');
            $table->timestamp('used_at')->nullable()->comment('Thời gian sử dụng');
            $table->timestamp('expires_at')->nullable()->comment('Ngày hết hạn');
            $table->text('message')->nullable()->comment('Lời nhắn');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_certificates');
    }
};
