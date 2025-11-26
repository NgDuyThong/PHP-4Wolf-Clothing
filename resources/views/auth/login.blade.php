@extends('layouts.client')
@section('content-client')
<style>
    .btn-google-login {
        width: 100%; 
        padding: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 10px; 
        color: #000 !important;
        text-decoration: none;
    }
    .btn-google-login:hover {
        color: #fff !important;
        text-decoration: none;
    }
</style>
<div class="container_fullwidth content-page">
    <div class="container">
        <div class="col-md-12 container-page">
            <div class="checkout-page">
              <ol class="checkout-steps">
                  <h4 class="title-steps text-center" style="padding-top: 25px;font-weight: 600;font-size: 25px;">
                    Đăng Nhập
                  </h4>
                  <div class="step-description">
                    <div class="row">
                      <div class="col-md-12 col-sm-12">
                        <div class="run-customer">
                          <form action="{{ route('user.login') }}" method="POST" id="login-form__js">
                            @csrf
                            <div class="form-group">
                              <label for="exampleInputEmail1">Email</label>
                              <input type="text" class="form-control" value="{{ old('email') }}" id="email" name="email" placeholder="Nhập email">
                              @if ($errors->get('email'))
                                <span id="email-error" class="error invalid-feedback" style="display: block">
                                  {{ implode(", ",$errors->get('email')) }}
                                </span>
                              @endif
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Mật Khẩu</label>
                              <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu">
                              @if ($errors->get('password'))
                                <span id="password-error" class="error invalid-feedback" style="display: block">
                                  {{ implode(", ",$errors->get('password')) }}
                                </span>
                              @endif
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary">
                                  Đăng Nhập
                                </button>
                            </div>
                            
                            <div class="text-center" style="margin: 20px 0;">
                                <div style="position: relative; text-align: center; margin: 20px 0;">
                                    <hr style="border-top: 1px solid #ddd;">
                                    <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: white; padding: 0 10px; color: #666;">Hoặc</span>
                                </div>
                                <a href="{{ route('auth.google') }}" class="btn btn-danger btn-google-login">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                                        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                                    </svg>
                                    Đăng nhập bằng Google
                                </a>
                            </div>
                            
                            <div class="content-footer">
                                <a href="{{ route('user.forgot_password_create') }}">
                                  Quên mật khẩu,
                                </a>
                                <a href="{{ route('user.register') }}">
                                  Đăng kí tài khoản
                                </a>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
              </ol>
            </div>
          </div>
    </div>
</div>
@vite(['resources/common/js/login.js'])
@endsection