@extends('layouts.client')
@section('content-client')
<div class="container_fullwidth content-page">
    <div class="container">
        <div class="col-md-12 container-page">
            <div class="checkout-page">
              <ol class="checkout-steps">
                  <h4 class="title-steps text-center" style="padding-top: 25px;font-weight: 600;font-size: 25px;">
                    Hoàn Thiện Thông Tin
                  </h4>
                  <p class="text-center" style="margin-bottom: 20px; color: #666;">
                    Vui lòng cung cấp thêm thông tin để hoàn tất đăng ký
                  </p>
                  
                  @if(session('google_user'))
                  <div class="step-description">
                    <div class="row">
                      <div class="col-md-12 col-sm-12">
                        <div class="run-customer">
                          <form action="{{ route('auth.google.complete') }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                              <label for="name">Họ và Tên</label>
                              <input type="text" class="form-control" id="name" value="{{ session('google_user')['name'] }}" readonly style="background-color: #f5f5f5;">
                            </div>
                            
                            <div class="form-group">
                              <label for="email">Email</label>
                              <input type="email" class="form-control" id="email" value="{{ session('google_user')['email'] }}" readonly style="background-color: #f5f5f5;">
                            </div>
                            
                            <div class="form-group">
                              <label for="phone_number">Số Điện Thoại <span style="color: red;">*</span></label>
                              <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Nhập số điện thoại" required>
                              @if ($errors->get('phone_number'))
                                <span class="error invalid-feedback" style="display: block">
                                  {{ implode(", ",$errors->get('phone_number')) }}
                                </span>
                              @endif
                            </div>
                            
                            <div class="text-center" style="margin-top: 20px;">
                                <button type="submit" class="btn btn-primary">
                                  Hoàn Tất Đăng Ký
                                </button>
                                <a href="{{ route('user.login') }}" class="btn btn-secondary" style="margin-left: 10px;">
                                  Hủy
                                </a>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  @else
                  <div class="text-center">
                    <p>Phiên đăng nhập đã hết hạn. Vui lòng thử lại.</p>
                    <a href="{{ route('user.login') }}" class="btn btn-primary">Quay lại đăng nhập</a>
                  </div>
                  @endif
              </ol>
            </div>
          </div>
    </div>
</div>
@endsection
