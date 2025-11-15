<!DOCTYPE html>
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->

<head>
  <!-- =====  BASIC PAGE NEEDS  ===== -->
  <meta charset="utf-8">
  <title>{{ setting_website()->name }}</title>
  <!-- =====  SEO MATE  ===== -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="distribution" content="global">
  <meta name="revisit-after" content="2 Days">
  <meta name="robots" content="ALL">
  <meta name="rating" content="8 YEARS">
  <meta name="Language" content="en-us">
  <meta name="GOOGLEBOT" content="NOARCHIVE">
  <!-- =====  MOBILE SPECIFICATION  ===== -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="viewport" content="width=device-width">
  <!-- =====  CSS  ===== -->
  <link rel="stylesheet" href="{{ asset('asset/admin/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/bootstrap.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/magnific-popup.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/owl.carousel.css') }}">
  <!-- IVY Premium Theme - Phong cách thời trang cao cấp -->
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/ivy-premium-theme.css') }}">
  <!-- Color Override - Remove all red/blue colors - MUST BE LAST -->
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/color-override.css') }}">
  <link rel="shortcut icon" href="{{ asset('asset/client/images/favicon.png') }}') }}">
  <link rel="apple-touch-icon" href="{{ asset('asset/client/images/apple-touch-icon.png') }}') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('asset/client/images/apple-touch-icon-72x72.png') }}') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('asset/client/images/apple-touch-icon-114x114.png') }}') }}">
  @vite(['resources/client/css/home.css'])
</head>
<style>
  /* Custom inline styles */
  .none-hover:hover {
    color: inherit !important;
  }
  
  .invalid-feedback {
    color: #ef4444 !important;
    font-size: 14px !important;
    margin-top: 4px !important;
  }
