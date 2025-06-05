@extends('layouts.web')
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessible-slick-theme.min.css') }}">
    <style>
        .slick-autoplay-toggle-button{display: none}
        .slick-dots li.slick-active button .slick-dot-icon:before{color: #052c7a !important}
        .slick-list.draggable{margin-left:0.7rem !important}
        .slick-dots li.slick-active button .slick-dot-icon:before, .slick-dots li button .slick-dot-icon:before{font-size: 20px !important}

        .slick-prev .slick-prev-icon:before {
            content: url("{{ asset('images/arrow-left.png') }}");
            position: relative;
            right: 2px;
            top: 2px;
        }

        .slick-next .slick-next-icon:before {
            content: url("{{ asset('images/arrow-right.png') }}");
            position: relative;
            left: 2px;
            top: 2px;
        }

        .slick-next, .slick-prev{background: #D5D5D5 !important; padding: 5px 10px;border-radius: 50%; width: 40px !important; height: 40px !important;}
        .slick-prev {left: -40px;}
        .slick-next {right: -40px;}
        .slick-arrow {transition: background 0.3s ease;}
        .slick-arrow:hover{background: #052c7a !important}
        .bg-F0F0F0{background: #F0F0F0}
        .product-card{width: 293px !important}
        #wrapProduct{overflow: hidden;}
    </style>
@endsection
@section('content')
    <div class="container-fluid px-0 mx-0 home-hero">
        <div class="container py-sm-4">
            <div class="row ">
                <div class="col-12 col-lg-7 pe-sm-2 pe-0 ps-0 ps-sm-1">
                    <div class="card home-hero-left">
                        <div class="card-body" style="display: grid">
                            <div class="d-flex flex-column align-items-start justify-content-center wow zoomIn" style="height: 100%" data-wow-delay="0.6s">
                                <h1 class="text-white">{{ translate('Brings Future Goodness through Prime Protection') }}</h1>
                                <p class="text-white">{{ translate('We empower industries to protect their workforce from risks, ensuring confidence and peace of mind as they perform their duties.') }}</p>
                                <a href="{{ url("$lang/brands") }}" class="btn btn-primary web">
                                    <span class="d-inline-block">{{ translate('Explore our Products') }}</span> <i class="d-inline-block fz-15 ms-3 fa fa-angle-right fw-bold"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 ps-sm-4 ps-0 pe-0 pe-sm-1">
                    <div class="card home-hero-right-top">
                        <div class="card-body" >
                            <div class="d-flex flex-column align-items-start justify-content-center" style="height: 100%">
                                <div class="d-flex gap-sm-4 justify-content-between mb-4" style="width: 100%">
                                    @foreach ($category as $key => $val)
                                        @if (in_array($key, [0,1,2,3]))
                                            <a href="{{ url("$lang/category/$val->slug") }}" class="" title="{{ $val->name }}" style="width: 25%">
                                                <div class="rounded-circle rounded-image-product" style="background-image:url({{ $val->logo }}); width: 100%"></div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                                <a href="{{ url("{$lang}/category") }}" class="btn btn-primary web" style="width: 100%">
                                    <span class="d-inline-block">{{ translate('Products by Category') }}</span> <i class="d-inline-block fz-15 ms-3 fa fa-angle-right fw-bold"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card home-hero-right-bottom">
                        <div class="card-body" >
                            <div class="d-flex flex-column align-items-start justify-content-center" style="height: 100%">
                                <div class="d-flex gap-4 justify-content-center mb-4 brand-home">
                                    @foreach ($brands as $key => $val)
                                        @if (in_array($key, [0,1,2]))
                                            <a href="{{ url("$lang/brands/$val->slug") }}" title="{{ $val->name }}" style="width:30%">
                                                <div class="">
                                                    <img src="{{ $val->logo }}" alt="{{ $val->name }}">
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                                <a href="{{ url("{$lang}/brands") }}" class="btn btn-primary web" style="width: 100%">
                                    <span class="d-inline-block">{{ translate('Products by Brand') }}</span> <i class="d-inline-block fz-15 ms-3 fa fa-angle-right fw-bold"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0 mx-0 home-trending position-relative wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-4" style="position: relative;z-index:10">
            <div class="row ">
                <div class="col-12 wow zoomIn" data-wow-delay="0.6s">
                    <h1 class="text-primary mb-0">{{ translate('Trending Now in Workplace Safety') }}</h1>
                    <p class="text-dark">{{ translate('Explore our most sought-after safety equipment, designed for reliability and comfort.') }}</p>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-slider">
                                @foreach ($products as $val)
                                    <a href="{{ url("$lang/products/$val->slug") }}" title="{{ $val->name }}">
                                        <div class="product-card me-3">
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
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="triangle-1"></div>
        <div class="triangle-2"></div> --}}
        <img src="{{ asset('images/rectangle-blue.webp') }}" alt="" class="position-absolute" style="bottom: 0; right:0;">
        <img src="{{ asset('images/rectangle-green.webp') }}" alt="" class="position-absolute" style="top: 0; right:0; width:60%">
    </div>
    <div id="section-category" class="container-fluid px-0 mx-0 position-relative wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-3 pt-5 wow fadeInUp d-none d-sm-block" data-wow-delay="0.3s">
                    <h1 class="text-primary mb-1">{{ translate('Browse Categories') }}</h1>
                    <hr class="bg-primary my-2" style="height: 3px; width: 90%;">
                    <ul class="list-group list-group-flush bg-white" style="width: 90%">
                        @foreach ($category as $key => $val)
                            <a href="{{ $val->slug }}" 
                                class="text-primary category-filter"
                            >
                                <li class="list-group-item p-3 border-bottom d-flex text-dark align-items-center">
                                    <div style="width: 30px; height:30px; border-radius: 50%; line-height:30px" class="bg-white text-center">
                                        <img src="{{ $val->logo }}" alt="" style="width: 100%;height: 100%;border-radius: 50%;object-fit: cover;object-position: center;">
                                    </div>
                                    <span class="ms-3 fz-13 fw-bold">{{ ucfirst($val->name) }}</span>
                                    @if (request('category') == $val->slug)
                                        <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center  ms-auto active-category">
                                            <img src="{{ asset('images/checked.svg') }}" alt="" style="width: 65%; height:65%;">
                                        </div>
                                    @endif
                                    @if (!request('category') && $key == 0) 
                                        <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center  ms-auto active-category">
                                            <img src="{{ asset('images/checked.svg') }}" alt="" style="width: 65%; height:65%;">
                                        </div>
                                    @endif
                                </li>
                            </a>
                        @endforeach
                    </ul>
                </div>
                <div class="col-12 col-sm-9 wow fadeInUp pe-0 pe-sm-1" data-wow-delay="0.3s">
                    @if (request('category'))
                        @php($filteredCategory = $category->where('slug', request('category'))->first())
                        <div class="card mb-3" id="headerCategory">
                            <div class="row g-0 align-items-center">
                                <div class="col-sm-1 col-3 text-center">
                                    <img src="{{ $filteredCategory->logo }}" class="img-fluid category-home-img" alt="{{ ucfirst($filteredCategory->name) }}">
                                </div>
                                <div class="col-sm-11 py-5 col-9">
                                    <div class="card-body p-0">
                                        <h3 class="mb-1 fz-16">{{ $filteredCategory->name }}</h3>
                                        <p class="mb-1 fz-12">{{ $filteredCategory->{'description_'.$lang} }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!request('category') && isset($category[0]))
                        <div class="card mb-3" id="headerCategory">
                            <div class="row g-0 align-items-center">
                                <div class="col-sm-1 col-3 text-center">
                                    <img src="{{ $category[0]->logo }}" class="img-fluid category-home-img" alt="{{ ucfirst($category[0]->name) }}">
                                </div>
                                <div class="col-sm-11 py-5 col-9">
                                    <div class="card-body p-0">
                                        <h3 class="mb-1 fz-16">{{ $category[0]->name }}</h3>
                                        <p class="mb-1 fz-12">{{ $category[0]->{'description_'.$lang} }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @if ($productCategory)
                                        <div class="category-product"  id="category-product">
                                            @foreach ($productCategory as $val)
                                                <a href="{{ url("$lang/products/$val->slug") }}" title="{{ $val->name }}">
                                                    <div class="product-card me-3">
                                                        <div class="product-brand">
                                                            <img src="{{ isset($val->brands[0]) ? $val->brands[0]->logo : asset('/images/home/brand-prod-1.png')}}"/>
                                                        </div>
                                                        <div class="product-img">
                                                            <img src="{{ isset($val->productMedia[0]) ? $val->productMedia[0]->value : asset('/images/home/prod-1.png') }}"/>
                                                        </div>
                                                        <div class="product-description mt-3">
                                                            <h3 class="mb-1">{{ descriptionProduct($val->name, 25) }}</h3>
                                                            <h3 class="mb-1">{{ descriptionProduct($val->code, 25) }}</h3>
                                                            <p>{{ descriptionProduct($val->name, 25) }}</p>
                                                        </div>
                                                    </div>    
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center ps-4 pe-5">
                            @if (request('category') && count($productCategory))
                                @php($filteredCategory = $category->where('slug', request('category'))->first())
                                <a class="btn btn-primary w-100" id="categoryLink" href="{{ url("{$lang}/category/{$filteredCategory->slug}") }}">
                                    {{ translate('View All') }} <i class="fe fe-chevron-right"></i>
                                </a>
                            @endif

                            @if (!request('category') && count($productCategory))
                                <a class="btn btn-primary w-100" id="categoryLink" href="{{ url("{$lang}/category/{$category[0]->slug}") }}">
                                    {{ translate('View All') }} <i class="fe fe-chevron-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('images/banner-side-cetegory.webp') }}" alt="" class="position-absolute d-none d-sm-none" style="top: 0; left:0;height: 100%;width: 26.5%;z-index: -3;">
    </div>
    <div id="section-brands" class="container-fluid px-0 mx-0 py-4 position-relative">
        <div class="container mb-3 wow zoomIn trusted" data-wow-delay="0.6s">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-1 text-primary">{{ translate('Trusted Brands for Ultimate Safety!') }}</h1>
                    <p class="text-primary">{{ translate('Explore products from industry-leading brands known for quality and reliability.') }}</p>
                </div>
            </div>
        </div>
        <div class="container py-3 bg-F0F0F0">
            <div class="row">
                <div class="col-12 col-sm-11 mx-sm-auto">
                    <div class="popular-brands mb-0 mt-2">
                        @foreach ($brands as $key => $val)
                            <a 
                                href="{{ $val->slug }}"
                                class="brand-slide-home text-primary" data-type="brands"
                            >
                                @if (request('brands'))
                                    <div class="slide-wrap {{ (request('brands') == $val->slug) ? 'active-slide-brand' : '' }}">
                                        <img class="w-100" src="{{ $val->banner }}" alt="{{ ucfirst($val->name) }}">
                                    </div>
                                @endif
                                @if (!request('brands'))
                                    <div class="slide-wrap {{ $key == 0 ? 'active-slide-brand' : '' }}">
                                        <img class="w-100" src="{{ $val->banner }}" alt="{{ ucfirst($val->name) }}">
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="container p-sm-3 mt-4 bg-F0F0F0">
            <div class="row">
                <div class="col-12 pt-4" id="wrapBrand">
                    <div class="mb-3 bg-F0F0F0  wow zoomIn" data-wow-delay="0.6s">
                        @if (request('brands'))
                            @php($filterBrand = $brands->where('slug', request('brands'))->first())
                            <div class="d-flex align-items-center">
                                <div class="default-brand">
                                    <img src="{{ $filterBrand->logo }}" class="img-fluid" alt="{{ $filterBrand->name }}">
                                </div>

                                <div class="card-body p-0 ms-3">
                                    <h1 class="text-primary mb-1">{{ $filterBrand->name }}</h1>
                                    <p class="card-text">{{ $filterBrand->{'description_'.$lang} }}</p>
                                </div>
                            </div>
                        @endif
                        @if (!request('brands') && isset($brands[0]))
                            <div class="d-flex align-items-center">
                                <div class="default-brand">
                                    <img src="{{ $brands[0]->logo }}" class="img-fluid" alt="{{ $brands[0]->name }}">
                                </div>

                                <div class="card-body p-0 ms-3">
                                    <h1 class="text-primary mb-1">{{ $brands[0]->name }}</h1>
                                    <p class="card-text">{{ $brands[0]->{'description_'.$lang} }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row pt-4 wrapProduct" id="wrapProduct">
                @if (request('brands'))
                    @php($filterBrand = $brands->where('slug', request('brands'))->first())
                    @foreach ($productBrand as $val)
                        @if (in_array($filterBrand->id, $val->brands->pluck('id')->toArray()))
                            <a href="{{ url("$lang/products/$val->slug") }}" title="{{ $val->name }}">
                                <div class="product-card me-3 bg-white">
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
                        @endif
                    @endforeach
                @endif
                @if (!request('brands'))
                    @foreach ($productBrand as $val)
                        @if (in_array($brands[0]->id, $val->brands->pluck('id')->toArray()))
                            <a href="{{ url("$lang/products/$val->slug") }}" title="{{ $val->name }}">
                                <div class="product-card me-3 bg-white">
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
                        @endif
                    @endforeach
                @endif
            </div>

            <div class="row">
                <div class="col-12 text-center ps-4 pe-4">
                    @if (request('brands'))
                        @php($slugBrand = $brands->where('slug', request('brands'))->first()->slug)
                        <a href="{{ url("$lang/brands/$slugBrand") }}" class="btn btn-primary w-100" id="actionBrands">
                            {{ translate('View All') }} <i class="fe fe-chevron-right"></i>
                        </a>
                    @endif
                    @if (!request('brands') && isset($brands[0]))
                        @php($slugBrand = $brands[0]->slug)
                        <a href="{{ url("$lang/brands/$slugBrand") }}" class="btn btn-primary w-100" id="actionBrands">
                            {{ translate('View All') }} <i class="fe fe-chevron-right"></i>
                        </a>
                    @endif

                    
                </div>
            </div>
        </div>
        <img src="{{ asset('images/rctangle-catalog-3.webp') }}" alt="" class="position-absolute d-none d-sm-block" style="bottom:0; left:0; z-index: -1">
    </div>

    <div class="container-fluid px-0 py-5 mx-0 home-catalogue position-relative">
        <div class="container position-relative" style="z-index: 9">
            <div class="row">
                <div class="col-12">
                    <div class="wow zoomIn catalogue-home ps-3" data-wow-delay="0.6s">
                        <h1 class="mb-0 text-primary">{{ translate('Catalogue') }}</h1>
                        <p>{{ translate('Find the right protective solutions for you industry, from head to toe.') }}</p>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="card-slider">
                                @foreach ($catalogue as $val)
                                    <a href="{{ url("/$lang/catalogue/$val->slug") }}" title="{{ $val->title }}">
                                        <div class="card p-3 mx-1 border mb-0">
                                            <div class="card-image-product">
                                                <img src="{{ $val->image }}" class="card-img-top" alt="{{ $val->title }}">
                                            </div>
                                            <div class="card-body text-center p-2 mt-2">
                                                <h3 class="mb-0 fz-16">{{ $val->title }}</h3>
                                                <p class="mb-0 fz-12">{{ descriptionProduct($val->{'description_'.$lang}, 25) }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('images/rctangle-catalog-2.webp') }}" alt="" class="position-absolute d-none d-sm-block" style="top:0; left:0">
        <img src="{{ asset('images/rctangle-catalog-1.webp') }}" alt="" class="position-absolute d-none d-sm-block" style="bottom:0; left:0; height:84.5%">
    </div>

    <div class="container-fluid px-0 mx-0 py-5">
        <div class="container">
            <div class="row wow zoomIn" data-wow-delay="0.6s">
                <div class="col-12 col-sm-6">
                    <h1 class="fz-35">50+ Years in Safety</h1>
                    @if ($about)
                        <div class="home-about-us">
                            {!! $about->{'description_'.$lang} !!}
                            <a title="About Us" class="text-primary" href="{{ url("$lang/about-us") }}">{{ translate('See More') }}...</a>
                        </div>
                    @endif
                </div>
                <div class="col-12 col-sm-6 text-center">
                    <iframe style="border-radius:20px; height:312px; width:100%" src="https://www.youtube.com/embed/1ouJfAvCLUE?si=fGpSyQXd2j6PiNgp" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0 mx-0 py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-8">
                    <div class="h1-button">
                        <h1 class="mb-0 d-inline-block bg-primary text-white d-inline-block py-2 px-3">{{ translate('Berkat Safety Outreach Program') }}</h1>
                        <hr class="bg-primary mb-4 mt-0">
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 csr-home">
                            <!--<ul>-->
                            <!--    @foreach ($csr as $key => $val)-->
                            <!--        @if ($key >= 0 && $key <= 4)-->
                            <!--            <li class="csr-list">-->
                            <!--                <a href="{{ url("$lang/about-us/csr/$val->slug") }}" title="{{ $val->name }}">-->
                            <!--                    <img src="{{ $val->image }}" alt="{{ $val->name }}">-->
                            <!--                </a>-->
                            <!--            </li>-->
                            <!--        @endif-->
                            <!--    @endforeach-->
                            <!--</ul>-->
                            @foreach ($csr as $key => $val)
                                <div class=" mb-3 border-bottom p-2 border-2">
                                    <a href="{{ url("{$lang}/about-us/csr/{$val->slug}") }}">
                                        <div class="row g-0">
                                            <div class="col-4">
                                                <div class="image-side-blogs">
                                                    <img src="{{ $val->image }}" class="img-fluid" alt="{{ $val->name }}">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body ps-2">
                                                    <h3>
                                                        <p class="card-text">{{ descriptionProduct($val->name, 100) }}</p></h3>
                                                        <p class="card-text">
                                                            <small class="text-muted">{{ $val->date }}</small>
                                                        </p>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <!--<div class="col-12 col-sm-6" style="text-align: justify">-->
                        <!--    @if (isset($csr[(count($csr) - 1)]))-->
                        <!--        <div>-->
                        <!--            <span style="font-size: 16px">{!! substr($csr[(count($csr) - 1)]->{'description_'.$lang}, 0, 450) !!}</span>-->
                        <!--        </div>-->
                        <!--        @php($slug = $csr[(count($csr) - 1)]->slug)-->
                        <!--        <a href="{{ url("$lang/about-us/csr/$slug") }}" class="btn btn-primary mb-4 mt-3 w-sm-100 w-75">-->
                        <!--            {{ translate('Learn More') }} <i class="fe fe-chevron-right"></i>-->
                        <!--        </a>-->
                        <!--    @endif-->
                        <!--</div>-->
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="h1-button">
                        <h1 class="mb-0 d-inline-block bg-primary text-white d-inline-block py-2 px-3">{{ translate('Latest News') }}</h1>
                        <hr class="bg-primary mb-4 mt-0">
                    </div>
                    @foreach ($news as $val)
                        <div class=" mb-3 border-bottom p-2 border-2">
                            <a href="{{ url("{$lang}/media/{$val->slug}") }}">
                                <div class="row g-0">
                                    <div class="col-8">
                                        <div class="card-body pe-2">
                                            <h3>
                                                <p class="card-text">{{ descriptionProduct($val->name, 100) }}</p></h3>
                                                <p class="card-text">
                                                    <small class="text-muted">{{ $val->date }}</small>
                                                </p>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="image-side-blogs">
                                            <img src="{{ $val->image }}" class="img-fluid" alt="{{ $val->name }}">
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            $('.card-slider, #category-product').slick({
                dots: true,
                arrows: false,
                slidesToShow: 5,
                infinite: false,
                autoplay: true,
                autoplaySpeed: 3500, 
                variableWidth: true,
                responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1
                    }
                }
                ]
            });

            $('.popular-brands').slick({
                arrows: true,
                slidesToShow: 8,
                infinite: false,
                autoplay: true,
                dots: true,
                autoplaySpeed: 3500, 
                responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 8
                    }
                },
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 3
                    }
                }
                ]
            });

            $('#wrapProduct').slick({
                dots: true,
                arrows: true,
                slidesToShow: 5,
                infinite: false,
                autoplay: true,
                autoplaySpeed: 3500, 
                variableWidth: true,
                responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1
                    }
                }
                ]
            });
        });

    </script>

    {{-- filter categoru & brand --}}
    <script>
        $(document).on('click', '.category-filter', function(e){
            e.preventDefault()
            let slug = $(this).attr('href')
            setParam({name:'category', value:slug})
        })

        $(document).on('click', '.brand-slide-home', function(e){
            e.preventDefault()
            let slug = $(this).attr('href')
            setParam({name:'brands', value:slug})
        })

        function setParam(param){
            var params = new URLSearchParams(location.search);
            params.set(param.name, param.value);
            window.location.href = location.pathname + '?' + params.toString();
        }
    </script>

    
    @if (request('brands') && request('category')) 
        <script>
            $(document).ready(function() {
                var navbarHeight = $("#menu-sticky-top").outerHeight() || 0;
                var sectionCategoryOffset = $("#section-brands").offset().top;
                
                $("html, body").animate({
                    scrollTop: sectionCategoryOffset - navbarHeight
                }, 500);
            });
        </script>
    @else
        @if (request('brands')) 
            <script>
                $(document).ready(function() {
                    var navbarHeight = $("#menu-sticky-top").outerHeight() || 0;
                    var sectionCategoryOffset = $("#section-brands").offset().top;
                    
                    $("html, body").animate({
                        scrollTop: sectionCategoryOffset - navbarHeight
                    }, 500);
                });
            </script>
        @endif
        @if (request('category')) 
            <script>
                $(document).ready(function() {
                    var navbarHeight = $("#menu-sticky-top").outerHeight() || 0;
                    var sectionCategoryOffset = $("#section-category").offset().top;
                    
                    $("html, body").animate({
                        scrollTop: sectionCategoryOffset - navbarHeight
                    }, 500);
                });
            </script>
        @endif
    @endif
@endsection


