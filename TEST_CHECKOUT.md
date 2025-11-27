# Test Trang Checkout

## Các Bước Đã Thực Hiện

### 1. Clear Cache
```bash
php artisan view:clear
php artisan cache:clear  
php artisan config:clear
```
✅ Hoàn thành

### 2. Build Assets
```bash
npm run build
```
✅ Hoàn thành - promotion.js đã được build thành công

### 3. Kiểm Tra Files

#### File Checkout View
- ✅ Syntax đúng
- ✅ Có @extends và @endsection
- ✅ CSS đã được thêm
- ✅ HTML structure đúng

#### File promotion.js
- ✅ Không có lỗi syntax
- ✅ Event listeners đã được thêm
- ✅ Functions hoạt động đúng

#### File CheckOutService
- ✅ Logic xử lý promotion đúng
- ✅ Có logging
- ✅ Error handling đầy đủ

## Cách Test

### Test 1: Truy Cập Trang Checkout
1. Đăng nhập vào website
2. Thêm sản phẩm vào giỏ hàng
3. Click "Thanh Toán"
4. Kiểm tra trang có load không

**Nếu lỗi:**
- Mở Console (F12) → Tab Console
- Xem có lỗi JavaScript không
- Chụp màn hình gửi cho tôi

### Test 2: Kiểm Tra Phần Mã Khuyến Mãi
1. Tại trang checkout
2. Tìm phần "Mã khuyến mãi"
3. Kiểm tra:
   - ✓ Có ô input không?
   - ✓ Có nút "Áp dụng" không?
   - ✓ Có link "Xem tất cả mã khuyến mãi" không?

### Test 3: Áp Dụng Mã
1. Nhập mã: `WELCOME2025`
2. Click "Áp dụng"
3. Kiểm tra:
   - ✓ Có thông báo không?
   - ✓ Có dòng "Giảm giá" xuất hiện không?
   - ✓ Tổng đơn hàng có thay đổi không?

## Các Lỗi Có Thể Gặp

### Lỗi 1: Trang Trắng
**Nguyên nhân:** Lỗi PHP syntax
**Giải pháp:**
```bash
# Xem log
Get-Content storage/logs/laravel.log -Tail 50
```

### Lỗi 2: CSS Không Load
**Nguyên nhân:** Assets chưa được build
**Giải pháp:**
```bash
npm run build
```

### Lỗi 3: JavaScript Không Hoạt Động
**Nguyên nhân:** File JS chưa được include
**Giải pháp:**
- Kiểm tra @vite có đúng không
- Xem Console có lỗi không

### Lỗi 4: CSRF Token Mismatch
**Nguyên nhân:** Thiếu CSRF token
**Giải pháp:**
- Đã thêm `<meta name="csrf-token">` vào layout
- Clear cache và thử lại

## Debug Steps

### Bước 1: Kiểm Tra Routes
```bash
php artisan route:list | Select-String "checkout"
```
Kết quả mong đợi:
- GET checkout
- POST checkout
- GET checkout/callback-payos

### Bước 2: Kiểm Tra Database
```sql
-- Kiểm tra bảng promotions
SELECT * FROM promotions WHERE is_active = 1;

-- Kiểm tra bảng orders có cột promotion_id chưa
DESCRIBE orders;
```

### Bước 3: Kiểm Tra Assets
```bash
# Kiểm tra file đã được build chưa
ls public/build/assets/promotion*
ls public/build/assets/checkout*
```

### Bước 4: Test API
Mở Console và chạy:
```javascript
fetch('/api/promotions/validate', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        code: 'WELCOME2025',
        order_total: 500000
    })
})
.then(r => r.json())
.then(d => console.log(d));
```

## Thông Tin Hỗ Trợ

### Files Quan Trọng
1. `resources/views/client/checkout.blade.php` - View
2. `resources/client/js/promotion.js` - JavaScript
3. `app/Services/CheckOutService.php` - Backend logic
4. `app/Http/Controllers/PromotionController.php` - API

### Routes
- `/checkout` - Trang checkout
- `/api/promotions/validate` - API kiểm tra mã
- `/promotions` - Trang xem mã khuyến mãi

### Mã Test
- WELCOME2025 - Giảm 10% (min 500k)
- FREESHIP50K - Giảm 50k (min 300k)
- SUMMER2025 - Giảm 15% (min 1tr)
- MEGA100K - Giảm 100k (min 800k)
- VIP20 - Giảm 20% (min 2tr)

## Nếu Vẫn Lỗi

Vui lòng cung cấp:
1. Screenshot màn hình lỗi
2. Nội dung Console (F12 → Console tab)
3. Nội dung Network tab (nếu có request failed)
4. Log từ `storage/logs/laravel.log`

Tôi sẽ giúp bạn fix ngay!
