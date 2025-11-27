# Hướng Dẫn Test Các Tính Năng

## ✅ Đã Hoàn Thành

### 1. Thêm CSRF Token
- ✅ Đã thêm `<meta name="csrf-token">` vào layout client
- ✅ JavaScript sử dụng token này cho tất cả AJAX requests

### 2. Cải Thiện Error Handling
- ✅ Thêm try-catch cho tất cả API endpoints
- ✅ Log errors để debug
- ✅ Trả về response code phù hợp (500 cho server errors)

### 3. Cải Thiện Validation
- ✅ Trim và uppercase mã code trước khi kiểm tra
- ✅ Kiểm tra user đã đăng nhập chưa (cho promotion)
- ✅ Validate đầy đủ các điều kiện

### 4. Cải Thiện UX
- ✅ Thêm xử lý phím Enter cho input fields
- ✅ Disable button khi đang xử lý
- ✅ Hiển thị spinner khi loading
- ✅ Auto-hide message sau 5 giây

### 5. Tạo Dữ Liệu Test
- ✅ Seeder cho Promotions (5 mã)
- ✅ Seeder cho Gift Certificates (4 mã)

---

## 🧪 Test Cases

### A. Test Trang Khuyến Mãi (/promotions)

#### Test 1: Xem danh sách khuyến mãi
1. Truy cập `/promotions`
2. Kiểm tra hiển thị đúng các mã khuyến mãi
3. Kiểm tra thông tin: tên, giá trị, điều kiện, thời gian

**Kết quả mong đợi:**
- Hiển thị 5 mã khuyến mãi mẫu
- Có thể click để copy mã
- Nút "Mua ngay" hoạt động

#### Test 2: Copy mã khuyến mãi
1. Click vào mã khuyến mãi
2. Kiểm tra tooltip "Đã sao chép!"

**Kết quả mong đợi:**
- Mã được copy vào clipboard
- Tooltip hiển thị 2 giây

---

### B. Test Áp Dụng Khuyến Mãi ở Checkout

#### Test 3: Áp dụng mã hợp lệ
1. Thêm sản phẩm vào giỏ (tổng > 500.000đ)
2. Vào trang checkout
3. Nhập mã: `WELCOME2025`
4. Click "Áp dụng"

**Kết quả mong đợi:**
- ✅ Message: "Áp dụng mã khuyến mãi thành công!"
- ✅ Hiển thị dòng "Giảm giá" với số tiền
- ✅ Tổng đơn hàng được cập nhật

#### Test 4: Mã không tồn tại
1. Nhập mã: `INVALID123`
2. Click "Áp dụng"

**Kết quả mong đợi:**
- ❌ Message: "Mã khuyến mãi không tồn tại!"

#### Test 5: Đơn hàng không đủ điều kiện
1. Thêm sản phẩm (tổng < 500.000đ)
2. Nhập mã: `WELCOME2025`
3. Click "Áp dụng"

**Kết quả mong đợi:**
- ❌ Message: "Đơn hàng tối thiểu 500,000đ để áp dụng mã này!"

#### Test 6: Nhấn Enter để áp dụng
1. Nhập mã vào ô input
2. Nhấn phím Enter

**Kết quả mong đợi:**
- Tự động trigger nút "Áp dụng"

#### Test 7: Hoàn tất đơn hàng với khuyến mãi
1. Áp dụng mã thành công
2. Điền đầy đủ thông tin
3. Click "Thanh Toán Đơn Hàng"

**Kết quả mong đợi:**
- ✅ Đơn hàng được tạo
- ✅ Lưu promotion_id và discount_amount
- ✅ Tạo record trong promotion_usages
- ✅ Tăng usage_count của promotion

---

### C. Test Trang Giấy Chứng Nhận (/gift-certificates)

#### Test 8: Kiểm tra mã hợp lệ
1. Truy cập `/gift-certificates`
2. Nhập mã: `GIFT-DEMO-2025`
3. Click "Kiểm tra"

**Kết quả mong đợi:**
- ✅ Message: "Mã hợp lệ!"
- ✅ Hiển thị giá trị: 500,000đ
- ✅ Hiển thị ngày hết hạn

