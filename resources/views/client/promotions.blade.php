@extends('layouts.client')
@section('content-client')
<style>
.promotion-card {
    border: 2px solid #e0e0e0;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
}
.promotion-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transform: translateY(-8px);
    border-color: #000000;
}
.promotion-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    background: linear-gradient(135deg, #2c2c2c 0%, #000000 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}
.promotion-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.promotion-code {
    background: linear-gradient(135deg, #2c2c2c 0%, #000000 100%);
    border: none;
    padding: 15px;
    border-radius: 10px;
    font-size: 20px;
    font-weight: bold;
    color: white;
    text-align: center;
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease;
    letter-spacing: 2px;
}
.promotion-code:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
}
.promotion-code i {
    margin-right: 8px;
}
.copy-tooltip {
    position: absolute;
    top: -35px;
    left: 50%;
    transform: translateX(-50%);
    background: #28a745;
    color: white;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 13px;
    display: none;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.copy-tooltip::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid #28a745;
}
.promotion-value {
    font-size: 36px;
    font-weight: bold;
    color: #ffffff;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}
.promotion-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.promotion-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}
.promotion-description {
    color: #666;
    font-size: 14px;
    margin-bottom: 15px;
}
.condition-list {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
}
.condition-list li {
    font-size: 13px;
    color: #333;
    margin-bottom: 5px;
}
.promotion-date {
    font-size: 12px;
    color: #999;
    display: flex;
    align-items: center;
    gap: 5px;
}
.btn-use-promo {
    background: white;
    border: 2px solid #333;
    color: #333;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-use-promo:hover {
    background: #333;
    color: white;
    transform: translateY(-2px);
}
.condition-list {
    height: 140px;
    position: relative;
    overflow-y: auto;
}
</style>

<div class="container mt_50 mb_50">
    <div class="row">
        <div class="col-12">
            <div class="heading-part mb_30">
                <h2 class="main_title">Khuyến Mãi Đặc Biệt</h2>
                <p class="sub-title">Săn ngay các mã giảm giá hấp dẫn cho đơn hàng của bạn</p>
            </div>
        </div>
    </div>

    <div class="row d-flex">
        @forelse($promotions as $promotion)
        <div class="col-lg-4 col-md-6 mb-4 d-flex">
            <div class="promotion-card">
                <div style="position: relative;">
                    @if($promotion->image)
                        <img src="{{ asset('storage/' . $promotion->image) }}" alt="{{ $promotion->name }}" class="promotion-image">
                    @else
                        <div class="promotion-image">
                            <div class="text-center text-white">
                                <i class="fa fa-gift fa-4x mb-3" style="color: #dc3545;"></i>
                                @if($promotion->type === 'percentage')
                                    <div class="promotion-value">{{ $promotion->value }}%</div>
                                    <div style="font-size: 16px; color: #ffffff;">GIẢM GIÁ</div>
                                @else
                                    <div class="promotion-value">{{ number_format($promotion->value/1000) }}K</div>
                                    <div style="font-size: 16px; color: #ffffff;">GIẢM NGAY</div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    @if($promotion->type === 'percentage')
                        <span class="promotion-badge badge badge-danger">
                            -{{ $promotion->value }}%
                        </span>
                    @else
                        <span class="promotion-badge badge badge-success">
                            -{{ number_format($promotion->value) }}đ
                        </span>
                    @endif
                </div>
<!-- 123 -->
                <div class="promotion-card-body">
                    <h5 class="promotion-title">{{ $promotion->name }}</h5>
                    
                    @if($promotion->description)
                        <p class="promotion-description">{{ $promotion->description }}</p>
                    @endif

                    <div class="promotion-code mb-3" onclick="copyCode('{{ $promotion->code }}', this)">
                        <span class="copy-tooltip">✓ Đã sao chép!</span>
                        <i class="fa fa-copy"></i> {{ $promotion->code }}
                    </div>

                    <div class="condition-list">
                        <ul class="mb-0 pl-3">
                            <li><i class="fa fa-check-circle" style="color: #000000;"></i> Đơn tối thiểu: <strong>{{ number_format($promotion->min_order_value) }}đ</strong></li>
                            @if($promotion->type === 'percentage' && $promotion->max_discount)
                                <li><i class="fa fa-check-circle" style="color: #000000;"></i> Giảm tối đa: <strong>{{ number_format($promotion->max_discount) }}đ</strong></li>
                            @endif
                            <li><i class="fa fa-user" style="color: #000000;"></i> Dùng được: <strong>{{ $promotion->usage_per_user }} lần</strong></li>
                            @if($promotion->usage_limit)
                                <li><i class="fa fa-ticket" style="color: #000000;"></i> Còn: <strong>{{ $promotion->usage_limit - $promotion->usage_count }} mã</strong></li>
                            @endif
                        </ul>
                    </div>

                    <div class="promotion-date mb-3">
                        <i class="fa fa-calendar"></i>
                        <span>{{ $promotion->start_date->format('d/m/Y') }} - {{ $promotion->end_date->format('d/m/Y') }}</span>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('user.home') }}" class="btn btn-use-promo btn-block">
                            <i class="fa fa-shopping-cart"></i> Mua Ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fa fa-info-circle fa-2x mb-2"></i>
                <h5>Hiện chưa có chương trình khuyến mãi nào</h5>
                <p>Vui lòng quay lại sau để không bỏ lỡ các ưu đãi hấp dẫn!</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($promotions->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $promotions->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function copyCode(code, element) {
    // Copy to clipboard
    navigator.clipboard.writeText(code).then(function() {
        // Show tooltip
        const tooltip = element.querySelector('.copy-tooltip');
        tooltip.style.display = 'block';
        
        // Hide after 2 seconds
        setTimeout(function() {
            tooltip.style.display = 'none';
        }, 2000);
    });
}
</script>
@endsection
