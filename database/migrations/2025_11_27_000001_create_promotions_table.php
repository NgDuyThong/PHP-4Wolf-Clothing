<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Mã khuyến mãi');
            $table->string('name')->comment('Tên chương trình');
            $table->text('description')->nullable()->comment('Mô tả');
            $table->enum('type', ['percentage', 'fixed'])->default('percentage')->comment('Loại: phần trăm hoặc số tiền cố định');
            $table->decimal('value', 15, 2)->comment('Giá trị giảm');
            $table->decimal('min_order_value', 15, 2)->default(0)->comment('Giá trị đơn hàng tối thiểu');
            $table->decimal('max_discount', 15, 2)->nullable()->comment('Giảm tối đa (cho loại %)');
            $table->integer('usage_limit')->nullable()->comment('Số lần sử dụng tối đa');
            $table->integer('usage_count')->default(0)->comment('Số lần đã sử dụng');
            $table->integer('usage_per_user')->default(1)->comment('Số lần mỗi user được dùng');
            $table->dateTime('start_date')->comment('Ngày bắt đầu');
            $table->dateTime('end_date')->comment('Ngày kết thúc');
            $table->boolean('is_active')->default(true)->comment('Trạng thái');
            $table->string('image')->nullable()->comment('Hình ảnh khuyến mãi');
            $table->timestamps();
        });

        // Bảng lưu lịch sử sử dụng mã khuyến mãi
        Schema::create('promotion_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_amount', 15, 2)->comment('Số tiền được giảm');
            $table->timestamp('used_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
        Schema::dropIfExists('promotions');
    }
};
