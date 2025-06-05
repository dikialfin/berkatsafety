@extends('layouts.web')

@section('style')
    <style>
        .navbar, .breadcrumbs{background-color: #F0F0F0 !important} 
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
                            @if ($allbrand)
                                <li class="breadcrumb-item active" aria-current="page">{{ translate('Brands') }}</li>
                            @else
                                <li class="breadcrumb-item text-primary">
                                    <a href="{{ url("/$lang/brands") }}" title="{{ translate('Brands') }}">{{ translate('Brands') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($brands[0]->name) }}</li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-sidebar-web position-relative">
        <div class="container bg-split">
            <div class="row">
                <div class="col-12 col-sm-3  py-5">
                    <div class="mb-5 wow zoomIn" data-wow-delay="0.1s">
                        <h3 class="text-primary mb-2">{{ translate('Categories') }}</h3>
                        <hr style="height: 3px" class="bg-primary my-2">
                        <ul class="list-group bg-white border-rounded">
                            @foreach ($category as $val)
                                <a href="{{ url("$lang/category/{$val->slug}") }}" title="{{ ucfirst($val->name) }}" class="text-dark">
                                    <li class="list-group-item border-0 p-3 border-bottom d-flex text-dark align-items-center">
                                        <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center">
                                            <img src="{{ $val->logo }}" alt="" style="width: 65%; height:65%;">
                                        </div>
                                        <span class="ms-2 fz-13 fw-bold">{{ ucfirst($val->name) }}</span>
                                        @if (in_array($val->id, $selectedCategory)) 
                                            <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center  ms-auto">
                                                <img src="{{ asset('images/checked.svg') }}" alt="" style="width: 65%; height:65%;">
                                            </div>
                                        @endif
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mb-5 wow zoomIn" data-wow-delay="0.3s">
                        <h3 class="text-primary mb-2">{{ translate('Brands') }}</h3>
                        <hr style="height: 3px" class="bg-primary my-2">
                        <ul class="list-group bg-white border-rounded">
                            @foreach ($brands as $val)
                                <a href="{{ url("$lang/brands/{$val->slug}") }}" title="{{ ucfirst($val->name) }}" class="text-dark">
                                    <li class="list-group-item border-0 p-3 border-bottom d-flex text-dark align-items-center">
                                        <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center">
                                            <img src="{{ $val->logo }}" alt="" style="width: 65%; height:65%;">
                                        </div>
                                        <span class="ms-2 fz-13 fw-bold">{{ ucfirst($val->name) }}</span>
                                        @if (in_array($val->id, $brandsId)) 
                                            <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center  ms-auto">
                                                <img src="{{ asset('images/checked.svg') }}" alt="" style="width: 65%; height:65%;">
                                            </div>
                                        @endif
                                        @if ($allbrand)
                                            <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center  ms-auto">
                                                <img src="{{ asset('images/checked.svg') }}" alt="" style="width: 65%; height:65%;">
                                            </div>
                                        @endif
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-9 bg-white">
                    <div class="row g-0 align-items-end wow zoomIn" data-wow-delay="0.6s">
                        <div class="col-md-12 mt-4">
                            <div class="card-body p-0">
                                @if($detail)
                                    <div class="row g-0 align-items-center wow zoomIn" data-wow-delay="0.6s">
                                        <div class="col-2 col-md-1 text-center">
                                            <div style="width: 64px; height:64px">
                                                <img src="{{ $detail->logo }}" class="img-fluid" alt="{{ $detail->name }}" style="width: 100%; height: 100%; object-fit:contain; object-position: center">
                                            </div>
                                        </div>
                                        <div class="col-10 col-md-11 mt-4 title-category-detail">
                                            <div class="card-body p-0">
                                                <h1 class="text-primary mb-1">{{ $detail->name }}</h1>
                                                <p class="text-dark">{{ $detail->{'description_'.$lang} }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <h1 class="text-primary mb-1">{{ translate('Brands') }}</h1>
                                    <p class="card-text">{{ translate('Brand - Find the right protective solutions for your industry, from head to toe.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <form action="{{ url()->current() }}" method="GET" class="mb-3">
                        <input type="text" name="search" class="form-control mt-3" placeholder="Search" value="{{ request('search') }}" >
                    </form>
                    
                    <div class="row mt-4 wow fadeInUp" data-wow-delay="0.1s">
                        @foreach ($products as $val)
                            <div class="col-12 col-sm-4 mb-4">
                                <a href="{{ url("$lang/products/$val->slug") }}" title="{{ $val->name }}">
                                    <div class="product-card">
                                        <div class="product-brand">
                                            <img src="{{ isset($val->brands[0]) ? $val->brands[0]->logo : asset('/images/home/brand-prod-1.png')}}"/>
                                        </div>
                                        <div class="product-img">
                                            <img src="{{ isset($val->productMedia[0]) ? $val->productMedia[0]->value : asset('/images/home/prod-1.png') }}"/>
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
                        <div class="col-12">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="split-bg position-absolute"></div>
    </div>
@endsection
@section('scripts')

@endsection


