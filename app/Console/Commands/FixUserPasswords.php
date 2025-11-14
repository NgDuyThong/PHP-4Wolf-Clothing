<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FixUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-passwords 
                            {--all : Reset password cho tất cả users}
                            {--email= : Reset password cho user cụ thể theo email}
                            {--password=Password123! : Password mặc định để reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và sửa password cho các user (reset về password mặc định)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Đang kiểm tra và sửa password cho users...');
        $this->newLine();

        $defaultPassword = $this->option('password');
        $resetAll = $this->option('all');
        $email = $this->option('email');

        // Validate password
        if (strlen($defaultPassword) < 8) {
            $this->error('❌ Password phải có ít nhất 8 ký tự!');
            return Command::FAILURE;
        }

        try {
            DB::beginTransaction();

            if ($resetAll) {
                // Reset tất cả users
                $users = User::whereNull('deleted_at')->get();
                $this->info("📋 Tìm thấy {$users->count()} users cần reset password.");
                
                if (!$this->confirm('⚠️  Bạn có chắc chắn muốn reset password cho TẤT CẢ users?', true)) {
                    $this->info('❌ Đã hủy bỏ.');
                    return Command::SUCCESS;
                }

                $bar = $this->output->createProgressBar($users->count());
                $bar->start();

                $count = 0;
                foreach ($users as $user) {
                    // Hash password cho mỗi user (mỗi lần hash sẽ có salt khác nhau)
                    $hashedPassword = Hash::make($defaultPassword);
                    // Update trực tiếp vào database để bỏ qua mutator
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['password' => $hashedPassword]);
                    $count++;
                    $bar->advance();
                }

                $bar->finish();
                $this->newLine(2);
                $this->info("✅ Đã reset password cho {$count} users!");
                $this->info("📝 Password mặc định: {$defaultPassword}");
                $this->warn("⚠️  Vui lòng thông báo cho users để họ đổi password sau khi đăng nhập!");

            } elseif ($email) {
                // Reset user cụ thể
                $user = User::where('email', $email)->whereNull('deleted_at')->first();
                
                if (!$user) {
                    $this->error("❌ Không tìm thấy user với email: {$email}");
                    return Command::FAILURE;
                }

                $this->info("📋 Tìm thấy user: {$user->name} ({$user->email})");
                
                if (!$this->confirm("⚠️  Bạn có chắc chắn muốn reset password cho user này?", true)) {
                    $this->info('❌ Đã hủy bỏ.');
                    return Command::SUCCESS;
                }

                // Update trực tiếp vào database để bỏ qua mutator
                $hashedPassword = Hash::make($defaultPassword);
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => $hashedPassword]);

                $this->info("✅ Đã reset password cho user: {$user->email}");
                $this->info("📝 Password mới: {$defaultPassword}");

            } else {
                // Hiển thị danh sách users và cho phép chọn
                $users = User::whereNull('deleted_at')->get(['id', 'name', 'email', 'role_id']);
                
                if ($users->isEmpty()) {
                    $this->warn('⚠️  Không tìm thấy user nào.');
                    return Command::SUCCESS;
                }

                $this->info("📋 Tìm thấy {$users->count()} users:");
                $this->newLine();

                $headers = ['ID', 'Tên', 'Email', 'Role'];
                $rows = [];
                foreach ($users as $user) {
                    $rows[] = [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role_id == 1 ? 'Admin' : ($user->role_id == 2 ? 'Staff' : 'User')
                    ];
                }
                $this->table($headers, $rows);
                $this->newLine();

                $this->warn('💡 Sử dụng các options:');
                $this->line('   --all              : Reset password cho tất cả users');
                $this->line('   --email=xxx@xxx.com : Reset password cho user cụ thể');
                $this->line('   --password=xxx      : Đặt password mặc định (mặc định: Password123!)');
            }

            DB::commit();
            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Lỗi: " . $e->getMessage());
            $this->error("📍 File: " . $e->getFile() . ":" . $e->getLine());
            return Command::FAILURE;
        }
    }
}
