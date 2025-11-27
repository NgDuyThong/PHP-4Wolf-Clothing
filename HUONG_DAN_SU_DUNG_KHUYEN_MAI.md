# Hướng Dẫn Sử Dụng Mã Khuyến Mãi

## 🎯 Luồng Hoàn Chỉnh

### Bước 1: Xem Danh Sách Khuyến Mãi
1. Truy cập trang **Khuyến Mãi** từ menu hoặc footer
2. Xem tất cả các mã khuyến mãi đang có
3. Click vào mã để **tự động sao chép**
4. Kiểm tra điều kiện áp dụng

### Bước 2: Mua Sắm
1. Chọn sản phẩm và thêm vào giỏ hàng
2. Đảm bảo tổng giá trị đơn hàng đủ điều kiện
3. Vào trang **Giỏ Hàng** kiểm tra
4. Click **Thanh Toán**

### Bước 3: Áp Dụng Mã Khuyến Mãi
1. Tại trang **Checkout**, tìm phần "Mã khuyến mãi"
2. Dán hoặc nhập mã đã copy (VD: `WELCOME2025`)
3. Click nút **"Áp dụng"** hoặc nhấn **Enter**
4. Chờ hệ thống kiểm tra

### Bước 4: Kiểm Tra Kết Quả
**Nếu thành công:**
- ✅ Hiển thị thông báo màu xanh: "Áp dụng mã khuyến mãi thành công!"
- ✅ Xuất hiện dòng "Giảm giá" với số tiền được giảm
- ✅ Tổng đơn hàng được cập nhật tự động

**Nếu thất bại:**
- ❌ Hiển thị thông báo màu đỏ với lý do cụ thể:
  - "Mã khuyến mãi không tồn tại!"
  - "Mã khuyến mãi đã hết hạn hoặc không còn hiệu lực!"
  - "Bạn đã sử dụng hết số lần cho phép của mã này!"
  - "Đơn hàng tối thiểu XXX đ để áp dụng mã này!"

### Bước 5: Hoàn Tất Đơn Hàng
1. Điền đầy đủ thông tin giao hàng
2. Chọn phương thức thanh toán
3. Click **"Thanh Toán Đơn Hàng"**
4. Hệ thống sẽ:
   - Lưu thông tin khuyến mãi vào đơn hàng
   - Ghi nhận lịch sử sử dụng
   - Tăng số lần đã dùng của mã

---

## 💡 Mẹo Sử Dụng

### 1. Copy Mã Nhanh
- Click trực tiếp vào mã khuyến mãi
- Mã sẽ tự động copy vào clipboard
- Tooltip "✓ Đã sao chép!" sẽ hiện ra

### 2. Nhập Mã Nhanh
- Nhập mã vào ô input
- Nhấn **Enter** thay vì click nút "Áp dụng"

### 3. Xem Mã Khi Checkout
- Click link "Xem tất cả mã khuyến mãi"
- Trang mới sẽ mở ra (không mất thông tin đã nhập)

### 4. Kiểm Tra Điều Kiện
Trước khi áp dụng, đảm bảo:
- ✓ Đơn hàng đủ giá trị tối thiểu
- ✓ Mã còn trong thời gian hiệu lực
- ✓ Bạn chưa dùng hết số lần cho phép
- ✓ Mã còn số lượng (nếu có giới hạn)

---

## 📊 Các Loại Khuyến Mãi

### 1. Giảm Theo Phần Trăm (%)
- Giảm X% giá trị đơn hàng
- Có thể có giới hạn giảm tối đa
- **Ví dụ:** WELCOME2025 - Giảm 10% (tối đa 100.000đ)

### 2. Giảm Số Tiền Cố Định (đ)
- Giảm trực tiếp số tiền
- Không có giới hạn
- **Ví dụ:** FREESHIP50K - Giảm 50.000đ

---

## 🔍 Công Thức Tính Toán

```
Tổng sản phẩm:     XXX.XXXđ
Phí vận chuyển:    +XX.XXXđ
Giảm giá:          -XX.XXXđ
─────────────────────────────
Tổng đơn hàng:     XXX.XXXđ
```

### Ví Dụ Cụ Thể:

**Trường hợp 1: Giảm theo %**
```
Sản phẩm:          500.000đ
Phí ship:          +30.000đ
Mã WELCOME2025:    -50.000đ (10% của 500k)
─────────────────────────────
Tổng:              480.000đ
```

