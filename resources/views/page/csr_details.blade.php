@extends('layouts.web')

@section('style')
<style>
    .navbar,
    .breadcrumbs {
        background-color: #F0F0F0 !important
    }

    .h-144 {
        height: 144px;
    }

    .h-144 img {
        height: 100%;
        width: 100%;
        object-fit: contain;
        object-position: center
    }

    @media (min-width: 320px) and (max-width: 575.98px) {
        .content img {
            width: 100%
        }
    }

    .carousel {
        /* any dimensions are fine, it can be responsive with max-width */
        height: 500px !important;
        background-color: black;
    }

    .carousel-inner, .carousel-item {
        /* make sure your .items will get correct height */
        height: 100% !important;
        width: 100% !important;
    }

    .carousel-item {
        background-size: cover !important;
        background-position: 50% 50% !important;
    }

    .carousel-item img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        opacity: 60%;
    }
</style>
@endsection

@section('content')
<div class="container-fuild breadcrumbs pb-4 pt-103px">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item text-primary">
                            <a href="{{ url('/'.app()->getLocale().'/about-us') }}" title="About Us">About Us</a>
                        </li>
                        <li class="breadcrumb-item text-primary">
                            <a href="{{ url('/'.app()->getLocale().'/about-us/csr') }}" title="CSR">CSR</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $detail->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid bg-white pt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card-body p-0 wow zoomIn" data-wow-delay="0.6s">
                    <h1 class="text-primary mb-1">{{ $detail->name }}</h1>
                    <p class="mb-0 text-muted d-flex">
                        <img src="{{ asset('images/user_blog.png') }}" alt="{{ $detail->name }}"> <span class="ms-2 me-3">{{ $detail->user }}</span>
                        <img src="{{ asset('images/eclips.svg') }}" alt="{{ $detail->name }}" class="me-2" />
                        {{ date('d/m/Y H:i A', strtotime($detail->created_at)) }}
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-8 content">
                <div class="row">
                    <div class="col-md-12 mt-4 wow zoomIn" data-wow-delay="0.6s">
                        <!-- START Carousel -->
                        <div id="carouselExampleIndicators" class="carousel slide mb-4">
                            <div class="carousel-indicators">
                                @if(count($medialist) < 1)
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                @else 
                                    @for($index=0; $index < count($medialist); $index++)
                                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$index}}" class="{{$index == 0 ? 'active' : ''}}" aria-current="true" aria-label="Slide 1"></button>
                                    @endfor
                                @endif
                            </div>
                            <div class="carousel-inner">
                                @if(count($medialist) < 1)
                                <div class="carousel-item active">
                                    <img src="{{ $detail->image }}" class="d-block w-100" alt="...">
                                </div>
                                @else
                                <?php $imageIterator = 0; ?>
                                @foreach($medialist as $image)
                                <div class="carousel-item <?= $imageIterator == 0 ? 'active' : ''; ?>">
                                    <img src="{{ $image->value }}" class="d-block w-100" alt="...">
                                </div>
                                <?php $imageIterator++; ?>
                                @endforeach
                                @endif
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        </div>
                        <!-- END Carousel -->

                        {!! $detail->{'description_'.$lang} !!}
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4 wow zoomIn" data-wow-delay="0.1s">
                <div class="h1-button">
                    <h1 class="mb-0 d-inline-block bg-primary text-white d-inline-block py-2 px-3">Latest CSR</h1>
                    <hr class="bg-primary mb-4 mt-0" style="z-index: 1 !important">
                </div>
                @foreach ($news as $val)
                <div class=" mb-3 border-bottom p-2 border-2">
                    <a href="{{ url("{$lang}/about-us/csr/{$val->slug}") }}">
                        <div class="row g-0">
                            <div class="col-8">
                                <div class="card-body">
                                    <h3>
                                        <p class="card-text">{{ descriptionProduct($val->name) }}</p>
                                    </h3>
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
</div>
@endsection
@section('scripts')

@endsection