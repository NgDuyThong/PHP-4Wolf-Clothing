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
  <link rel="shortcut icon" href="{{ asset('asset/client/images/favicon.png') }}') }}">
  <link rel="apple-touch-icon" href="{{ asset('asset/client/images/apple-touch-icon.png') }}') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('asset/client/images/apple-touch-icon-72x72.png') }}') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('asset/client/images/apple-touch-icon-114x114.png') }}') }}">
  @vite(['resources/client/css/home.css'])
</head>
<style>
  .product-imageblock {
    /* height: 280px !important; */
  }
  .product-thumb__img-product{
    height: 400px !important;
    object-fit: cover !important;
  }
  .cart__shopping {
    height: 40px;
  }

  .shopcart i:hover {
    color: #fa5460 !important;
    cursor: pointer;
  }

  .login, .register {
    font-size: 15px;
    font-weight: 500;
    color: #fff !important;
  }

  .login:hover {
    color: #fa5460 !important;
    cursor: pointer;
  }
  .none-hover:hover{
    color: unset;
  }
  .button_group{
      display: flex;
      justify-content: center;
      padding-top: 10px; 
  }
  .invalid-feedback{
    color: #fa5460;
  }
</style>
<body>
  <!-- =====  LODER  ===== -->
  <div class="loder"></div>
  <div class="wrapper">
    <!-- =====  HEADER START  ===== -->
    <header id="header">
      <div class="header-top" style="background: #000;">
        <div class="container">
          <div class="row">
            <div class="col-xs-12 col-sm-4">
              <div class="header-top-left">
                <div class="contact"><a>Call now !</a> <span class="hidden-xs hidden-sm hidden-md">0346792997</span></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-8">
              <ul class="header-top-right text-right">
                @if (!Auth::check())
                  <li class="account">
                    <a href="{{ route('user.login') }}" class="login">
                      <i class="far fa-user"></i> Đăng Nhập
                    </a>
                  </li>
                  <li class="account">
                    <a href="{{ route('user.register') }}" class="login">
                      <i class="fas fa-key"></i> Đăng Ký
                    </a>
                  </li>
                @else
                  <li class="language dropdown"> 
                    <span class="dropdown-toggle login" id="dropdownMenu1" data-toggle="dropdown"
                      aria-haspopup="true" aria-expanded="false" role="button"><i style="padding-right: 5px;" class="fas fa-user"></i>Thông Tin Cá Nhân 
                      <span class="caret"></span>
                    </span>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                      <li><a href="{{ route('profile.index') }}">Thông tin cá nhân</a></li>
                      <li><a href="{{ route('order_history.index') }}">Lịch sử mua hàng</a></li>
                      <li><a href="{{ route('user.logout') }}">Đăng xuất</a></li>
                    </ul>
                  </li>
              @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="header">
        <div class="container">
          <div class="row">
            <div class="navbar-header col-xs-6 col-sm-4" style="text-align: unset;"> 
                <a class="navbar-brand none-hover" href="{{ route('user.home') }}"> 
                  {{-- <img alt="OYEENok"
                    src="{{ asset('asset/client/images/logo.png') }}">  --}}
                  <h2 style="font-weight: 600;">Truong Thuy Store</h2>
                </a> 
              </div>
            <div class="col-xs-12 col-sm-4">
              <div class="main-search mt_40">
                <form action="{{ route('user.search') }}" method="get">
                <input id="search-input" name="keyword" placeholder="Tìm kiếm" class="form-control input-lg"
                  autocomplete="off" type="text">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
                </span>
              </form>
              </div>
            </div>
            <div class="col-xs-6 col-sm-4 shopcart">
              <div id="cart" class="btn-group btn-block mtb_40">                
                <a style="float: right;padding-left: 30px;" href="{{ route('cart.index') }}">
                  <i class="fas fa-shopping-cart" style="font-size: 25px; color: #fff;"></i>
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
              <li><a href="#">Thông tin giao hàng</a></li>
              <li><a href="#">Chính sách bảo mật</a></li>
              <li><a href="#">Điều khoản & Điều kiện</a></li>
              <li><a href="#">Liên hệ với chúng tôi</a></li>
            </ul>
          </div>
          <div class="col-md-3 footer-block">
            <h6 class="footer-title ptb_20">Dịch Vụ</h6>
            <ul>
              <li><a href="#">Bản đồ</a></li>
              <li><a href="#">Danh sách yêu thích</a></li>
              <li><a href="#">Tài khoản của tôi</a></li>
              <li><a href="#">Lịch sử đặt hàng</a></li>
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
              <li>Cơ sở 1 317 Lý Thường Kiệt, Phù Lý, Hà Nam</li>
              <li>Cơ sở 2 Nga Bắp, Liêm Thuận, Thanh Liêm, Hà Nam</li>
              <li>0346792997
              </li>
              <li>honglanh3122002@gmail.com</li>
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
                  <li><a href="https://www.facebook.com/profile.php?id=100026087362147&mibextid=LQQJ4d"><i class="fa fa-facebook"></i></a></li>
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
  <a id="scrollup"></a>
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
</body>

</html>