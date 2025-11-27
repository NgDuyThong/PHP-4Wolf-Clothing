@extends('layouts.client')
@section('content-client')
<style>
.brand-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    background: white;
}
.brand-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-5px);
}
.brand-logo {
    width: 100%;
    height: 150px;
    object-fit: contain;
    margin-bottom: 15px;
}
.brand-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}
.brand-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
}
</style>

<div class="container mt_50 mb_50">
    <div class="row">
        <div class="col-12">
            <div class="heading-part mb_30">
                <h2 class="main_title">Thương Hiệu</h2>
                <p class="sub-title">Khám phá các thương hiệu thời trang hàng đầu</p>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($brands as $brand)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="brand-card">
                @if($brand->img)
                    <img src="{{ asset('storage/' . $brand->img) }}" alt="{{ $brand->name }}" class="brand-logo">
                @else
                    <div class="brand-logo d-flex align-items-center justify-content-center" style="background: #f5f5f5;">
                        <i class="fa fa-image fa-3x" style="color: #ccc;"></i>
                    </div>
                @endif
                
                <h3 class="brand-name">{{ $brand->name }}</h3>
                
                @if($brand->description)
                    <p class="brand-description">{{ Str::limit($brand->description, 100) }}</p>
                @endif

                <a href="{{ route('user.brand.show', $brand->id) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-eye"></i> Xem sản phẩm
                </a>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fa fa-info-circle fa-2x mb-2"></i>
                <h5>Chưa có thương hiệu nào</h5>
            </div>
        </div>
        @endforelse
    </div>

    @if($brands->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $brands->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
