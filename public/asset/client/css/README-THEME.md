# HƯỚNG DẪN SỬ DỤNG THEME

## File CSS Chính
- **master-theme.css** - File CSS chính, chứa toàn bộ styling cho website

## Các File CSS Cũ (Có thể xóa)
Các file sau đây đã được tích hợp vào `master-theme.css` và có thể xóa để giảm dung lượng:
- ivy-style-theme.css
- theme-modern.css
- professional-theme.css
- homepage-pro.css
- final-polish.css
- theme-enhancements.css
- ultimate-theme.css

## Tính Năng Của Master Theme

### 1. Design System
- Màu sắc hiện đại với gradient đẹp mắt
- Typography rõ ràng, dễ đọc
- Spacing và layout nhất quán
- Border radius và shadows chuyên nghiệp

### 2. Components
- **Header**: Sticky header với search bar và cart icon
- **Navigation**: Menu responsive với hover effects
- **Product Cards**: Card design hiện đại với hover animations
- **Footer**: Footer đầy đủ với social icons
- **Buttons**: Nhiều style buttons với gradient
- **Forms**: Form controls đẹp với focus states
- **Alerts**: Alert boxes với màu sắc phù hợp

### 3. Responsive Design
- Mobile-first approach
- Breakpoints: 576px, 768px, 991px
- Touch-friendly trên mobile
- Optimized cho mọi kích thước màn hình

### 4. Animations
- Smooth transitions
- Hover effects
- Loading animations
- Scroll animations

## Cách Sử Dụng

### Trong Layout File
```php
<link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/master-theme.css') }}">
```

### Custom CSS Variables
Bạn có thể override các biến CSS trong file riêng:
```css
:root {
    --primary: #your-color;
    --secondary: #your-color;
}
```

## Tối Ưu Hóa
1. File đã được tối ưu cho performance
2. Sử dụng CSS variables để dễ dàng customize
3. Responsive và mobile-friendly
4. Cross-browser compatible

## Hỗ Trợ
- Chrome, Firefox, Safari, Edge (latest versions)
- iOS Safari, Chrome Mobile
- Responsive từ 320px trở lên
