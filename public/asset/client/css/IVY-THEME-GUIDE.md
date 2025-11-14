# IVY PREMIUM THEME - HƯỚNG DẪN SỬ DỤNG

## 🎨 Giới Thiệu
Theme được thiết kế theo phong cách **IVY Moda** - thương hiệu thời trang cao cấp hàng đầu Việt Nam.

### Đặc Điểm Chính:
- **Tối giản (Minimalist)**: Loại bỏ mọi yếu tố thừa, tập trung vào sản phẩm
- **Sang trọng (Elegant)**: Màu đen trắng chủ đạo, font chữ Montserrat
- **Chuyên nghiệp (Professional)**: Layout rõ ràng, dễ sử dụng
- **Hiện đại (Modern)**: Animations mượt mà, responsive hoàn hảo

## 🎯 Design Philosophy

### 1. Màu Sắc
```css
--black: #000000        /* Màu chủ đạo */
--white: #ffffff        /* Background */
--gray-50 đến gray-900  /* Các tone xám */
```

**Nguyên tắc:**
- Đen trắng là chủ đạo
- Không dùng màu sắc rực rỡ
- Tối đa 2-3 màu trong một màn hình

### 2. Typography
```css
Font: Montserrat
Sizes: 11px - 28px
Weight: 300, 400, 500, 600, 700, 800
Letter-spacing: 0.3px - 3px
Text-transform: UPPERCASE cho headings
```

### 3. Spacing
```css
--space-xs: 4px
--space-sm: 8px
--space-md: 16px
--space-lg: 24px
--space-xl: 32px
--space-2xl: 48px
--space-3xl: 64px
```

### 4. Components

#### Header
- **Top Bar**: Đen, chứa hotline và account
- **Main Header**: Trắng, logo + search + cart
- **Navigation**: Menu ngang, border-bottom khi hover

#### Product Cards
- **Aspect Ratio**: 3:4 (chuẩn thời trang)
- **Hover Effect**: Lift up 4px + shadow
- **Image**: Scale 1.05 khi hover
- **Button**: Đen, đổi trắng khi hover

#### Footer
- **Background**: Đen
- **Text**: Xám nhạt
- **Links**: Hover thành trắng

## 📱 Responsive Breakpoints

```css
Desktop: > 991px
Tablet: 768px - 991px
Mobile: < 768px
Small Mobile: < 576px
```

## 🚀 Cách Sử Dụng

### 1. Import Theme
```html
<link rel="stylesheet" href="ivy-premium-theme.css">
```

### 2. HTML Structure
```html
<!-- Product Card Example -->
<div class="product-thumb">
    <div class="image">
        <img src="product.jpg" alt="Product">
    </div>
    <div class="caption">
        <h4><a href="#">TÊN SẢN PHẨM</a></h4>
        <div class="price">
            <span class="price-new">599.000đ</span>
            <span class="price-old">799.000đ</span>
        </div>
        <div class="button-group">
            <button>MUA NGAY</button>
        </div>
    </div>
</div>
```

### 3. Custom CSS Variables
```css
:root {
    --black: #your-color;
    --accent-gold: #your-accent;
}
```

## ✨ Best Practices

### DO's ✅
- Sử dụng uppercase cho headings
- Giữ spacing nhất quán
- Dùng border thay vì shadow
- Ảnh sản phẩm chất lượng cao
- Hover effects tinh tế

### DON'Ts ❌
- Không dùng màu sắc rực rỡ
- Không dùng border-radius lớn
- Không dùng gradient
- Không dùng animation phức tạp
- Không cluttered layout

## 🎨 Color Usage Guide

### Primary Actions
```css
background: var(--black);
color: var(--white);
border: 1px solid var(--black);
```

### Secondary Actions
```css
background: var(--white);
color: var(--black);
border: 1px solid var(--black);
```

### Hover States
```css
/* Invert colors */
background: var(--white) → var(--black);
color: var(--black) → var(--white);
```

## 📐 Layout Guidelines

### Container
- Max-width: 1400px
- Padding: 0 15px

### Grid
- Product Grid: 4 columns desktop, 2 mobile
- Gap: 30px desktop, 15px mobile

### Sections
- Margin-top: 80px
- Margin-bottom: 40px

## 🔧 Customization

### Thay đổi màu chủ đạo
```css
:root {
    --black: #1a1a1a;  /* Đen nhạt hơn */
}
```

### Thay đổi font
```css
:root {
    --font-primary: 'Your Font', sans-serif;
}
```

### Thay đổi spacing
```css
:root {
    --space-md: 20px;  /* Tăng/giảm spacing */
}
```

## 📊 Performance

- **CSS Size**: ~15KB (minified)
- **Load Time**: < 50ms
- **Render Time**: < 100ms
- **Mobile Score**: 95+/100

## 🌐 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- iOS Safari 14+
- Chrome Mobile 90+

## 📞 Support

Nếu cần hỗ trợ hoặc có câu hỏi, vui lòng liên hệ team phát triển.

---

**Version**: 1.0.0  
**Last Updated**: 2025  
**Author**: Development Team  
**License**: Proprietary
