<?php
/**
 * Script kiểm tra user admin trong database
 * Chạy: php check_admin_user.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "=== KIỂM TRA USER ADMIN ===\n\n";

// Nhập email từ command line hoặc hardcode
$email = $argv[1] ?? null;

if (!$email) {
    echo "Cách sử dụng: php check_admin_user.php <email>\n";
    echo "Ví dụ: php check_admin_user.php admin@gmail.com\n\n";
    
    // Hiển thị tất cả users
    echo "Danh sách tất cả users:\n";
    $users = User::whereNull('deleted_at')->get(['id', 'name', 'email', 'role_id', 'active', 'email_verified_at']);
    foreach ($users as $user) {
        $roleName = $user->role_id == 1 ? 'Admin' : ($user->role_id == 2 ? 'Staff' : 'User');
        echo sprintf(
            "ID: %d | Email: %s | Role: %s (ID: %d) | Active: %s | Verified: %s\n",
            $user->id,
            $user->email,
            $roleName,
            $user->role_id,
            $user->active ?? 'NULL',
            $user->email_verified_at ? 'Yes' : 'No'
        );
    }
    exit;
}

echo "Đang kiểm tra user: {$email}\n\n";

// Tìm user
$user = User::where('email', $email)->whereNull('deleted_at')->first();

if (!$user) {
    echo "❌ KHÔNG TÌM THẤY USER với email: {$email}\n";
    exit(1);
}

echo "✅ Tìm thấy user:\n";
echo "   - ID: {$user->id}\n";
echo "   - Name: {$user->name}\n";
echo "   - Email: {$user->email}\n";
echo "   - Role ID: {$user->role_id}\n";

$roleName = $user->role_id == 1 ? 'Admin' : ($user->role_id == 2 ? 'Staff' : 'User');
echo "   - Role: {$roleName}\n";
echo "   - Active: " . ($user->active ?? 'NULL') . "\n";
echo "   - Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
echo "   - Deleted At: " . ($user->deleted_at ?? 'NULL') . "\n";
echo "   - Has Password: " . (!empty($user->password) ? 'Yes' : 'No') . "\n";

if (!empty($user->password)) {
    $passwordLength = strlen($user->password);
    $isHashed = $passwordLength === 60 && (substr($user->password, 0, 4) === '$2y$' || substr($user->password, 0, 4) === '$2a$' || substr($user->password, 0, 4) === '$2b$');
    echo "   - Password Length: {$passwordLength}\n";
    echo "   - Password Is Hashed: " . ($isHashed ? 'Yes' : 'No') . "\n";
}

echo "\n=== KIỂM TRA ĐIỀU KIỆN ĐĂNG NHẬP ADMIN ===\n\n";

$errors = [];

// Kiểm tra role_id
if (!in_array($user->role_id, [Role::ROLE['admin'], Role::ROLE['staff']])) {
    $errors[] = "❌ Role ID không đúng: {$user->role_id} (Cần: 1 hoặc 2)";
    echo "   " . end($errors) . "\n";
} else {
    echo "   ✅ Role ID đúng: {$user->role_id}\n";
}

// Kiểm tra active
if (isset($user->active) && $user->active == 0) {
    $errors[] = "❌ Tài khoản bị vô hiệu hóa (active = 0)";
    echo "   " . end($errors) . "\n";
} else {
    echo "   ✅ Tài khoản đang hoạt động\n";
}

// Kiểm tra deleted_at
if ($user->deleted_at) {
    $errors[] = "❌ Tài khoản đã bị xóa (deleted_at không NULL)";
    echo "   " . end($errors) . "\n";
} else {
    echo "   ✅ Tài khoản chưa bị xóa\n";
}

// Kiểm tra password
if (empty($user->password)) {
    $errors[] = "❌ Password rỗng";
    echo "   " . end($errors) . "\n";
} else {
    echo "   ✅ Có password\n";
}

echo "\n=== KẾT QUẢ ===\n";
if (empty($errors)) {
    echo "✅ User này có thể đăng nhập admin!\n";
    echo "\nNếu vẫn không đăng nhập được, có thể do:\n";
    echo "1. Password không đúng\n";
    echo "2. Có vấn đề với session/guard\n";
} else {
    echo "❌ User này KHÔNG THỂ đăng nhập admin vì:\n";
    foreach ($errors as $error) {
        echo "   {$error}\n";
    }
    echo "\nHãy sửa các lỗi trên trong MySQL:\n";
    if (!in_array($user->role_id, [Role::ROLE['admin'], Role::ROLE['staff']])) {
        echo "   UPDATE users SET role_id = 1 WHERE id = {$user->id};\n";
    }
    if (isset($user->active) && $user->active == 0) {
        echo "   UPDATE users SET active = 1 WHERE id = {$user->id};\n";
    }
    if ($user->deleted_at) {
        echo "   UPDATE users SET deleted_at = NULL WHERE id = {$user->id};\n";
    }
}

echo "\n";

