# Hướng Dẫn Trạng Thái Đơn Hàng

## Tổng Quan

Hệ thống quản lý đơn hàng hiện có **13 trạng thái** để theo dõi chi tiết quy trình xử lý đơn hàng từ lúc tạo đến khi hoàn thành hoặc hủy.

## Danh Sách Trạng Thái

| Mã | Tên Trạng Thái | Màu Badge | Mô Tả |
|----|----------------|-----------|-------|
| 0 | Chờ xử lý | Vàng (warning) | Đơn hàng mới được tạo, chờ admin xử lý |
| 1 | Đã xác nhận | Xanh dương (info) | Admin đã xác nhận đơn hàng |
| 2 | Đã hủy | Đỏ (danger) | Đơn hàng đã bị hủy |
| 3 | Đã nhận hàng | Xanh lá (success) | Khách hàng đã nhận hàng thành công |
| 4 | Đang giao hàng | Xanh (primary) | Đơn hàng đang được giao đến khách |
| 5 | Đang chuẩn bị hàng | Xanh dương (info) | Admin đang đóng gói sản phẩm |
| 6 | Đã giao cho ĐVVC | Xanh (primary) | Đã chuyển cho đơn vị vận chuyển |
| 7 | Giao hàng thất bại | Vàng (warning) | Không liên lạc được khách hoặc địa chỉ sai |
| 8 | Chờ thanh toán | Xám (secondary) | Chờ khách hàng thanh toán |
| 9 | Đã thanh toán | Xanh lá (success) | Đã xác nhận thanh toán |
| 10 | Hoàn trả/Đổi hàng | Vàng (warning) | Khách yêu cầu đổi/trả hàng |
| 11 | Đã hoàn tiền | Xanh dương (info) | Đã hoàn tiền cho khách hàng |
| 12 | Chờ xác nhận hủy | Xám (secondary) | Khách yêu cầu hủy, chờ admin xác nhận |

## Luồng Xử Lý Đơn Hàng

### 1. Luồng Thành Công (COD - Thanh toán khi nhận hàng)

```
0. Chờ xử lý
   ↓
1. Đã xác nhận
   ↓
5. Đang chuẩn bị hàng
   ↓
6. Đã giao cho ĐVVC
   ↓
4. Đang giao hàng
   ↓
3. Đã nhận hàng
   ↓
9. Đã thanh toán
```

### 2. Luồng Thành Công (Chuyển khoản trước)

```
0. Chờ xử lý
   ↓
8. Chờ thanh toán
   ↓
9. Đã thanh toán
   ↓
1. Đã xác nhận
   ↓
5. Đang chuẩn bị hàng
   ↓
6. Đã giao cho ĐVVC
   ↓
4. Đang giao hàng
   ↓
3. Đã nhận hàng
```

### 3. Luồng Giao Hàng Thất Bại

```
4. Đang giao hàng
   ↓
7. Giao hàng thất bại
   ↓
4. Đang giao hàng (giao lại)
   ↓
3. Đã nhận hàng
```

### 4. Luồng Hủy Đơn

```
0. Chờ xử lý
   ↓
12. Chờ xác nhận hủy (khách yêu cầu)
   ↓
2. Đã hủy
   ↓
11. Đã hoàn tiền (nếu đã thanh toán)
```

### 5. Luồng Đổi/Trả Hàng

```
3. Đã nhận hàng
   ↓
10. Hoàn trả/Đổi hàng
   ↓
11. Đã hoàn tiền
```

## Sử Dụng Trong Code

### 1. Lấy Constant Trạng Thái

```php
use App\Models\Order;

// Lấy mã trạng thái
$pendingStatus = Order::STATUS_ORDER['pending'];        // 0
$confirmedStatus = Order::STATUS_ORDER['confirmed'];    // 1
$shippingStatus = Order::STATUS_ORDER['shipping'];      // 4
```

### 2. Sử Dụng Helper

```php
use App\Helpers\OrderStatusHelper;

// Lấy tên trạng thái
$statusName = OrderStatusHelper::getStatusName(0);  // "Chờ xử lý"

// Lấy class badge
$badgeClass = OrderStatusHelper::getStatusBadgeClass(0);  // "badge bg-warning"

// Lấy tất cả trạng thái
$allStatuses = OrderStatusHelper::getAllStatuses();

// Kiểm tra có thể chuyển trạng thái không
$canTransition = OrderStatusHelper::canTransition(0, 1);  // true (Chờ xử lý -> Đã xác nhận)
```

### 3. Hiển Thị Trong View

```blade
@if ($order->order_status == 0)
    <span class="badge bg-warning">Chờ xử lý</span>
@elseif($order->order_status == 1)
    <span class="badge bg-info">Đã xác nhận</span>
@endif
```

Hoặc sử dụng helper:

```blade
<span class="{{ App\Helpers\OrderStatusHelper::getStatusBadgeClass($order->order_status) }}">
    {{ App\Helpers\OrderStatusHelper::getStatusName($order->order_status) }}
</span>
```

## Quy Tắc Chuyển Đổi Trạng Thái

### Trạng thái có thể chuyển:

- **Chờ xử lý (0)** → Đã xác nhận (1), Chờ thanh toán (8), Chờ xác nhận hủy (12), Đã hủy (2)
- **Chờ thanh toán (8)** → Đã thanh toán (9), Đã hủy (2)
- **Đã xác nhận (1)** → Đang chuẩn bị hàng (5), Đã hủy (2)
- **Đang chuẩn bị hàng (5)** → Đã giao cho ĐVVC (6), Đã hủy (2)
- **Đã giao cho ĐVVC (6)** → Đang giao hàng (4)
- **Đang giao hàng (4)** → Đã nhận hàng (3), Giao hàng thất bại (7)
- **Giao hàng thất bại (7)** → Đang giao hàng (4), Đã hủy (2)
- **Đã nhận hàng (3)** → Hoàn trả/Đổi hàng (10)
- **Hoàn trả/Đổi hàng (10)** → Đã hoàn tiền (11)
- **Chờ xác nhận hủy (12)** → Đã hủy (2), Đã xác nhận (1)

### Trạng thái kết thúc (không thể chuyển):

- Đã hủy (2)
- Đã hoàn tiền (11)
- Đã thanh toán (9) - sau khi đã nhận hàng

## Cập Nhật Database

Nếu bạn đã có dữ liệu cũ, cần cập nhật:

```sql
-- Không cần cập nhật vì các giá trị 0,1,2,3,4 vẫn giữ nguyên
-- Chỉ có thêm các trạng thái mới 5,6,7,8,9,10,11,12
```

## Lưu Ý

1. **Không xóa trạng thái cũ**: Các trạng thái 0,1,2,3,4 vẫn giữ nguyên để tương thích với dữ liệu cũ
2. **Validation**: Nên validate trạng thái trước khi cập nhật
3. **Logging**: Nên log lại mỗi lần thay đổi trạng thái để tracking
4. **Notification**: Gửi email/SMS cho khách khi trạng thái thay đổi
5. **Permission**: Chỉ admin mới được phép thay đổi trạng thái

## Tính Năng Mở Rộng (Tương Lai)

1. **Order Status History**: Lưu lịch sử thay đổi trạng thái
2. **Auto Status Update**: Tự động cập nhật trạng thái dựa trên API đơn vị vận chuyển
3. **Status Timeline**: Hiển thị timeline trạng thái cho khách hàng
4. **Email Notification**: Gửi email tự động khi trạng thái thay đổi
5. **SMS Notification**: Gửi SMS thông báo trạng thái đơn hàng
