@extends('layouts.web')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessible-slick-theme.min.css') }}">
    <style>
        .navbar, .breadcrumbs{background-color: #F0F0F0 !important} 
        .color-3334CC{color: #3334CC}
        .slick-slide {
            margin-right: 15px;
        }
        .slick-pause-icon{display: none}
    </style>
@endsection

@section('content')
    <div class="container-fuild breadcrumbs pb-4 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-primary"><a href="{{ url("/$lang") }}">Home</a></li>
                            <li class="breadcrumb-item text-primary"><a href="{{ url("$lang/brands") }}">Brands</a></li>
                            @if(isset($detail->brands[0]))
                                <li class="breadcrumb-item text-primary"><a href="{{ url("$lang/brands/".$detail->brands[0]->slug) }}">{{ $detail->brands[0]->name }}</a></li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">{{ descriptionProduct(ucfirst($detail->name), 10) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 wow zoomIn" data-wow-delay="0.1s">
                    <div class="row">
                        <div class="col-sm-3 col-12 order-2 order-sm-1 product-detail">
                            @foreach ($detail->productMedia as $key => $val)
                                <div class="thumb-product border border-2 {{ $key == 0 ? 'border-primary' : 'border-light'}} mb-1">
                                    <img src="{{ $val->value }}" alt="{{ ucfirst($detail->name) }}" class="w-100">
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-9 col-12 order-1 order-sm-2">
                            <div style="width: 100%; height:350px">
                                <img src="{{ $detail->productMedia[0]->value }}" alt="{{ ucfirst($detail->name) }}" class="w-100" id="imagePreview">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 p-4 border-rounded position-relative wow zoomIn" style="background-color: #F0F0F0 !important; border-radius:10px" data-wow-delay="0.6s">
                    <h1 class="text-primary mb-4">{{ ucfirst($detail->name) }}</h1>
                    <div class="description">
                        {!! $detail->{'description_'.$lang} !!}
                    </div>
                    <div class="d-sm-flex w-100 p-3 " style="left: 0; bottom:0">
                        <a class="d-sm-flex mt-2 align-items-center justify-content-center btn btn-primary" href="tel:{{ $setting->make_a_request }}" style="width: 100%">
                            <span class="fw-bold me-2">Make a Request</span> <img src="{{ asset('images/phone-call-01.png') }}" alt="Make a Request">
                        </a>
                        <a href="https://wa.me/{{ $setting->ask_our_admin }}?text=Halo%20Admin,%20saya%20minta informasi teantang produk {{ ucfirst($detail->name) }}" 
                            target="_blank"
                            class="d-sm-flex mt-2 align-items-center justify-content-center btn btn-outline-primary border-3 ms-sm-3"
                            style="width: 100%">
                                <span class="fw-bold me-2">Ask our Admin</span>
                                <img src="{{ asset('images/message-question-circle.png') }}" alt="Ask our Admin">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if ($detail->{'specification_'.$lang})
            <hr>
            <div class="container specification mt-5 p-5" id="specification" style="background-color: #F0F0F0; border-radius:10px;">
                <h1 class="text-primary text-center">SPECIFICATION</h1>
                <div>
                    {!! $detail->{'specification_'.$lang} !!}
                </div>
            </div>
        @endif

        <div class="container pt-5">
            <div class="row mt-5">
                <div class="col-12 text-center wow zoomIn" data-wow-delay="0.6s">
                    <h1 class="mb-2 text-primary">Related Products You Might Need</h1>
                </div>
            </div>
            <div class="row mt-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="another-products">
                    @foreach ($product as $val)
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
                    @endforeach
                </div>
            </div>
        </div>
    </div>
   
@endsection
@section('scripts')
<script src="{{ asset('js/slick.min.js') }}"></script>
<script>
    $(document).on('click', '.thumb-product', function(){
        $('.thumb-product').removeClass('border-primary')
        $(this).removeClass('border-light')
        $(this).addClass('border-primary')
        let image = $(this).children('img').attr('src')
        $('#imagePreview').attr('src', image)
    })

    $(document).ready(function () {
        $(".description").each(function () {
            let content = $(this);
            let originalHeight = content.height();
            let maxHeight = 200;

            if (originalHeight > maxHeight) {
                content.css({
                    "height": maxHeight + "px",
                    "overflow": "hidden",
                    "position": "relative"
                });

                content.after('<a href="#" class="toggle-btn color-3334CC mt-3 d-block">Show More <i class="fa fa-angle-down fw-bold"></i></a>');
            }
        });

        $(document).on("click", ".toggle-btn", function (e) {
            e.preventDefault();
            let content = $(this).prev(".description");

            if (content.hasClass("expanded")) {
                content.removeClass("expanded").css("height", "200px");
                $(this).html('Show More <i class="fa fa-angle-down fw-bold"></i>');
            } else {
                content.addClass("expanded").css("height", "auto");
                $(this).html('Show Less <i class="fa fa-angle-up fw-bold"></i>');
            }
        });

        $('.specification table').addClass('table table-bordered')
        $('#specification table th, #specification table td').addClass('border border-dark p-2')

        function checkScreenSize() {
            $('.another-products').slick({
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
            
            $('.product-detail').slick({
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
        }

        checkScreenSize();

        window.addEventListener("resize", checkScreenSize);
    });


</script>
@endsection


