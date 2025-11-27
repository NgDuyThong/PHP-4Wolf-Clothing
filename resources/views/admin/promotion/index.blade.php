@extends('layouts.admin')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-12">
        <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
          <i class="fas fa-plus"></i> Thêm mã khuyến mãi
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Danh sách mã khuyến mãi</h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Mã</th>
                  <th>Tên chương trình</th>
                  <th>Loại</th>
                  <th>Giá trị</th>
                  <th>Thời gian</th>
                  <th>Đã dùng/Giới hạn</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @forelse($promotions as $promotion)
                <tr>
                  <td>{{ $promotion->id }}</td>
                  <td><strong>{{ $promotion->code }}</strong></td>
                  <td>{{ $promotion->name }}</td>
                  <td>
                    @if($promotion->type === 'percentage')
                      <span class="badge badge-info">Phần trăm</span>
                    @else
                      <span class="badge badge-success">Cố định</span>
                    @endif
                  </td>
                  <td>
                    @if($promotion->type === 'percentage')
                      {{ $promotion->value }}%
                      @if($promotion->max_discount)
                        <br><small>(Tối đa: {{ number_format($promotion->max_discount) }}đ)</small>
                      @endif
                    @else
                      {{ number_format($promotion->value) }}đ
                    @endif
                  </td>
                  <td>
                    <small>
                      {{ $promotion->start_date->format('d/m/Y H:i') }}<br>
                      đến<br>
                      {{ $promotion->end_date->format('d/m/Y H:i') }}
                    </small>
                  </td>
                  <td>
                    {{ $promotion->usage_count }} / 
                    {{ $promotion->usage_limit ?? '∞' }}
                  </td>
                  <td>
                    @if($promotion->is_active && $promotion->isValid())
                      <span class="badge badge-success">Hoạt động</span>
                    @elseif($promotion->is_active)
                      <span class="badge badge-warning">Chưa bắt đầu/Hết hạn</span>
                    @else
                      <span class="badge badge-danger">Tắt</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-sm btn-info">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.promotions.toggle', $promotion) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="btn btn-sm {{ $promotion->is_active ? 'btn-warning' : 'btn-success' }}">
                        <i class="fas fa-{{ $promotion->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                      </button>
                    </form>
                    <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="9" class="text-center">Chưa có mã khuyến mãi nào</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            {{ $promotions->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
