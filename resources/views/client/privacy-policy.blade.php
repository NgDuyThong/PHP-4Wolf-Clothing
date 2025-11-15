@extends('layouts.client')
@section('content-client')
<link rel="stylesheet" href="{{ asset('asset/client/css/pages-style.css') }}">

<div class="page-header">
  <div class="container">
    <h1>Chính Sách Bảo Mật</h1>
  </div>
</div>

<div class="container page-content">
  <div class="row">
    <div class="col-12">
      <div class="content-section">
        <h4>1. Thu Thập Thông Tin</h4>
        <p>Chúng tôi thu thập thông tin cá nhân của bạn khi bạn:</p>
        <ul>
          <li>Đăng ký tài khoản trên website</li>
          <li>Đặt hàng sản phẩm</li>
          <li>Đăng ký nhận bản tin</li>
          <li>Liên hệ với chúng tôi</li>
        </ul>
      </div>
      
      <div class="content-section">
        <h4>2. Thông Tin Được Thu Thập</h4>
        <ul>
          <li>Họ tên</li>
          <li>Địa chỉ email</li>
          <li>Số điện thoại</li>
          <li>Địa chỉ giao hàng</li>
          <li>Thông tin thanh toán</li>
        </ul>
      </div>
      
      <div class="content-section">
        <h4>3. Mục Đích Sử Dụng Thông Tin</h4>
        <ul>
          <li>Xử lý và giao hàng đơn hàng</li>
          <li>Gửi thông báo về đơn hàng</li>
          <li>Cải thiện dịch vụ khách hàng</li>
          <li>Gửi thông tin khuyến mãi (nếu bạn đồng ý)</li>
          <li>Phân tích và cải thiện website</li>
        </ul>
      </div>
      
      <div class="content-section">
        <h4>4. Bảo Mật Thông Tin</h4>
        <p>Chúng tôi cam kết bảo mật thông tin cá nhân của bạn bằng cách:</p>
        <ul>
          <li>Sử dụng công nghệ mã hóa SSL</li>
          <li>Lưu trữ dữ liệu trên máy chủ an toàn</li>
          <li>Giới hạn quyền truy cập thông tin</li>
          <li>Không chia sẻ thông tin với bên thứ ba không liên quan</li>
        </ul>
      </div>
      
      <div class="content-section">
        <h4>5. Quyền Của Khách Hàng</h4>
        <p>Bạn có quyền:</p>
        <ul>
          <li>Truy cập và xem thông tin cá nhân</li>
          <li>Yêu cầu chỉnh sửa thông tin không chính xác</li>
          <li>Yêu cầu xóa thông tin cá nhân</li>
          <li>Từ chối nhận email marketing</li>
        </ul>
      </div>
      
      <div class="content-section">
        <h4>6. Liên Hệ</h4>
        <p>Nếu bạn có bất kỳ câu hỏi nào về chính sách bảo mật, vui lòng liên hệ:</p>
        <ul>
          <li>Email: 4Wolf@gmail.com</li>
          <li>Điện thoại: 0782485283</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
