@extends('layouts.web')

@section('style')
    <style>
        .navbar, .breadcrumbs{background-color: #F0F0F0 !important} 
        .h-144{height: 144px;}
        .h-144 img{height: 100%; width: 100%; object-fit: contain; object-position: center}
    </style>
@endsection

@section('content')
    <div class="container-fuild breadcrumbs pb-4 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-primary">
                                <a href="{{ url('/'.app()->getLocale()) }}" title="{{ translate('Home') }}">{{ translate('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ translate('Media') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-white pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body p-0 wow zoomIn" data-wow-delay="0.6s">
                                <h1 class="text-primary mb-1">{{ $detail->name }}</h1>
                                <p class="mb-0 text-muted d-flex">
                                    <img src="{{ asset('images/user_blog.png') }}" alt="{{ $detail->name }}"> <span class="ms-2 me-3">{{ $detail->user }}</span>
                                    <img src="{{ asset('images/eclips.svg') }}" alt="{{ $detail->name }}" class="me-2"/>
                                    {{ date('d/m/Y H:i A', strtotime($detail->created_at)) }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4 wow zoomIn" data-wow-delay="0.1s">
                            <img src="{{ $detail->image }}" class="card-img-top" alt="{{ $detail->name }}">
                            {!! $detail->{'description_'.$lang} !!}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 wow zoomIn" data-wow-delay="0.1s">
                   <div class="h1-button">
                        <h1 class="mb-0 d-inline-block bg-primary text-white d-inline-block py-2 px-3">{{ translate('Latest News') }}</h1>
                        <hr class="bg-primary mb-4 mt-0">
                    </div>
                    @foreach ($news as $val)
                        <div class=" mb-3 border-bottom p-2 border-2">
                            <a href="{{ url("{$lang}/media/{$val->slug}") }}">
                                <div class="row g-0">
                                    <div class="col-8">
                                        <div class="card-body p-2">
                                            <h3>
                                                <p class="card-text">{{ descriptionProduct($val->name) }}</p></h3>
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


