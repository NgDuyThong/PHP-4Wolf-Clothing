@extends('layouts.client')
@section('content-client')
<style>
.certificate-card {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}
.certificate-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.certificate-card.used {
    background: #f8f9fa;
    border-color: #ccc;
}
.certificate-code {
    font-size: 24px;
    font-weight: bold;
    color: #667eea;
    font-family: monospace;
}
.certificate-value {
    font-size: 32px;
    font-weight: bold;
    color: #28a745;
}
.status-badge {
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
}
.status-badge.active {
    background: #d4edda;
    color: #155724;
}
.status-badge.used {
    background: #f8d7da;
    color: #721c24;
}
.status-badge.expired {
    background: #fff3cd;
    color: #856404;
}
</style>

<div class="container mt_50 mb_50">
    <div class="row">
        <div class="col-12">
            <div class="heading-part mb_30">
                <h2 class="main_title">Giấy Chứng Nhận Của Tôi</h2>
                <p class="sub-title">Quản lý các giấy chứng nhận quà tặng của bạn</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @forelse($certificates as $certificate)
            <div class="certificate-card {{ $certificate->is_used ? 'used' : '' }}">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <i class="fa fa-gift fa-4x mb-2" style="color: #667eea;"></i>
                        <div class="certificate-code">{{ $certificate->code }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="certificate-value mb-2">{{ format_number_to_money($certificate->value) }}đ</div>
                        
                        @if($certificate->message)
                            <p class="mb-2"><strong>Lời nhắn:</strong> {{ $certificate->message }}</p>
                        @endif

                        <p class="mb-1">
                            <strong>Ngày tạo:</strong> {{ $certificate->created_at->format('d/m/Y H:i') }}
                        </p>

                        @if($certificate->expires_at)
                            <p class="mb-1">
                                <strong>Hết hạn:</strong> {{ $certificate->expires_at->format('d/m/Y') }}
                            </p>
                        @endif

                        @if($certificate->is_used)
                            <p class="mb-1">
                                <strong>Đã sử dụng:</strong> {{ $certificate->used_at->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                    <div class="col-md-3 text-center">
                        @if($certificate->is_used)
                            <span class="status-badge used">Đã sử dụng</span>
                        @elseif($certificate->expires_at && $certificate->expires_at->isPast())
                            <span class="status-badge expired">Hết hạn</span>
                        @else
                            <span class="status-badge active">Còn hiệu lực</span>
                            <div class="mt-3">
                                <a href="{{ route('cart.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-shopping-cart"></i> Sử dụng ngay
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info text-center">
                <i class="fa fa-info-circle fa-2x mb-2"></i>
                <h5>Bạn chưa có giấy chứng nhận nào</h5>
                <p>Giấy chứng nhận quà tặng sẽ xuất hiện ở đây khi bạn nhận được</p>
            </div>
            @endforelse
        </div>
    </div>

    @if($certificates->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $certificates->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
