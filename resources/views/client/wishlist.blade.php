@extends('layouts.client')
@section('content-client')
<link rel="stylesheet" href="{{ asset('asset/client/css/pages-style.css') }}">

<div class="page-header">
  <div class="container">
    <h1>Danh Sách Yêu Thích</h1>
  </div>
</div>

<div class="container page-content">
  <div class="row">
    <div class="col-12">
      <div class="content-section">
        <h4>Sản Phẩm Yêu Thích Của Bạn (<span id="wishlist-count">0</span>)</h4>
        
        <div class="wishlist-empty text-center" id="wishlist-empty" style="padding: 60px 20px;">
          <i class="fa fa-heart-o" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
          <h3 style="color: #999; margin-bottom: 15px;">Danh sách yêu thích trống</h3>
          <p style="color: #666; margin-bottom: 30px;">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
          <a href="{{ route('user.home') }}" class="btn-submit">Khám phá sản phẩm</a>
        </div>
        
        <div class="wishlist-products" id="wishlist-products" style="display: none;">
          <div class="row" id="products-container">
            <!-- Products will be loaded here by JavaScript -->
          </div>
        </div>
      </div>
      
      <div class="content-section" id="suggestions-section" style="display: none;">
        <h4>Gợi Ý Cho Bạn</h4>
        <p>Dựa trên sở thích của bạn, chúng tôi nghĩ bạn sẽ thích những sản phẩm này:</p>
        
        <div class="row mt_30" id="suggestions-container">
          <!-- Suggested products will be loaded here -->
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadWishlist();
    loadSuggestions();
});

function loadWishlist() {
    // Lấy wishlist từ localStorage
    let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    
    console.log('Wishlist IDs:', wishlist); // Debug
    
    // Cập nhật số lượng
    document.getElementById('wishlist-count').textContent = wishlist.length;
    
    if (wishlist.length === 0) {
        document.getElementById('wishlist-empty').style.display = 'block';
        document.getElementById('wishlist-products').style.display = 'none';
        return;
    }
    
    document.getElementById('wishlist-empty').style.display = 'none';
    document.getElementById('wishlist-products').style.display = 'block';
    
    // Fetch products data
    fetch(`/api/products?ids=${wishlist.join(',')}`)
        .then(response => {
            console.log('Response status:', response.status); // Debug
            return response.json();
        })
        .then(products => {
            console.log('Products loaded:', products); // Debug
            displayProducts(products);
        })
        .catch(error => {
            console.error('Error loading wishlist:', error);
            alert('Có lỗi khi tải danh sách yêu thích. Vui lòng thử lại!');
        });
}

function displayProducts(products) {
    const container = document.getElementById('products-container');
    container.innerHTML = '';
    
    if (!products || products.length === 0) {
        container.innerHTML = '<div class="col-12 text-center"><p>Không tìm thấy sản phẩm</p></div>';
        return;
    }
    
    products.forEach(product => {
        const productHtml = `
            <div class="col-md-3 col-sm-6 mb_30">
                <div class="product-item" style="border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; transition: all 0.3s ease; position: relative;">
                    <div class="product-image" style="position: relative; overflow: hidden; background: #f5f5f5;">
                        <a href="/product-detail/${product.id}">
                            <img src="/asset/client/images/products/small/${product.image}" 
                                 alt="${product.name}" 
                                 style="width: 100%; height: 300px; object-fit: cover;"
                                 onerror="this.src='/asset/client/images/no-image.png'">
                        </a>
                        <button onclick="removeFromWishlist(${product.id})" 
                                style="position: absolute; top: 10px; right: 10px; background: #fff; border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 10;"
                                title="Xóa khỏi yêu thích">
                            <i class="fa fa-times" style="color: #000;"></i>
                        </button>
                    </div>
                    <div class="product-info" style="padding: 20px; text-align: center;">
                        <h5 style="margin-bottom: 10px; min-height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            <a href="/product-detail/${product.id}" style="color: #000; text-decoration: none;">${product.name}</a>
                        </h5>
                        <p style="color: #e74c3c; font-weight: 600; font-size: 18px; margin-bottom: 15px;">
                            ${formatPrice(product.price)}đ
                        </p>
                        <a href="/product-detail/${product.id}" class="btn-submit" style="padding: 8px 20px; font-size: 14px; display: inline-block; text-decoration: none;">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += productHtml;
    });
}

function loadSuggestions() {
    // Load 4 random products as suggestions
    fetch('/api/products/random?limit=4')
        .then(response => response.json())
        .then(products => {
            if (products && products.length > 0) {
                displaySuggestions(products);
                document.getElementById('suggestions-section').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading suggestions:', error);
        });
}

function displaySuggestions(products) {
    const container = document.getElementById('suggestions-container');
    container.innerHTML = '';
    
    products.forEach(product => {
        const productHtml = `
            <div class="col-md-3 col-sm-6 mb_30">
                <div class="product-suggestion text-center" style="padding: 20px; background: #f8f8f8; border-radius: 6px; transition: all 0.3s ease;">
                    <a href="/product-detail/${product.id}" style="text-decoration: none;">
                        <div style="height: 200px; background: #fff; border-radius: 4px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="/asset/client/images/products/small/${product.image}" 
                                 alt="${product.name}"
                                 style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                 onerror="this.parentElement.innerHTML='<i class=\\'fa fa-image\\' style=\\'font-size: 40px; color: #999;\\'></i>'">
                        </div>
                        <h5 style="margin-bottom: 10px; color: #000; min-height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">${product.name}</h5>
                        <p style="color: #e74c3c; font-weight: 600; margin-bottom: 15px;">${formatPrice(product.price)}đ</p>
                    </a>
                    <a href="/product-detail/${product.id}" class="btn-submit" style="padding: 8px 20px; font-size: 14px; text-decoration: none; display: inline-block;">Xem chi tiết</a>
                </div>
            </div>
        `;
        container.innerHTML += productHtml;
    });
}

function removeFromWishlist(productId) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
        return;
    }
    
    let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    wishlist = wishlist.filter(id => id !== productId);
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    
    // Update header count
    if (typeof updateWishlistCount === 'function') {
        updateWishlistCount();
    }
    
    loadWishlist();
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}
</script>
@endsection
