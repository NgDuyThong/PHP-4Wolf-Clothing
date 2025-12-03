@extends('layouts.admin')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-12">
        <a href="{{ route('admin.product_index') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i> 
          <strong>Cảnh báo:</strong> Các sản phẩm ở đây đã bị xóa tạm thời. Nhấn nút xóa sẽ xóa vĩnh viễn khỏi database và không thể khôi phục!
        </div>
        <x-table-crud 
          :headers="$tableCrud['headers']" 
          :list="$tableCrud['list']" 
          :actions="$tableCrud['actions']"
          :routes="$tableCrud['routes']" 
        />
      </div>
    </div>
  </div>
</section>
@endsection
