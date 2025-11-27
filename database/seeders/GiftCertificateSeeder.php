<?php

namespace Database\Seeders;

use App\Models\GiftCertificate;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GiftCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $certificates = [
            [
                'code' => 'GIFT-DEMO-2025',
                'value' => 500000,
                'user_id' => null,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMonths(6),
                'message' => 'Giấy chứng nhận quà tặng demo'
            ],
            [
                'code' => 'GIFT-TEST-ABCD',
                'value' => 300000,
                'user_id' => null,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMonths(3),
                'message' => 'Chúc mừng sinh nhật!'
            ],
            [
                'code' => 'GIFT-USED-1234',
                'value' => 200000,
                'user_id' => 1,
                'is_used' => true,
                'used_by' => 1,
                'used_at' => Carbon::now()->subDays(5),
                'expires_at' => Carbon::now()->addMonths(6),
                'message' => 'Mã đã được sử dụng'
            ],
            [
                'code' => 'GIFT-EXPIRED-99',
                'value' => 400000,
                'user_id' => null,
                'is_used' => false,
                'expires_at' => Carbon::now()->subDays(10),
                'message' => 'Mã đã hết hạn'
            ],
        ];

        foreach ($certificates as $certificate) {
            GiftCertificate::create($certificate);
        }
    }
}
