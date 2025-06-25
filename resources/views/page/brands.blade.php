@extends('layouts.web')

@section('style')
<style>
    .navbar,
    .breadcrumbs {
        background-color: #F0F0F0 !important
    }
</style>
@endsection

@section('content')
<div class="container-fuild bg-white pb-4 pt-5 bg-F0F0F0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item text-primary">
                            <a href="{{ url('/'.$lang) }}" title="{{ translate('Home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ translate('Brands') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid bg-sidebar-web position-relative">
    <div class="container bg-split">
        <div class="row">
            <div class="col-12 bg-white my-5 p-5 rounded-4">
                @if(isset($brands))
                <div class="row g-0 align-items-end wow zoomIn" data-wow-delay="0.6s">
                    <div class="col-md-12 mt-4">
                        <div class="card-body p-0">
                            <h1 class="text-primary mb-1">{{ translate('Brands') }}</h1>
                            <p class="card-text">{{ translate('Brand - Find the right protective solutions for your industry, from head to toe.') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($products) && count($products) > 0)
                <div class="row g-0 align-items-center wow zoomIn" data-wow-delay="0.6s">
                    <div class="col-2 col-md-1 text-center">
                        <div style="width: 64px; height:64px">
                            <img src="{{ $products[0]->brandLogo }}" class="img-fluid" alt="{{ $products[0]->brandName }}" style="width: 100%; height: 100%; object-fit:contain; object-position: center">
                        </div>
                    </div>
                    <div class="col-10 col-md-11 mt-4 title-category-detail">
                        <div class="card-body p-0">
                            <h1 class="text-primary mb-1">{{ $products[0]->brandName }}</h1>
                            <p class="text-dark">{{ $products[0]->{'brandsDescription_'.$lang} }}</p>
                        </div>
                    </div>
                </div>
                @endif
                <form action="{{ url()->current() }}" method="GET" class="mb-3">
                    <input type="text" name="search" class="form-control mt-3" placeholder="Search" value="{{ request('search') }}">
                </form>

                <div class="row mt-4 wow fadeInUp" data-wow-delay="0.1s">
                    <!-- Brand Product -->
                    @if(isset($products))
                    @foreach ($products as $val)
                    <div class="col-12 col-sm-4 mb-4">
                        <a href="{{ url("$lang/products/$val->slug") }}" title="{{ $val->name }}">
                            <div class="product-card">
                                <div class="product-brand">
                                    <img src="{{ $val->brandLogo}}" />
                                </div>
                                <div class="product-img">
                                    <img src="{{ $val->photoProduct}}" />
                                </div>
                                <div class="product-description mt-3">
                                    <h3 class="mb-1 fz-16">{{ descriptionProduct($val->name, 25) }}</h3>
                                    <p class="mb-1 fz-12">{{ descriptionProduct($val->code, 25) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @if ($products->isEmpty())
                    <div class="col-12 text-center">
                        <div class="col-12 col-sm-4 mx-sm-auto text-center">
                            <img src="{{ asset('images/no-data.png') }}" alt="" class="w-5rem">
                            <p class="text-muted text-center">No Data</p>
                        </div>
                    </div>
                    @endif
                    @endif
                    <!-- end Brand Product -->
                    <!-- Brands -->
                    @if(isset($brands))
                    @foreach ($brands as $val)
                    <div class="col-12 col-lg-2 col-sm-4 mb-4">
                        <a href="{{ url("$lang/brands/$val->slug") }}" title="{{ $val->name }}">
                            <div class="product-card" style="height: 250px; padding-bottom:30px">
                                <div class="product-brand">
                                    <img src="{{ isset($val->logo) ? $val->logo : asset('/images/home/brand-prod-1.png')}}" />
                                </div>
                                <div class="product-img">
                                    <img src="{{ isset($val->logo) ? $val->logo : asset('/images/home/prod-1.png') }}" />
                                </div>
                                <div class="product-description mt-3">
                                    <h3 class="mb-1 fz-16">{{ descriptionProduct($val->name, 25) }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @if ($brands->isEmpty())
                    <div class="col-12 text-center">
                        <div class="col-12 col-sm-4 mx-sm-auto text-center">
                            <img src="{{ asset('images/no-data.png') }}" alt="" class="w-5rem">
                            <p class="text-muted text-center">No Data</p>
                        </div>
                    </div>
                    @endif
                    @endif
                    <!-- end Brands -->
                    <div class="col-12">
                        @if(isset($products))
                        {{ $products->links() }}
                        @else
                        {{ $brands->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection