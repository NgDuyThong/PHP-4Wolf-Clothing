# Hướng Dẫn Sử Dụng Tiện Ích Bổ Sung

## Tổng Quan
Đã xây dựng hoàn chỉnh 4 trang trong phần "Tiện ích bổ sung":
1. **Thương hiệu** - Xem các thương hiệu và sản phẩm
2. **Giấy chứng nhận quà tặng** - Quản lý và sử dụng gift certificates
3. **Khuyến mãi** - Xem và áp dụng mã khuyến mãi
4. **Bản tin** - Đăng ký nhận email marketing

---

## 1. THƯƠNG HIỆU (Brands)

### Truy cập:
- URL: `/brands`
- Route name: `user.brands`

### Tính năng:
- Hiển thị danh sách tất cả thương hiệu
- Xem logo và mô tả thương hiệu
- Click vào thương hiệu để xem sản phẩm
- Phân trang

### Trang chi tiết thương hiệu:
- URL: `/brands/{id}`
- Route name: `user.brand.show`
- Hiển thị tất cả sản phẩm của thương hiệu đó

### Database:
- Bảng: `brands` (đã có sẵn)
- Đã thêm relationship với `products`

---

## 2. GIẤY CHỨNG NHẬN QUÀ TẶNG (Gift Certificates)

### Truy cập:
- URL: `/gift-certificates`
- Route name: `user.gift_certificates`

### Tính năng:

#### Kiểm tra mã:
1. Nhập mã giấy chứng nhận
2. Click "Kiểm tra"
3. Hệ thống sẽ hiển thị:
   - Giá trị của mã
   - Ngày hết hạn
   - Trạng thái (còn hiệu lực/đã dùng/hết hạn)

#### Quản lý giấy chứng nhận của tôi:
- URL: `/my-gift-certificates` (yêu cầu đăng nhập)
- Route name: `user.my-gift-certificates`
- Xem tất cả giấy chứng nhận đã nhận
- Theo dõi trạng thái sử dụng

### API Endpoints:
- `POST /api/gift-certificates/check` - Kiểm tra mã

### Database:
- Bảng: `gift_certificates`
- Các trường:
  - `code`: Mã giấy chứng nhận (unique)
  - `value`: Giá trị (VNĐ)
  - `user_id`: Người nhận
  - `is_used`: Đã sử dụng chưa
  - `used_by`: Người sử dụng
  - `used_at`: Thời gian sử dụng
  - `expires_at`: Ngày hết hạn
  - `message`: Lời nhắn

### Tạo giấy chứng nhận mẫu (Admin):
```php
GiftCertificate::create([
    'code' => 'GIFT-' . strtoupper(Str::random(8)),
    'value' => 500000,
    'user_id' => 1, // ID người nhận
    'expires_at' => now()->addMonths(6),
    'message' => 'Chúc mừng sinh nhật!'
]);
```

---

## 3. KHUYẾN MÃI (Promotions)

### Đã hoàn thành đầy đủ - Xem file `HUONG_DAN_KHUYEN_MAI.md`

---

## 4. BẢN TIN (Newsletter)

### Truy cập:
- URL: `/newsletter`
- Route name: `user.newsletter`

### Tính năng:

#### Đăng ký nhận bản tin:
1. Nhập email
2. Đồng ý điều khoản
3. Click "Đăng Ký"
4. Nhận thông báo thành công

#### Lợi ích:
- Ưu đãi độc quyền
- Thông báo sản phẩm mới
- Flash sale
- Xu hướng thời trang

### API Endpoints:
- `POST /api/newsletter/subscribe` - Đăng ký
- `POST /api/newsletter/unsubscribe` - Hủy đăng ký

### Database:
- Bảng: `newsletters`
- Các trường:
  - `email`: Email đăng ký (unique)
  - `subscribed_at`: Thời gian đăng ký

### Quản lý (Admin):
Admin có thể:
- Xem danh sách email đã đăng ký
- Export danh sách để gửi email marketing
- Xóa email khỏi danh sách

