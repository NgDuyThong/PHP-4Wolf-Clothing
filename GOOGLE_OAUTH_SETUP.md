# Hướng dẫn cấu hình Google OAuth 2.0

## Bước 1: Tạo Google OAuth Credentials

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo project mới hoặc chọn project hiện có
3. Vào **APIs & Services** > **Credentials**
4. Click **Create Credentials** > **OAuth client ID**
5. Chọn **Application type**: Web application
6. Điền thông tin:
   - **Name**: Tên ứng dụng của bạn
   - **Authorized JavaScript origins**: `http://localhost` (hoặc domain của bạn)
   - **Authorized redirect URIs**: `http://localhost/auth/google/callback` (hoặc domain của bạn)
7. Click **Create** và lưu lại **Client ID** và **Client Secret**

## Bước 2: Cấu hình file .env

Thêm các dòng sau vào file `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT=http://localhost/auth/google/callback
```

## Bước 3: Chạy migration

```bash
php artisan migrate
```

## Bước 4: Test chức năng

1. Truy cập trang đăng nhập: `http://localhost/login`
2. Click vào nút "Đăng nhập bằng Google"
3. Chọn tài khoản Google để đăng nhập
4. Sau khi đăng nhập thành công, bạn sẽ được chuyển về trang chủ

## Lưu ý

- Đảm bảo Laravel Socialite đã được cài đặt (đã có sẵn trong composer.json)
- Cột `google_id` sẽ được thêm vào bảng `users` để lưu Google ID
- User đăng nhập bằng Google sẽ không cần password
- Email sẽ tự động được xác thực khi đăng nhập bằng Google
