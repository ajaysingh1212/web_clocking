@extends('layouts.app')

@section('title', 'Product | EEMOT Clocking PWA')

@php($item = data_get($product, 'data', $product))

@section('content')
<section class="card app-card">
    <div class="card-body">
        @if($apiError)
            <div class="alert alert-warning">{{ $apiError }}</div>
        @endif
        <div class="row g-4 align-items-center">
            <div class="col-md-4">
                <img class="product-image" src="{{ data_get($item, 'image') ?: asset('images/product-placeholder.svg') }}" alt="Product image">
            </div>
            <div class="col-md-8">
                <h1 class="h3">{{ data_get($item, 'name') ?? data_get($item, 'product_name', '-') }}</h1>
                <div class="row g-3 mt-2">
                    <div class="col-sm-6"><div class="metric"><span>SKU</span><strong>{{ data_get($item, 'sku', '-') }}</strong></div></div>
                    <div class="col-sm-6"><div class="metric"><span>Price</span><strong>{{ data_get($item, 'price', '-') }}</strong></div></div>
                    <div class="col-sm-6"><div class="metric"><span>Category</span><strong>{{ data_get($item, 'category.name') ?? data_get($item, 'category', '-') }}</strong></div></div>
                    <div class="col-sm-6"><div class="metric"><span>Status</span><strong>{{ data_get($item, 'status', '-') }}</strong></div></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
