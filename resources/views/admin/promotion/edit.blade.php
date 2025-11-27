@extends('layouts.admin')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-12">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Chỉnh sửa mã khuyến mãi</h3>
          </div>
          <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Mã khuyến mãi <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                           value="{{ old('code', $promotion->code) }}" required>
                    @error('code')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tên chương trình <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $promotion->name) }}" required>
                    @error('name')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $promotion->description) }}</textarea>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Loại khuyến mãi <span class="text-danger">*</span></label>
                    <select name="type" class="form-control @error('type') is-invalid @enderror" id="promotion_type" required>
                      <option value="percentage" {{ old('type', $promotion->type) === 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                      <option value="fixed" {{ old('type', $promotion->type) === 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                    </select>
                    @error('type')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Giá trị <span class="text-danger">*</span></label>
                    <input type="number" name="value" class="form-control @error('value') is-invalid @enderror" 
                           value="{{ old('value', $promotion->value) }}" min="0" step="0.01" required>
                    @error('value')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4" id="max_discount_field">
                  <div class="form-group">
                    <label>Giảm tối đa (đ)</label>
                    <input type="number" name="max_discount" class="form-control" 
                           value="{{ old('max_discount', $promotion->max_discount) }}" min="0" step="1000">
                    <small class="text-muted">Chỉ áp dụng cho loại phần trăm</small>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Giá trị đơn hàng tối thiểu (đ) <span class="text-danger">*</span></label>
                    <input type="number" name="min_order_value" class="form-control @error('min_order_value') is-invalid @enderror" 
                           value="{{ old('min_order_value', $promotion->min_order_value) }}" min="0" step="1000" required>
                    @error('min_order_value')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Số lần sử dụng tối đa</label>
                    <input type="number" name="usage_limit" class="form-control" 
                           value="{{ old('usage_limit', $promotion->usage_limit) }}" min="1">
                    <small class="text-muted">Để trống nếu không giới hạn</small>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Số lần mỗi user được dùng <span class="text-danger">*</span></label>
                    <input type="number" name="usage_per_user" class="form-control @error('usage_per_user') is-invalid @enderror" 
                           value="{{ old('usage_per_user', $promotion->usage_per_user) }}" min="1" required>
                    @error('usage_per_user')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date', $promotion->start_date->format('Y-m-d\TH:i')) }}" required>
                    @error('start_date')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                           value="{{ old('end_date', $promotion->end_date->format('Y-m-d\TH:i')) }}" required>
                    @error('end_date')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Hình ảnh</label>
                @if($promotion->image)
                  <div class="mb-2">
                    <img src="{{ asset('storage/' . $promotion->image) }}" alt="Promotion" style="max-width: 200px;">
                  </div>
                @endif
                <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                         {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                  <label class="custom-control-label" for="is_active">Kích hoạt</label>
                </div>
              </div>

              <div class="alert alert-info">
                <strong>Thống kê:</strong> Mã này đã được sử dụng {{ $promotion->usage_count }} lần
              </div>
            </div>

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Cập nhật</button>
              <a href="{{ route('admin.promotions.index') }}" class="btn btn-default">Hủy</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const typeSelect = document.getElementById('promotion_type');
  const maxDiscountField = document.getElementById('max_discount_field');
  
  function toggleMaxDiscount() {
    if (typeSelect.value === 'percentage') {
      maxDiscountField.style.display = 'block';
    } else {
      maxDiscountField.style.display = 'none';
    }
  }
  
  typeSelect.addEventListener('change', toggleMaxDiscount);
  toggleMaxDiscount();
});
</script>
@endsection
