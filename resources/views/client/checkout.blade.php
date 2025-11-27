@extends('layouts.client')
@section('content-client')
<style>
  .title-steps{
    padding: 20px 0px;
    font-weight: 600;
  }
  
  /* Promo Section Styles */
  .promo-section #apply-promo-btn:hover {
    background: #333 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  
  .promo-section #promo-code:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
  }
  
  .promo-message.text-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
  }
  
  .promo-message.text-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
  }
  
  #discount-row {
    background: #d4edda;
    padding: 15px;
    border-radius: 8px;
    border: 2px solid #28a745;
  }
  
  #discount-row .info__order-box {
    margin: 0;
  }
</style>
<div class="container_fullwidth">
    <div class="container">
      <form action="{{ route('checkout.index') }}" method="POST" id="form__js">
        <input style="visibility: hidden;" id="total-order-input" value="{{ Cart::getTotal() }}" type="text" hidden>
        <input style="visibility: hidden;" id="total-order-const" value="{{ Cart::getTotal() }}" type="text" hidden>
        <input style="visibility: hidden;" id="address" value="" type="text" hidden name="address">
        @csrf
        <div class="row">
          <div class="col-md-7">
            <ol class="checkout-steps">
                <h4 class="title-steps">
                  Thông Tin Cá Nhân
                </h4>
                <div class="step-description">
                  <div class="your-details">
                    <div class="form-group">
                      <label for="exampleInputPassword1">Họ Và Tên</label>
                      <input type="text" class="form-control" value="{{ $fullName }}" id="name" name="name" placeholder="Nhập họ và tên">
                      @if ($errors->get('name'))
                        <span id="name-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('name')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Email</label>
                      <input type="text" class="form-control" value="{{ $email }}" id="email" name="email" placeholder="Nhập địa chỉ email">
                      @if ($errors->get('email'))
                        <span id="email-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('email')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Số điện thoại</label>
                      <input type="text" class="form-control" value="{{ $phoneNumber }}" id="phone_number" name="phone_number" placeholder="Nhập số điện thoại">
                      @if ($errors->get('phone_number'))
                        <span id="phone_number-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('phone_number')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Tỉnh, Thành Phố</label>
                      <select class="form-control form-select" id="city" name="city">
                        @foreach ($citys as $item)
                            <option value="{{ $item['ProvinceID'] }}"
                            @if ( $item['ProvinceID'] == $city)
                                selected
                            @endif
                            >{{ $item['NameExtension'][1] }}</option>
                        @endforeach
                      </select>
                      @if ($errors->get('city'))
                        <span id="city-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('city')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Quận, Huyện</label>
                      <select class="form-control form-select" id="district" name="district">
                        @foreach ($districts as $item)
                            <option value="{{ $item['DistrictID'] }}"
                            @if ( $item['DistrictID'] == $district)
                                selected
                            @endif
                            >{{ $item['DistrictName'] }}</option>
                        @endforeach
                      </select>
                      @if ($errors->get('district'))
                        <span id="district-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('district')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Phường Xã</label>
                      <select class="form-control form-select" id="ward" name="ward">
                        @foreach ($wards as $item)
                            <option value="{{ $item['WardCode'] }}"
                            @if ( $item['WardCode'] == $ward)
                              selected
                            @endif
                            >{{ $item['WardName'] }}</option>
                        @endforeach
                      </select>
                      @if ($errors->get('ward'))
                        <span id="ward-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('ward')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Địa Chỉ Nhà</label>
                      <div style="display: flex; gap: 10px;">
                        <input type="text" class="form-control" value="{{ $apartment_number}}" id="apartment_number" name="apartment_number" aria-describedby="emailHelp" placeholder="Nhập địa chỉ nhà" style="flex: 1;">
                        <button type="button" class="btn btn-info" id="get-location-btn" style="white-space: nowrap;">
                          <i class="fa fa-map-marker"></i> Lấy vị trí
                        </button>
                      </div>
                      <small id="location-status" style="display: none; color: #666; margin-top: 5px;"></small>
                      @if ($errors->get('apartment_number'))
                        <span id="apartment_number-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('apartment_number')) }}
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
            </ol>
          </div>
          <div class="col-md-5">
            <div>
                <ol class="checkout-steps">
                    <h4 class="title-steps">
                      Thông Tin Đơn Hàng
                    </h4>
                    <div class="step-description">
                      <div class="your-details">
                        <div class="info-order">
                          <div class="info__order-box">
                            <span>Tổng tiền sản phẩm</span>
                            <span id="total-product">{{ format_number_to_money(Cart::getTotal()) }}</span>
                          </div>
                        </div>
                        <div class="info-order">
                          <div class="info__order-box">
                            <span>Phí vận chuyển</span>
                            <span id="fee">0</span>
                          </div>
                        </div>
                        <div class="info-order promo-section">
                          <div class="info__order-box" style="flex-direction: column; align-items: flex-start; gap: 12px; background: #f8f9fa; padding: 20px; border-radius: 10px;">
                            <div style="display: flex; align-items: center; gap: 8px; width: 100%;">
                              <i class="fa fa-tag" style="color: #667eea; font-size: 18px;"></i>
                              <span style="font-weight: 600; font-size: 16px; color: #333;">Mã khuyến mãi</span>
                            </div>
                            <div style="display: flex; gap: 10px; width: 100%;">
                              <input type="text" id="promo-code" class="form-control" placeholder="Nhập mã khuyến mãi (VD: WELCOME2025)" 
                                     style="flex: 1; border: 2px solid #ddd; padding: 12px; border-radius: 8px; font-weight: 600; text-transform: uppercase;">
                              <button type="button" id="apply-promo-btn" class="btn" 
                                      style="white-space: nowrap; background: white; border: 2px solid #333; color: #333; font-weight: 600; padding: 12px 24px; border-radius: 8px; transition: all 0.3s;">
                                <i class="fa fa-check"></i> Áp dụng
                              </button>
                            </div>
                            <div id="promo-message" class="promo-message" style="display: none; font-size: 14px; padding: 10px; border-radius: 6px; width: 100%;"></div>
                            <input type="hidden" id="promotion-id" name="promotion_id" value="">
                            <a href="{{ route('user.promotions') }}" target="_blank" 
                               style="font-size: 14px; color: #667eea; font-weight: 500; display: flex; align-items: center; gap: 6px; text-decoration: none;">
                              <i class="fa fa-gift"></i> Xem tất cả mã khuyến mãi
                            </a>
                          </div>
                        </div>
                        <div class="info-order" id="discount-row" style="display: none;">
                          <div class="info__order-box" style="flex-direction: column; gap: 8px;">
                            <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                              <span style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-ticket" style="color: #28a745;"></i>
                                <span>Mã: <strong id="applied-promo-code" style="color: #28a745;"></strong></span>
                              </span>
                              <button type="button" id="remove-promo-btn" class="btn btn-sm btn-outline-danger" style="padding: 4px 12px; font-size: 12px;">
                                <i class="fa fa-times"></i> Hủy
                              </button>
                            </div>
                            <div style="display: flex; justify-content: space-between; width: 100%;">
                              <span>Giảm giá</span>
                              <span id="discount-amount" style="color: #28a745; font-weight: 600; font-size: 16px;">0đ</span>
                            </div>
                          </div>
                        </div>
                        <div class="info-order">
                          <div class="info__order-box">
                            <span style="font-weight: 600; font-size: 16px;">Tổng đơn hàng</span>
                            <span id="total-order" style="font-weight: 600; font-size: 16px; color: #dc3545;">0</span>
                            <span id="final-total" style="display: none;"></span>
                          </div>
                        </div>
                        <div class="info-order">
                          <div class="payment-method">
                            <span>Chọn phương thức thanh toán</span>
                            @if ($errors->get('payment_method'))
                              <span id="payment_method-error" class="error invalid-feedback" style="display: block">
                                {{ implode(", ",$errors->get('payment_method')) }}
                              </span>
                            @endif
                          </div>
                          @foreach ($payments as $payment)
                          <div class="payment-method-select">
                            <label for="{{ $payment->id }}" class="payment-method-select--check">
                              <div>
                                <input type="radio" value="{{ $payment->id }}" name="payment_method" id="{{ $payment->id }}" @if ($payment->id == 1)
                                checked
                                @endif>
                                <span class="label-momo">
                                  {{ $payment->name }}
                                </span>
                              </div>
                              <img src="{{ asset("asset/imgs/$payment->img") }}" alt="">
                            </label>
                          </div>
                          @endforeach
                          <div style="padding-top: 40px;" class="text-center">
                            <button class="btn btn-primary">Thanh Toán Đơn Hàng</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </ol>
            </div>
          </div>
        </div>
      </form>
      <div class="clearfix">
      </div>
    </div>
  </div>
@vite(['resources/client/js/checkout.js', 'resources/client/css/checkout.css', 'resources/client/js/promotion.js'])

@endsection