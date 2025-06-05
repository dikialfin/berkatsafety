@extends('layouts.web')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessible-slick-theme.min.css') }}">
    <style>
        .product-img{height: 285px !important;}
        .navbar, .breadcrumbs{background-color: #F0F0F0 !important} 
        .product-card{
            height: 370px !important;
        }
        .slick-slide {
            margin-right: 15px;
        }
        .slick-pause-icon{display: none}
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
                                <a href="{{ url('/'.app()->getLocale()) }}" title="{{ translate('Home') }}">{{ translate('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ translate('Catalogue') }}</li>
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
                        <h3 class="text-primary mb-2">{{ translate('Brands') }}</h3>
                        <hr style="height: 3px" class="bg-primary my-2">
                        <ul class="list-group bg-white border-rounded">
                            @foreach ($brands as $val)
                                <a href="{{ url("$lang/catalogue-brands/{$val->slug}") }}" title="{{ ucfirst($val->name) }}" class="text-dark">
                                    <li class="list-group-item border-0 p-3 border-bottom d-flex text-dark align-items-center">
                                        <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center">
                                            <img src="{{ $val->logo }}" alt="" style="width: 65%; height:65%;">
                                        </div>
                                        <span class="ms-2 fz-13 fw-bold">{{ ucfirst($val->name) }}</span>
                                        @if (in_array($val->slug, [$slug])) 
                                            <div style="width: 20px; height:20px; border-radius: 50%; line-height:20px" class="bg-white text-center  ms-auto">
                                                <img src="{{ asset('images/checked.svg') }}" alt="" style="width: 65%; height:65%;">
                                            </div>
                                        @endif
                                        @if ($allBrand)
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
                <div class="col-12 col-sm-9 bg-white pb-5">
                    <div class="row g-0 align-items-end">
                        <div class="col-md-12 mt-4 wow zoomIn" data-wow-delay="0.6s">
                            <div class="card-body p-0">
                                <h1 class="text-primary mb-1">{{ translate('Catalogue') }}</h1>
                                <p class="card-text">{{ translate('Catalogue - Find the right protective solutions for your industry, from head to toe.') }}</p>
                            </div>
                        </div>
                    </div>
                    <form action="{{ url()->current() }}" method="GET" class="mb-3">
                        <input type="text" name="search" class="form-control mt-3" placeholder="Search" value="{{ request('search') }}" >
                    </form>
                    <div class="row mt-4 wow fadeInUp " data-wow-delay="0.1s">
                        @foreach ($catalogue as $val)
                            <div class="col-12 col-sm-3 p-1">
                                <a href="{{ url("/$lang/catalogue/$val->slug") }}" title="{{ $val->title }}">
                                    <div class="card p-3 border mb-0">
                                        <div class="">
                                            <img src="{{ $val->image }}" class="card-img-top" alt="{{ $val->title }}">
                                        </div>
                                        <div class="card-body text-center p-2 mt-2">
                                            <h3 class="mb-0">{{ $val->title }}</h3>
                                            <p class="mb-0">{{ substr($val->{'description_'.$lang}, 0, 70) }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach 
                        @if ($catalogue->isEmpty())
                            <div class="col-12 text-center">
                                <div class="col-12 col-sm-4 mx-sm-auto text-center">
                                    <img src="{{ asset('images/no-data.png') }}" alt="" class="w-5rem">
                                    <p class="text-muted text-center">No Data</p>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            {{ $catalogue->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="split-bg position-absolute"></div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            function checkScreenSize() {
                if (window.matchMedia("(min-width: 320px) and (max-width: 575.98px)").matches) {
                    $('.product-catalog').slick({
                        arrows: true,
                        slidesToShow: 4,
                        infinite: false,
                        autoplay: true,
                        dots: false,
                        autoplaySpeed: 3000, 
                        centerMode: false,
                        variableWidth: true,
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
                                slidesToShow: 1
                            }
                        }
                        ]
                    });
                    
                } else {
                    console.log("Layar di luar rentang tersebut");
                }
            }

            checkScreenSize();

            window.addEventListener("resize", checkScreenSize);
        })
    </script>
@endsection