#### Test 9: Mã không tồn tại
1. Nhập mã: `GIFT-INVALID`
2. Click "Kiểm tra"

**Kết quả mong đợi:**
- ❌ Message: "Mã giấy chứng nhận không tồn tại!"

#### Test 10: Mã đã sử dụng
1. Nhập mã: `GIFT-USED-1234`
2. Click "Kiểm tra"

**Kết quả mong đợi:**
- ❌ Message: "Mã giấy chứng nhận đã được sử dụng!"
- Hiển thị thông tin: thời gian sử dụng, người sử dụng

#### Test 11: Mã hết hạn
1. Nhập mã: `GIFT-EXPIRED-99`
2. Click "Kiểm tra"

**Kết quả mong đợi:**
- ❌ Message: "Mã giấy chứng nhận đã hết hạn!"

#### Test 12: Nhấn Enter để kiểm tra
1. Nhập mã vào ô input
2. Nhấn phím Enter

**Kết quả mong đợi:**
- Tự động trigger nút "Kiểm tra"

---

### D. Test Trang Giấy Chứng Nhận Của Tôi

#### Test 13: Xem danh sách (chưa đăng nhập)
1. Logout
2. Truy cập `/my-gift-certificates`

**Kết quả mong đợi:**
- Redirect về trang login

#### Test 14: Xem danh sách (đã đăng nhập)
1. Login
2. Truy cập `/my-gift-certificates`

**Kết quả mong đợi:**
- Hiển thị danh sách giấy chứng nhận
- Phân biệt trạng thái: Còn hiệu lực / Đã sử dụng / Hết hạn

---

## 🔍 Kiểm Tra Database

### Sau khi áp dụng khuyến mãi thành công:

```sql
-- Kiểm tra bảng orders
SELECT id, promotion_id, discount_amount, total_money 
FROM orders 
ORDER BY created_at DESC 
LIMIT 1;

-- Kiểm tra bảng promotion_usages
SELECT * FROM promotion_usages 
ORDER BY used_at DESC 
LIMIT 1;

-- Kiểm tra usage_count đã tăng
SELECT code, usage_count, usage_limit 
FROM promotions 
WHERE code = 'WELCOME2025';
```

---

## 🐛 Debug

### Nếu gặp lỗi 419 (CSRF Token Mismatch):
1. Kiểm tra `<meta name="csrf-token">` có trong HTML không
2. Clear cache: `php artisan cache:clear`
3. Kiểm tra session driver trong `.env`

### Nếu API không hoạt động:
1. Mở Console (F12) để xem lỗi JavaScript
2. Kiểm tra Network tab để xem request/response
3. Xem log: `storage/logs/laravel.log`

### Nếu không thấy dữ liệu:
```bash
# Chạy lại seeders
php artisan db:seed --class=PromotionSeeder
php artisan db:seed --class=GiftCertificateSeeder
```

---

## 📝 Mã Test Có Sẵn

### Khuyến Mãi:
- `WELCOME2025` - Giảm 10% (min 500k, max 100k)
- `FREESHIP50K` - Giảm 50k (min 300k)
- `SUMMER2025` - Giảm 15% (min 1tr, max 200k)
- `MEGA100K` - Giảm 100k (min 800k)
- `VIP20` - Giảm 20% (min 2tr, max 500k)

### Giấy Chứng Nhận:
- `GIFT-DEMO-2025` - 500k (còn hiệu lực)
- `GIFT-TEST-ABCD` - 300k (còn hiệu lực)
- `GIFT-USED-1234` - 200k (đã sử dụng)
- `GIFT-EXPIRED-99` - 400k (hết hạn)

---

## ✨ Tính Năng Đã Cải Thiện

1. **CSRF Protection**: Đã thêm token vào tất cả AJAX requests
2. **Error Handling**: Try-catch cho tất cả API endpoints
3. **Validation**: Trim, uppercase, kiểm tra đầy đủ
4. **UX**: Enter key, loading states, auto-hide messages
5. **Logging**: Log errors để debug dễ dàng
6. **Code Quality**: Loại bỏ unused variables
7. **Data Seeding**: Tạo dữ liệu test đầy đủ
