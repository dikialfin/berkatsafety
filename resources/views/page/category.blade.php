@extends('layouts.web')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessible-slick-theme.min.css') }}">
    <style>
        .img-category{width: 50px; height: 50px;}
        .img-category img{width: 100%; height: 100%; object-fit: contain; object-position: center}
        /* .navbar, .breadcrumbs{background-color: #F0F0F0 !important}  */
        .categories-section{
            background-image: url({{ asset('images/bg-category.webp') }});
            background-repeat: no-repeat;
            background-size: cover;
        }
        .rectangle-cate-1{
            right: 0;
            top: 0;
            width: 35rem;
        }
        .rectangle-cate-2{
            right: 0;
            bottom: 0;
            z-index: -1;
            width: 35rem;
        }
        
    </style>
@endsection

@section('content')
    <div class="container-fuild bg-white pb-4 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-primary"><a href="{{ url('/'.app()->getLocale()) }}">{{ translate('Home') }}</a></li>
                            <li class="breadcrumb-item text-primary"><a href="#">{{ translate('Category') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ translate('All Categories') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-0 mx-0 position-relative categories-section" style="z-index:10">
        <div class="container py-4">
            <div class="row mt-4">
                <div class="col-12 wow zoomIn" data-wow-delay="0.6s">
                    <h1 class="text-primary mb-0">{{ translate('Browse All Safety Categories') }}</h1>
                    <p class="text-dark">{{ translate('Find everything you need to protect your team, from head to toe') }}</p>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row wow fadeInUp" data-wow-delay="0.1s">
                                @foreach ($category as $val)
                                    <div class="col-12 col-sm-4 mb-4">
                                        <div class="border rounded p-3 br-20px">
                                                <div class="row">
                                                    <div class="col-3 text-center">
                                                        <div class="img-category mx-auto">
                                                            <img src="{{ $val->logo ? $val->logo : asset('images/brand/icon1.png') }}" alt="{{ ucfirst($val->name) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-9 ps-3">
                                                        <h2 class="text-primary mb-0">{{ ucfirst($val->name) }}</h2>
                                                        <ul class="list-group list-group-flush">
                                                            @foreach ($val->children as $_val)
                                                                <li class="list-group-item p-1">
                                                                    <a href="{{ url("/".app()->getLocale()."/category/$_val->slug") }}" class="border-bottom link-category" title="{{ $_val->name }}">{{ $_val->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img class="rectangle-cate-1 position-absolute" src="{{ asset('images/rectangle-cate-1.webp') }}" alt="">
        <img class="rectangle-cate-2 position-absolute" src="{{ asset('images/rectangle-cate-2.webp') }}" alt="">
        {{-- <div class="triangle-1"></div>
        <div class="triangle-2"></div> --}}
    </div>
    <div class="container-fluid bg-primary py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-1">
                    <h1 class="text-white mb-0">Popular Brands</h1>
                </div>
                <div class="col-12 col-sm-11">
                    <div class="popular-brands mb-0 mt-2">
                        @foreach ($brands as $key => $val)
                            <a 
                                href="{{ url($lang.'/brands/'.$val['slug']) }}" 
                                class="brand-slide-home text-primary" data-type="brands"
                            >
                                <div class="slide-wrap {{ $key == 0 ? 'active-slide-brand' : '' }}">
                                    <img class="w-100" src="{{ $val['banner'] }}" alt="{{ ucfirst($val['name']) }}">
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.popular-brands').slick({
                arrows: true,
                slidesToShow: 8,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 3000, 
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
        })
    </script>
@endsection