</style>
<body>
  <!-- =====  LODER  ===== -->
  <div class="loder"></div>
  <div class="wrapper">
    <!-- =====  HEADER START  ===== -->
    <header id="header">
      <div class="header">
        <div class="container">
          <div class="row header-main-row">
            <div class="navbar-header col-xs-6 col-sm-2"> 
                <a class="navbar-brand none-hover" href="{{ route('user.home') }}"> 
                  <img alt="Store Logo" src="{{ asset('asset/client/images/logo.png') }}"> 
                </a> 
              </div>
            <div class="col-xs-12 col-sm-6">
              <div class="main-search">
                <form action="{{ route('user.search') }}" method="get">
                  <input id="search-input" name="keyword" placeholder="Tìm kiếm sản phẩm..." class="form-control"
                    autocomplete="off" type="text">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </span>
                </form>
              </div>
            </div>
            <div class="col-xs-6 col-sm-4">
              <div class="header-actions">
                @if (!Auth::check())
                  <a href="{{ route('user.login') }}" class="header-action-link">
                    <i class="far fa-user"></i>
                    <span>Đăng Nhập</span>
                  </a>
                  <a href="{{ route('user.register') }}" class="header-action-link">
                    <i class="fas fa-user-plus"></i>
                    <span>Đăng Ký</span>
                  </a>
                @else
                  <div class="dropdown header-dropdown">
                    <a href="#" class="header-action-link dropdown-toggle" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-user"></i>
                      <span>Tài Khoản</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
                      <li><a href="{{ route('profile.index') }}">Thông tin cá nhân</a></li>
                      <li><a href="{{ route('order_history.index') }}">Lịch sử mua hàng</a></li>
                      <li><a href="{{ route('user.logout') }}">Đăng xuất</a></li>
                    </ul>
                  </div>
                @endif
                <a href="{{ route('user.wishlist') }}" class="header-action-link" title="Yêu thích" id="wishlist-header-link">
                  <i class="fa fa-heart"></i>
                  <span>Yêu Thích (<span id="wishlist-header-count">0</span>)</span>
                </a>
                <a href="{{ route('cart.index') }}" class="header-action-link" title="Giỏ hàng">
                  <i class="fas fa-shopping-cart"></i>
                  <span>Giỏ Hàng</span>
                </a>
              </div>
            </div>
          </div>
          <nav class="navbar">
            <p>Menu</p>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse"> <span
                class="i-bar"><i class="fa fa-bars"></i></span></button>
            <div class="collapse navbar-collapse js-navbar-collapse">
              <ul id="menu" class="nav navbar-nav">
                <li>
                  <a href="{{ route('user.home') }}">Trang Chủ</a>
                </li>
                @foreach (category_header() as $category)
                  <li>
                    <a href="{{ route('user.products', $category->slug) }}">{{ $category->name }}</a>
                  </li>
                @endforeach
                <li>
                    <a href="{{ route('user.introduction') }}">Giới Thiệu</a>
                </li>
              </ul>
            </div>
            <!-- /.nav-collapse -->
          </nav>
        </div>
      </div>
    </header>
    <!-- =====  HEADER END  ===== -->
    
    <!-- =====  CONTAINER START  ===== -->
    @yield('content-client')
    <div class="container">
      <div id="brand_carouse" class="ptb_30 text-center">
        <div class="type-01">
          <div class="heading-part mb_10 ">
            <h2 class="main_title">Thương Hiệu</h2>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="brand owl-carousel ptb_20">
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand1.png") }}" alt="Disney" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand2.png") }}" alt="Dell" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand3.png") }}" alt="Harley" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand4.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand5.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand6.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand7.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand8.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img src="{{ asset("asset/client/images/brand/brand9.png") }}" alt="Canon" class="img-responsive" /></a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- =====  CONTAINER END  ===== -->
    <!-- =====  FOOTER START  ===== -->
    <div class="footer pt_60">
      <div class="container">
        <div class="row">
          {{-- <div class="footer-top pb_60 mb_30">
            <div class="col-xs-12 col-sm-6">
              <div class="footer-logo"> <a href="{{ route('user.home') }}"> <img src="{{ asset('asset/client/images/footer-logo.png') }}" alt="OYEENok"> </a>
              </div>
              <div class="footer-desc">Lorem ipsum doLorem ipsum dolor sit amet, consectetur adipisicagna.</div>
            </div>
            <!-- =====  testimonial  ===== -->
            <div class="col-xs-12 col-sm-6">
              <div class="Testimonial">
                <div class="client owl-carousel">
                  <div class="item client-detail">
                    <div class="client-avatar"> <img alt="" src="{{ asset('asset/client/images/user1.jpg') }}"> </div>
                    <div class="client-title"><strong>joseph Lui</strong></div>
                    <div class="client-designation mb_10"> - php Developer</div>
                    <p><i class="fa fa-quote-left" aria-hidden="true"></i>Lorem ipsum dolor sit amet, volumus oporteat
                      his at sea in Rem ipsum dolor sit amet, sea in odio ..</p>
                  </div>
                  <div class="item client-detail">
                    <div class="client-avatar"> <img alt="" src="{{ asset('asset/client/images/user2.jpg') }}"> </div>
                    <div class="client-title"><strong>joseph Lui</strong></div>
                    <div class="client-designation mb_10"> - php Developer</div>
                    <p><i class="fa fa-quote-left" aria-hidden="true"></i>Lorem ipsum dolor sit amet, volumus oporteat
                      his at sea in Rem ipsum dolor sit amet, sea in odio ..</p>
                  </div>
                  <div class="item client-detail">
                    <div class="client-avatar"> <img alt="" src="{{ asset('asset/client/images/user3.jpg') }}"> </div>
                    <div class="client-title"><strong>joseph Lui</strong></div>
                    <div class="client-designation mb_10"> - php Developer</div>
                    <p><i class="fa fa-quote-left" aria-hidden="true"></i>Lorem ipsum dolor sit amet, volumus oporteat
                      his at sea in Rem ipsum dolor sit amet, sea in odio ..</p>
                  </div>
                </div>
              </div>
            </div>
            <!-- =====  testimonial end ===== -->
          </div> --}}
        </div>
        <div class="row">
          <div class="col-md-3 footer-block">
            <h6 class="footer-title ptb_20">Về Chúng Tôi</h6>
            <ul>
              <li><a href="{{ route('user.shipping_info') }}">Thông tin giao hàng</a></li>
              <li><a href="{{ route('user.privacy_policy') }}">Chính sách bảo mật</a></li>
              <li><a href="{{ route('user.terms_conditions') }}">Điều khoản & Điều kiện</a></li>
              <li><a href="{{ route('user.contact') }}">Liên hệ với chúng tôi</a></li>
            </ul>
          </div>
          <div class="col-md-3 footer-block">
            <h6 class="footer-title ptb_20">Dịch Vụ</h6>
            <ul>
              <li><a href="{{ route('user.store_locations') }}">Bản đồ</a></li>
              <li><a href="{{ route('user.wishlist') }}">Danh sách yêu thích</a></li>
              <li><a href="{{ route('profile.index') }}">Tài khoản của tôi</a></li>
              <li><a href="{{ route('order_history.index') }}">Lịch sử đặt hàng</a></li>
            </ul>
          </div>
          <div class="col-md-3 footer-block">
            <h6 class="footer-title ptb_20">Tiện ích bổ sung</h6>
            <ul>
              <li><a href="#">Thương hiệu</a></li>
              <li><a href="#">Giấy chứng nhận quà tặng</a></li>
              <li><a href="#">Khuyến mãi</a></li>
              <li><a href="#">Bản tin</a></li>
            </ul>
          </div>
          <div class="col-md-3 footer-block">
            <h6 class="footer-title ptb_20">Liên Hệ</h6>
            <ul>
              <li>Cơ sở 1 160STORE Tô Hiến Thành - Quận 10</li>
              <li>Cơ sở 2 160STORE Khuông Việt - Tân Phú</li>
              <li>0782485283
              </li>
              <li>ngduythong1412@gmail.com</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-bottom mt_60 ptb_20">
        <div class="container">
          <div class="row">
            <div class="col-sm-4">
              <div class="social_icon">
                <ul>
                  <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="#"><i class="fa fa-google"></i></a></li>
                  <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                  <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fa fa-rss"></i></a></li>
                </ul>
              </div>
            </div>
            <div class="col-sm-4">
            </div>
            <div class="col-sm-4">
              <div class="payment-icon text-right">
                <ul>
                  <li><i class="fa fa-cc-paypal "></i></li>
                  <li><i class="fa fa-cc-visa"></i></li>
                  <li><i class="fa fa-cc-discover"></i></li>
                  <li><i class="fa fa-cc-mastercard"></i></li>
                  <li><i class="fa fa-cc-amex"></i></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- =====  FOOTER END  ===== -->
  </div>
  <a id="scrollup"><i class="fa fa-chevron-up"></i></a>
  @if (Session::has('success'))
    <span id="toast__js" message="{{ session('success') }}" type="success"></span>
  @elseif (Session::has('error'))
    <span id="toast__js" message="{{ session('error') }}" type="error"></span>
  @endif
  <script src="{{ asset('asset/client/js/jQuery_v3.1.1.min.js') }}"></script>
  <script src="{{ asset('asset/client/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('asset/client/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('asset/client/js/jquery.magnific-popup.js') }}"></script>
  <script src="{{ asset('asset/client/js/jquery.firstVisitPopup.js') }}"></script>
  <script src="{{ asset('asset/client/js/custom.js') }}"></script>
  <script src="{{ asset('asset/admin/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <script src="{{ asset('asset/admin/plugins/jquery-validation/jquery.validate.js') }}"></script>
  
  @vite(['resources/admin/js/toast-message.js'])
  
  <script>
    // Force black/white color scheme - NO RED EVER
    document.addEventListener('DOMContentLoaded', function() {
      
      // Function to apply correct colors
      function applyCorrectColors() {
        // Get all menu items
        const menuItems = document.querySelectorAll('#menu.navbar-nav > li > a, .navbar-nav > li > a, .nav > li > a');
        
        menuItems.forEach(item => {
          // Remove any inline styles with red
          const style = item.getAttribute('style');
          if (style) {
            const cleanStyle = style
              .replace(/background(-color)?:\s*#e40001[^;]*/gi, '')
              .replace(/background(-color)?:\s*red[^;]*/gi, '')
              .replace(/background(-color)?:\s*#fa5460[^;]*/gi, '')
              .replace(/color:\s*#e40001[^;]*/gi, '')
              .replace(/color:\s*red[^;]*/gi, '')
              .replace(/color:\s*#fa5460[^;]*/gi, '');
            item.setAttribute('style', cleanStyle);
          }
          
          // Force correct default colors
          item.style.setProperty('color', '#000000', 'important');
          item.style.setProperty('background-color', 'transparent', 'important');
          item.style.setProperty('background', 'transparent', 'important');
          
          // Add hover event listeners
          item.addEventListener('mouseenter', function() {
            this.style.setProperty('color', '#ffffff', 'important');
            this.style.setProperty('background-color', '#000000', 'important');
            this.style.setProperty('background', '#000000', 'important');
            this.style.setProperty('border-bottom-color', '#000000', 'important');
          });
          
          item.addEventListener('mouseleave', function() {
            // Immediately set to default colors - NO DELAY
            const isActive = this.parentElement.classList.contains('active');
            
            if (!isActive) {
              // Force immediate color change
              this.style.cssText = 'color: #000000 !important; background-color: transparent !important; background: transparent !important; border-bottom-color: transparent !important; transition: none !important;';
            } else {
              // Keep active state
              this.style.cssText = 'color: #ffffff !important; background-color: #000000 !important; background: #000000 !important; border-bottom-color: #000000 !important; transition: none !important;';
            }
          });
        });
        
        // Set active menu item
        const activeItems = document.querySelectorAll('#menu.navbar-nav > li.active > a, .navbar-nav > li.active > a');
        activeItems.forEach(item => {
          item.style.setProperty('color', '#ffffff', 'important');
          item.style.setProperty('background-color', '#000000', 'important');
          item.style.setProperty('background', '#000000', 'important');
          item.style.setProperty('border-bottom-color', '#000000', 'important');
        });
      }
      
      // Apply colors immediately
      applyCorrectColors();
      
      // Re-apply after a short delay to catch any dynamic content
      setTimeout(applyCorrectColors, 100);
      setTimeout(applyCorrectColors, 500);
      
      // Watch for any style changes and fix them
      const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
            const el = mutation.target;
            if (el.matches('#menu.navbar-nav > li > a, .navbar-nav > li > a, .nav > li > a')) {
              const style = el.getAttribute('style');
              if (style && (style.includes('#e40001') || style.includes('red') || style.includes('#fa5460'))) {
                // Remove red colors immediately
                const cleanStyle = style
                  .replace(/background(-color)?:\s*#e40001[^;]*/gi, 'background-color: #000000')
                  .replace(/background(-color)?:\s*red[^;]*/gi, 'background-color: #000000')
                  .replace(/background(-color)?:\s*#fa5460[^;]*/gi, 'background-color: #000000')
                  .replace(/color:\s*#e40001[^;]*/gi, 'color: #ffffff')
                  .replace(/color:\s*red[^;]*/gi, 'color: #ffffff')
                  .replace(/color:\s*#fa5460[^;]*/gi, 'color: #ffffff');
                el.setAttribute('style', cleanStyle);
              }
            }
          }
        });
      });
      
      observer.observe(document.body, {
        attributes: true,
        attributeFilter: ['style', 'class'],
        subtree: true
      });
    });
    
    // Update wishlist count in header
    function updateWishlistCount() {
      const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
      const countElement = document.getElementById('wishlist-header-count');
      if (countElement) {
        countElement.textContent = wishlist.length;
      }
    }
    
    // Update on page load
    document.addEventListener('DOMContentLoaded', updateWishlistCount);
    
    // Update when localStorage changes (from other tabs)
    window.addEventListener('storage', function(e) {
      if (e.key === 'wishlist') {
        updateWishlistCount();
      }
    });
  </script>
</body>

</html>