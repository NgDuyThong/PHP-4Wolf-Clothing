# Hướng Dẫn Sử Dụng Hệ Thống Khuyến Mãi

## Tổng Quan
Hệ thống khuyến mãi đã được xây dựng hoàn chỉnh cho website bán quần áo, cho phép:
- Admin quản lý các mã khuyến mãi
- Khách hàng xem và áp dụng mã khuyến mãi khi mua hàng
- Theo dõi lịch sử sử dụng mã khuyến mãi

## Các Tính Năng Chính

### 1. Quản Lý Khuyến Mãi (Admin)

#### Truy cập:
- Đăng nhập vào Admin Panel
- Vào menu: **Quản Lý Bán Hàng > Khuyến Mãi**

#### Tạo Mã Khuyến Mãi Mới:
1. Click nút "Thêm mã khuyến mãi"
2. Điền thông tin:
   - **Mã khuyến mãi**: Mã duy nhất (VD: SUMMER2025)
   - **Tên chương trình**: Tên hiển thị
   - **Mô tả**: Mô tả chi tiết về chương trình
   - **Loại khuyến mãi**: 
     - Phần trăm (%): Giảm theo phần trăm giá trị đơn hàng
     - Số tiền cố định (đ): Giảm một số tiền cố định
   - **Giá trị**: Giá trị giảm (% hoặc số tiền)
   - **Giảm tối đa**: Số tiền giảm tối đa (chỉ cho loại phần trăm)
   - **Giá trị đơn hàng tối thiểu**: Đơn hàng phải đạt giá trị này mới áp dụng được
   - **Số lần sử dụng tối đa**: Tổng số lần mã có thể được sử dụng (để trống = không giới hạn)
   - **Số lần mỗi user được dùng**: Mỗi khách hàng có thể dùng bao nhiêu lần
   - **Ngày bắt đầu/kết thúc**: Thời gian hiệu lực
   - **Hình ảnh**: Upload hình ảnh minh họa (không bắt buộc)
   - **Kích hoạt ngay**: Bật/tắt mã khuyến mãi

3. Click "Tạo mã khuyến mãi"

#### Chỉnh Sửa/Xóa:
- Click icon bút chì để chỉnh sửa
- Click icon thùng rác để xóa
- Click icon toggle để bật/tắt mã khuyến mãi

### 2. Xem Khuyến Mãi (Khách Hàng)

#### Truy cập:
- Vào menu: **Khuyến Mãi** trên thanh menu chính
- Hoặc truy cập: `https://your-domain.com/promotions`

#### Tính năng:
- Xem tất cả các mã khuyến mãi đang hoạt động
- Click vào mã để tự động sao chép
- Xem điều kiện áp dụng của từng mã
- Click "Mua ngay" để đi đến trang sản phẩm

### 3. Áp Dụng Mã Khuyến Mãi Khi Thanh Toán

#### Cách sử dụng:
1. Thêm sản phẩm vào giỏ hàng
2. Vào trang thanh toán
3. Tìm phần "Mã khuyến mãi"
4. Nhập mã hoặc click "Xem các mã khuyến mãi" để chọn
5. Click "Áp dụng"
6. Hệ thống sẽ tự động:
   - Kiểm tra mã có hợp lệ không
   - Kiểm tra đơn hàng có đủ điều kiện không
   - Tính toán và hiển thị số tiền được giảm
   - Cập nhật tổng đơn hàng

## Ví Dụ Mã Khuyến Mãi Mẫu

Hệ thống đã tạo sẵn 5 mã khuyến mãi mẫu:

1. **WELCOME2025**: Giảm 10% (tối đa 100.000đ) cho đơn từ 500.000đ
2. **FREESHIP50K**: Giảm 50.000đ cho đơn từ 300.000đ
3. **SUMMER2025**: Giảm 15% (tối đa 200.000đ) cho đơn từ 1.000.000đ
4. **MEGA100K**: Giảm 100.000đ cho đơn từ 800.000đ
5. **VIP20**: Giảm 20% (tối đa 500.000đ) cho đơn từ 2.000.000đ

## Cấu Trúc Database

### Bảng `promotions`:
- Lưu thông tin các mã khuyến mãi
- Các trường quan trọng: code, type, value, min_order_value, usage_limit, start_date, end_date

### Bảng `promotion_usages`:
- Lưu lịch sử sử dụng mã khuyến mãi
- Liên kết với: promotions, users, orders

### Bảng `orders`:
- Đã thêm 2 trường mới:
  - `promotion_id`: ID của mã khuyến mãi đã sử dụng
  - `discount_amount`: Số tiền được giảm

## API Endpoints

### Client:
- `GET /promotions` - Xem danh sách khuyến mãi
- `POST /api/promotions/validate` - Kiểm tra mã khuyến mãi (AJAX)

### Admin:
- `GET /admin/promotions` - Danh sách khuyến mãi
- `GET /admin/promotions/create` - Form tạo mới
- `POST /admin/promotions/create` - Lưu mã mới
- `GET /admin/promotions/edit/{id}` - Form chỉnh sửa
- `PUT /admin/promotions/update/{id}` - Cập nhật
- `DELETE /admin/promotions/delete/{id}` - Xóa
- `PATCH /admin/promotions/toggle/{id}` - Bật/tắt

## Lưu Ý Kỹ Thuật

### Chạy Migration:
```bash
php artisan migrate
```

### Tạo Dữ Liệu Mẫu:
```bash
php artisan db:seed --class=PromotionSeeder
```

### Build Assets:
```bash
npm run build
# hoặc
npm run dev
```

## Tùy Chỉnh

### Thay đổi giao diện:
- File CSS: `resources/client/css/` (đã tích hợp trong view)
- File JS: `resources/client/js/promotion.js`

### Thay đổi logic:
- Model: `app/Models/Promotion.php`
- Controller Admin: `app/Http/Controllers/Admin/PromotionController.php`
- Controller Client: `app/Http/Controllers/PromotionController.php`
- Service: `app/Services/CheckOutService.php`

## Hỗ Trợ

Nếu gặp vấn đề, kiểm tra:
1. Database đã chạy migration chưa
2. File JavaScript đã được build chưa (`npm run build`)
3. Storage folder có quyền ghi chưa (cho upload ảnh)
4. Log file: `storage/logs/laravel.log`
