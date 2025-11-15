@extends('layouts.client')
@section('content-client')
<link rel="stylesheet" href="{{ asset('asset/client/css/pages-style.css') }}">

<div class="page-header">
  <div class="container">
    <h1>Hệ Thống Cửa Hàng</h1>
  </div>
</div>

<div class="container page-content">
  <div class="row">
    <div class="col-md-6 mb_30">
      <div class="content-section">
        <h4>Cơ Sở 1 - Quận 10</h4>
        
        <div class="store-info">
          <div class="contact-item">
            <i class="fa fa-map-marker"></i>
            <div class="contact-item-content">
              <strong>Địa chỉ</strong>
              <span>160STORE Tô Hiến Thành, Phường 14, Quận 10, TP.HCM</span>
            </div>
          </div>
          
          <div class="contact-item">
            <i class="fa fa-phone"></i>
            <div class="contact-item-content">
              <strong>Điện thoại</strong>
              <span>0782485283</span>
            </div>
          </div>
          
          <div class="contact-item">
            <i class="fa fa-clock-o"></i>
            <div class="contact-item-content">
              <strong>Giờ mở cửa</strong>
              <span>
                Thứ 2 - Thứ 7: 8:00 - 21:00<br>
                Chủ nhật: 9:00 - 20:00
              </span>
            </div>
          </div>
        </div>
        
        <div class="map-wrapper mt_30">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.3254048944!2d106.66525731533395!3d10.786834992313928!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ed23c80767d%3A0x5a981a5efee9fd7d!2zVMO0IEhp4bq_biBUaMOgbmgsIFF1YW4gMTAsIEjhu5MgQ2jDrSBNaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1234567890123!5m2!1svi!2s" 
            width="100%" 
            height="300" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </div>
    
    <div class="col-md-6 mb_30">
      <div class="content-section">
        <h4>Cơ Sở 2 - Tân Phú</h4>
        
        <div class="store-info">
          <div class="contact-item">
            <i class="fa fa-map-marker"></i>
            <div class="contact-item-content">
              <strong>Địa chỉ</strong>
              <span>160STORE Khuông Việt, Phường Phú Trung, Quận Tân Phú, TP.HCM</span>
            </div>
          </div>
          
          <div class="contact-item">
            <i class="fa fa-phone"></i>
            <div class="contact-item-content">
              <strong>Điện thoại</strong>
              <span>0782485283</span>
            </div>
          </div>
          
          <div class="contact-item">
            <i class="fa fa-clock-o"></i>
            <div class="contact-item-content">
              <strong>Giờ mở cửa</strong>
              <span>
                Thứ 2 - Thứ 7: 8:00 - 21:00<br>
                Chủ nhật: 9:00 - 20:00
              </span>
            </div>
          </div>
        </div>
        
        <div class="map-wrapper mt_30">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.0!2d106.62!3d10.80!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDQ4JzAwLjAiTiAxMDbCsDM3JzEyLjAiRQ!5e0!3m2!1svi!2s!4v1234567890123!5m2!1svi!2s" 
            width="100%" 
            height="300" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12">
      <div class="content-section">
        <h4>Hướng Dẫn Đến Cửa Hàng</h4>
        
        <div class="row">
          <div class="col-md-6">
            <h5 style="color: #000; margin-bottom: 15px;">Cơ Sở 1 - Quận 10</h5>
            <ul style="line-height: 1.8;">
              <li><strong>Từ Bến xe Miền Tây:</strong> Di chuyển theo đường Kinh Dương Vương, rẽ phải vào Tô Hiến Thành (khoảng 15 phút)</li>
              <li><strong>Từ Sân bay Tân Sơn Nhất:</strong> Đi theo đường Hoàng Văn Thụ, rẽ trái vào Tô Hiến Thành (khoảng 20 phút)</li>
              <li><strong>Bằng xe buýt:</strong> Tuyến 01, 06, 93 đều đi qua Tô Hiến Thành</li>
            </ul>
          </div>
          
          <div class="col-md-6">
            <h5 style="color: #000; margin-bottom: 15px;">Cơ Sở 2 - Tân Phú</h5>
            <ul style="line-height: 1.8;">
              <li><strong>Từ Bến xe Miền Tây:</strong> Di chuyển theo đường Lũy Bán Bích, rẽ phải vào Khuông Việt (khoảng 10 phút)</li>
              <li><strong>Từ Sân bay Tân Sơn Nhất:</strong> Đi theo đường Trường Chinh, rẽ trái vào Khuông Việt (khoảng 15 phút)</li>
              <li><strong>Bằng xe buýt:</strong> Tuyến 50, 53, 78 đều đi qua Khuông Việt</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12">
      <div class="content-section text-center">
        <h4>Cần Hỗ Trợ?</h4>
        <p style="margin-bottom: 20px;">Nếu bạn cần hỗ trợ tìm đường hoặc có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi!</p>
        <a href="{{ route('user.contact') }}" class="btn-submit">Liên hệ ngay</a>
      </div>
    </div>
  </div>
</div>
@endsection
