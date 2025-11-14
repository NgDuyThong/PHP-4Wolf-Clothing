# 4Wolf Store - Website Bán Thời Trang

Website thương mại điện tử chuyên bán quần áo thời trang được xây dựng bằng Laravel.

## Giới thiệu

4Wolf Store là một nền tảng thương mại điện tử hiện đại, cung cấp các sản phẩm thời trang chất lượng cao cho nam và nữ. Website được thiết kế với giao diện thân thiện, dễ sử dụng và tích hợp đầy đủ các tính năng cần thiết cho một cửa hàng trực tuyến.

## Tính năng chính

### Khách hàng
- 🛍️ Xem và tìm kiếm sản phẩm
- 🛒 Giỏ hàng và thanh toán
- 👤 Đăng ký/Đăng nhập tài khoản
- 📦 Theo dõi đơn hàng
- 💳 Thanh toán qua Momo và COD
- ⭐ Đánh giá sản phẩm

### Quản trị viên
- 📊 Dashboard thống kê
- 📦 Quản lý sản phẩm
- 👥 Quản lý người dùng
- 🛍️ Quản lý đơn hàng
- 🏷️ Quản lý danh mục
- 🎨 Quản lý thương hiệu
- ⚙️ Cài đặt website

## Công nghệ sử dụng

- **Framework**: Laravel 9.x
- **Database**: MySQL
- **Frontend**: Bootstrap, jQuery, Owl Carousel
- **Payment**: Momo API
- **Email**: SMTP

## Yêu cầu hệ thống

- PHP >= 8.0
- Composer
- MySQL >= 5.7
- Node.js & NPM

## Cài đặt

1. Clone repository
```bash
git clone https://github.com/NgDuyThong/PHP-4Wolf-Clothing.git
cd PHP-4Wolf-Clothing
```

2. Cài đặt dependencies
```bash
composer install
npm install
```

3. Tạo file .env
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Cấu hình database trong file .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Chạy migration và seeder
```bash
php artisan migrate --seed
```

7. Build assets
```bash
npm run dev
```

8. Khởi động server
```bash
php artisan serve
```

Website sẽ chạy tại: `http://localhost:8000`


## Cấu trúc thư mục

```
├── app/                    # Application logic
├── database/              # Migrations & Seeders
├── public/                # Public assets
│   └── asset/
│       ├── admin/        # Admin assets
│       └── client/       # Client assets
├── resources/
│   └── views/
│       ├── admin/        # Admin views
│       └── client/       # Client views
└── routes/               # Route definitions
```

## Tác giả

**Nguyễn Duy Thông**
- GitHub: [@NgDuyThong](https://github.com/NgDuyThong)

## License

Dự án này được phát hành dưới [MIT License](https://opensource.org/licenses/MIT).