---

## Cấu Trúc Files

### Controllers:
```
app/Http/Controllers/
├── BrandPageController.php
├── GiftCertificateController.php
├── NewsletterController.php
└── PromotionController.php
```

### Models:
```
app/Models/
├── Brand.php (đã cập nhật)
├── GiftCertificate.php
├── Newsletter.php
├── Promotion.php
└── PromotionUsage.php
```

### Views:
```
resources/views/client/
├── brands.blade.php
├── brand-detail.blade.php
├── gift-certificates.blade.php
├── my-gift-certificates.blade.php
├── newsletter.blade.php
└── promotions.blade.php
```

### Migrations:
```
database/migrations/
├── 2025_11_27_000001_create_promotions_table.php
├── 2025_11_27_000002_add_promotion_to_orders_table.php
├── 2025_11_27_000003_create_gift_certificates_table.php
└── 2025_11_27_000004_create_newsletters_table.php
```

---

## Routes Tổng Hợp

### Client Routes:
```php
// Thương hiệu
GET /brands
GET /brands/{id}

// Giấy chứng nhận
GET /gift-certificates
GET /my-gift-certificates (auth)
POST /api/gift-certificates/check

// Khuyến mãi
GET /promotions
POST /api/promotions/validate

// Bản tin
GET /newsletter
POST /api/newsletter/subscribe
POST /api/newsletter/unsubscribe
```

---

## Chạy Migration

```bash
# Chạy tất cả migrations
php artisan migrate

# Hoặc chạy từng file
php artisan migrate --path=database/migrations/2025_11_27_000001_create_promotions_table.php
php artisan migrate --path=database/migrations/2025_11_27_000002_add_promotion_to_orders_table.php
php artisan migrate --path=database/migrations/2025_11_27_000003_create_gift_certificates_table.php
php artisan migrate --path=database/migrations/2025_11_27_000004_create_newsletters_table.php
```

---

## Tạo Dữ Liệu Mẫu

### Khuyến mãi:
```bash
php artisan db:seed --class=PromotionSeeder
```

### Giấy chứng nhận (trong tinker):
```bash
php artisan tinker
```
```php
GiftCertificate::create([
    'code' => 'GIFT-DEMO2025',
    'value' => 500000,
    'expires_at' => now()->addMonths(6)
]);
```

---

## Tích Hợp Vào Menu

Các trang đã được tích hợp vào menu chính:
- Menu "Khuyến Mãi" đã được thêm vào navigation bar
- Các trang khác có thể truy cập trực tiếp qua URL

Để thêm vào footer hoặc menu khác, sử dụng:
```blade
<a href="{{ route('user.brands') }}">Thương hiệu</a>
<a href="{{ route('user.gift_certificates') }}">Giấy chứng nhận quà tặng</a>
<a href="{{ route('user.promotions') }}">Khuyến mãi</a>
<a href="{{ route('user.newsletter') }}">Bản tin</a>
```

---

## Tùy Chỉnh

### Thay đổi giao diện:
- CSS đã được tích hợp trực tiếp trong các view
- Có thể tách ra file riêng nếu cần

### Thêm tính năng:
- Admin panel cho Gift Certificates
- Admin panel cho Newsletter
- Email automation cho Newsletter
- Tích hợp Gift Certificate vào checkout

---

## Lưu Ý

1. **Gift Certificates**: Hiện tại chỉ có chức năng kiểm tra. Cần tích hợp vào checkout để sử dụng.
2. **Newsletter**: Cần tích hợp với email service (Mailchimp, SendGrid, etc.) để gửi email thực tế.
3. **Brands**: Đảm bảo bảng brands có cột `img` và `description`.

---

## Hỗ Trợ

Nếu gặp vấn đề:
1. Kiểm tra migrations đã chạy chưa
2. Kiểm tra routes đã được thêm chưa
3. Xem log: `storage/logs/laravel.log`
4. Kiểm tra database có đủ quyền chưa
