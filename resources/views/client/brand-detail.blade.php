@extends('layouts.client')
@section('content-client')
<style>
.brand-header {
    background: #f8f9fa;
    padding: 40px 0;
    margin-bottom: 40px;
}
.brand-logo-large {
    max-width: 200px;
    height: auto;
}
</style>

<div class="brand-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                @if($brand->img)
                    <img src="{{ asset('storage/' . $brand->img) }}" alt="{{ $brand->name }}" class="brand-logo-large">
                @else
                    <i class="fa fa-image fa-5x" style="color: #ccc;"></i>
                @endif
            </div>
            <div class="col-md-9">
                <h1>{{ $brand->name }}</h1>
                @if($brand->description)
                    <p class="lead">{{ $brand->description }}</p>
                @endif
                <p class="text-muted">
                    <i class="fa fa-box"></i> {{ $products->total() }} sản phẩm
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container mb_50">
    <div class="row">
        <div class="col-12 mb-4">
            <h3>Sản Phẩm Của {{ $brand->name }}</h3>
        </div>

        @forelse($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="product">
                <article>
                    <div class="thumb">
                        <a href="{{ route('user.products_detail', $product->id) }}" class="thumb-hover">
                            <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->name }}" class="img-responsive">
                        </a>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">
                            <a href="{{ route('user.products_detail', $product->id) }}">{{ $product->name }}</a>
                        </h4>
                        <div class="product-price">
                            <span class="price">{{ format_number_to_money($product->price_sell) }}</span>
                            @if($product->price_old > $product->price_sell)
                                <span class="old-price">{{ format_number_to_money($product->price_old) }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h5>Thương hiệu này chưa có sản phẩm nào</h5>
            </div>
        </div>
        @endforelse
    </div>

    @if($products->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
