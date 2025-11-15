@extends('layouts.client')
@section('content-client')
<link rel="stylesheet" href="{{ asset('asset/client/css/pages-style.css') }}">

<div class="page-header">
  <div class="container">
    <h1>Liên Hệ Với Chúng Tôi</h1>
  </div>
</div>

<div class="container page-content">
  <div class="row">
    <div class="col-md-6">
      <div class="contact-info-box">
        <h4 style="margin-bottom: 25px;">Thông Tin Liên Hệ</h4>
        
        <div class="contact-item">
          <i class="fa fa-map-marker"></i>
          <div class="contact-item-content">
            <strong>Địa chỉ</strong>
            <span>
              Cơ sở 1: 160STORE Tô Hiến Thành - Quận 10<br>
              Cơ sở 2: 160STORE Khuông Việt - Tân Phú
            </span>
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
          <i class="fa fa-envelope"></i>
          <div class="contact-item-content">
            <strong>Email</strong>
            <span>ngduythong1412@gmail.com</span>
          </div>
        </div>
        
        <div class="contact-item">
          <i class="fa fa-clock-o"></i>
          <div class="contact-item-content">
            <strong>Giờ làm việc</strong>
            <span>
              Thứ 2 - Thứ 7: 8:00 - 21:00<br>
              Chủ nhật: 9:00 - 20:00
            </span>
          </div>
        </div>
      </div>
      
      <div class="social-connect">
        <h4>Kết Nối Với Chúng Tôi</h4>
        <div class="social_icon">
          <ul>
            <li><a href="https://www.facebook.com/profile.php?id=100026087362147&mibextid=LQQJ4d" target="_blank"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#"><i class="fa fa-google"></i></a></li>
            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="col-md-6">
      <div class="contact-form-wrapper">
        <h4>Gửi Tin Nhắn</h4>
        
        <form id="contactForm">
          <div class="form-group mb_20">
            <label>Họ và tên <span>*</span></label>
            <input type="text" class="form-control" name="name" required>
          </div>
          
          <div class="form-group mb_20">
            <label>Email <span>*</span></label>
            <input type="email" class="form-control" name="email" required>
          </div>
          
          <div class="form-group mb_20">
            <label>Số điện thoại</label>
            <input type="tel" class="form-control" name="phone">
          </div>
          
          <div class="form-group mb_20">
            <label>Tiêu đề <span>*</span></label>
            <input type="text" class="form-control" name="subject" required>
          </div>
          
          <div class="form-group mb_20">
            <label>Nội dung <span>*</span></label>
            <textarea class="form-control" name="message" rows="5" required></textarea>
          </div>
          
          <button type="submit" class="btn-submit">Gửi tin nhắn</button>
        </form>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12">
      <div class="map-wrapper">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.3254048944!2d106.66525731533395!3d10.786834992313928!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ed23c80767d%3A0x5a981a5efee9fd7d!2zVMO0IEhp4bq_biBUaMOgbmgsIFF1YW4gMTAsIEjhu5MgQ2jDrSBNaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1234567890123!5m2!1svi!2s" 
          width="100%" 
          height="450" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
    this.reset();
});
</script>
@endsection
