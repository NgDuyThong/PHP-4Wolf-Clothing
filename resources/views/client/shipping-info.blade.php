@extends('layouts.client')
@section('content-client')
<link rel="stylesheet" href="{{ asset('asset/client/css/pages-style.css') }}">

<div class="page-header">
  <div class="container">
    <h1>Thông Tin Giao Hàng</h1>
  </div>
</div>

<div class="container page-content">
  <div class="row">
    <div class="col-12">
      <div class="content-section">
        <h4>1. Phạm Vi Giao Hàng</h4>
        <p>4Wolf Store cung cấp dịch vụ giao hàng toàn quốc, bao gồm tất cả các tỉnh thành trên lãnh thổ Việt Nam.</p>
      </div>
      
      <div class="content-section">
        <h4>2. Thời Gian Giao Hàng</h4>
        <ul>
          <li><strong>Nội thành TP.HCM:</strong> 1-2 ngày làm việc</li>
          <li><strong>Các tỉnh thành khác:</strong> 3-5 ngày làm việc</li>
          <li><strong>Vùng sâu, vùng xa:</strong> 5-7 ngày làm việc</li>
        </ul>
      </div>
      
      <div class="content-section">
        <h4>3. Phí Giao Hàng</h4>
        <ul>
          <li>Miễn phí giao hàng cho đơn hàng từ 500.000đ trở lên</li>
          <li>Phí giao hàng 30.000đ cho đơn hàng dưới 500.000đ</li>
          <li>Phí giao hàng có thể thay đổi tùy theo khu vực</li>
        </ul>
      </div>
      
      <div class="content-section">
        <h4>4. Quy Trình Giao Hàng</h4>
        <ol>
          <li>Sau khi đặt hàng thành công, bạn sẽ nhận được email xác nhận</li>
          <li>Đơn hàng sẽ được xử lý và đóng gói trong vòng 24h</li>
          <li>Bạn sẽ nhận được mã vận đơn để theo dõi đơn hàng</li>
          <li>Nhân viên giao hàng sẽ liên hệ trước khi giao</li>
          <li>Kiểm tra hàng trước khi thanh toán (với COD)</li>
        </ol>
      </div>
      
      <div class="content-section">
        <h4>5. Lưu Ý</h4>
        <ul>
          <li>Vui lòng cung cấp địa chỉ và số điện thoại chính xác</li>
          <li>Kiểm tra kỹ sản phẩm trước khi nhận hàng</li>
          <li>Liên hệ ngay với chúng tôi nếu có vấn đề về đơn hàng</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