**Trường hợp 2: Giảm cố định**
```
Sản phẩm:          800.000đ
Phí ship:          +30.000đ
Mã MEGA100K:       -100.000đ
─────────────────────────────
Tổng:              730.000đ
```

---

## ⚠️ Lưu Ý Quan Trọng

### 1. Điều Kiện Áp Dụng
- Mã chỉ áp dụng cho **giá trị sản phẩm**, không tính phí ship
- Phải đăng nhập mới được sử dụng mã
- Mỗi đơn hàng chỉ dùng được **1 mã**

### 2. Giới Hạn Sử Dụng
- Mỗi user có số lần sử dụng giới hạn
- Một số mã có tổng số lượng giới hạn
- Không thể dùng lại mã đã hết hạn

### 3. Thời Gian
- Kiểm tra ngày bắt đầu và kết thúc
- Mã hết hạn sẽ không thể áp dụng
- Thời gian được tính theo giờ server

### 4. Khi Hủy Đơn
- Nếu hủy đơn hàng, số lần sử dụng **KHÔNG** được hoàn lại
- Cân nhắc kỹ trước khi đặt hàng

---

## 🐛 Xử Lý Lỗi

### Lỗi: "Mã khuyến mãi không tồn tại"
**Nguyên nhân:**
- Nhập sai mã
- Mã đã bị xóa

**Giải pháp:**
- Kiểm tra lại mã chính xác
- Xem danh sách mã còn hiệu lực

### Lỗi: "Đơn hàng tối thiểu XXX đ"
**Nguyên nhân:**
- Giá trị đơn hàng chưa đủ

**Giải pháp:**
- Thêm sản phẩm vào giỏ
- Chọn mã khác có điều kiện thấp hơn

### Lỗi: "Đã sử dụng hết số lần"
**Nguyên nhân:**
- Đã dùng mã này đủ số lần cho phép

**Giải pháp:**
- Sử dụng mã khác
- Đợi chương trình khuyến mãi mới

### Lỗi: "Vui lòng đăng nhập"
**Nguyên nhân:**
- Chưa đăng nhập tài khoản

**Giải pháp:**
- Đăng nhập trước khi áp dụng mã
- Đăng ký tài khoản nếu chưa có

---

## 📱 Giao Diện

### Trang Khuyến Mãi
- Card hiển thị đẹp mắt với gradient
- Thông tin đầy đủ: giá trị, điều kiện, thời gian
- Click để copy mã nhanh
- Nút "Mua Ngay" dẫn đến trang sản phẩm

### Trang Checkout
- Phần mã khuyến mãi nổi bật với background màu
- Input lớn, dễ nhập
- Nút "Áp dụng" với hover effect
- Link xem mã mở tab mới

### Thông Báo
- Màu xanh cho thành công
- Màu đỏ cho lỗi
- Tự động ẩn sau 5 giây
- Hiển thị rõ ràng, dễ đọc

---

## 🎁 Mã Khuyến Mãi Hiện Có

### 1. WELCOME2025
- **Loại:** Giảm 10%
- **Tối đa:** 100.000đ
- **Điều kiện:** Đơn từ 500.000đ
- **Số lần:** 1 lần/user

### 2. FREESHIP50K
- **Loại:** Giảm 50.000đ
- **Điều kiện:** Đơn từ 300.000đ
- **Số lần:** 3 lần/user

### 3. SUMMER2025
- **Loại:** Giảm 15%
- **Tối đa:** 200.000đ
- **Điều kiện:** Đơn từ 1.000.000đ
- **Số lần:** 2 lần/user

### 4. MEGA100K
- **Loại:** Giảm 100.000đ
- **Điều kiện:** Đơn từ 800.000đ
- **Số lần:** 1 lần/user

### 5. VIP20
- **Loại:** Giảm 20%
- **Tối đa:** 500.000đ
- **Điều kiện:** Đơn từ 2.000.000đ
- **Số lần:** 5 lần/user

---

## ✨ Tính Năng Đã Hoàn Thiện

✅ Xem danh sách khuyến mãi đẹp mắt
✅ Copy mã nhanh chóng
✅ Áp dụng mã tại checkout
✅ Kiểm tra điều kiện tự động
✅ Tính toán giảm giá chính xác
✅ Cập nhật tổng tiền real-time
✅ Lưu lịch sử sử dụng
✅ Giới hạn số lần dùng
✅ Thông báo rõ ràng
✅ Responsive trên mobile
✅ Nhấn Enter để submit
✅ Loading state khi xử lý
✅ Error handling đầy đủ
✅ Logging để debug

---

Chúc bạn mua sắm vui vẻ! 🛍️
