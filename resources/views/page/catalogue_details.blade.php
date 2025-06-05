@extends('layouts.web')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/dflip.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/themify-icons.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .product-img{height: 285px !important;}
        .navbar, .breadcrumbs{background-color: #F0F0F0 !important} 
        .card-image-product{
            height: 325px;
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
                                <a href="{{ url('/'.app()->getLocale()) }}" title="{{ translate('Home') }}">{{ translate('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item text-primary">
                                <a href="{{ url('/'.app()->getLocale().'/catalogue') }}" title="{{ translate('Catalogue') }}">{{ translate('Catalogue') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($detail->title) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-12 bg-white">
                    <div class="row g-0 align-items-end">
                        <div class="col-12 col-sm-6 mt-4">
                            <div class="card-body p-0">
                                <h1 class="text-primary mb-1">{{ ucfirst($detail->title) }}</h1>
                                <p class="card-text">{{ $detail->{'description_'.$lang} }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 mt-4 text-end">
                            <div class="card-body p-0 d-flex align-items-center justify-content-end">
                                <a href="{{ $detail->file }}" download class="btn btn-outline-primary btn-lg">
                                    <i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                @php
                                    $file = explode('storage', $detail->file);
                                @endphp
                                <p class="card-text ms-2 fz-12" style="color:#3E3F5E">PDF - <span id="showSizePdf"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="_df_book" height="700" webgl="true" backgroundcolor="#f0f0f0" source="{{ $detail->file }}" id="df_manual_book">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5 pt-4 px-sm-5 mb-5" style="background: #F0F0F0; border-radius:10px">
            <div class="row">
                <div class="col-12 wow zoomIn" data-wow-delay="0.6s">
                    <h1 class="text-center">{{ translate('Related Catalogue You Might Like') }}</h1>
                </div>
            </div>
            <div class="row wow zoomIn" data-wow-delay="0.1s">
                @foreach ($catalogue as $val)
                    <div class="col-12 col-sm-3 mb-4">
                        <a href="{{ url("/$lang/catalogue/$val->slug") }}" title="{{ $val->title }}">
                            <div class="card p-3 border">
                                <div class="card-image-product">
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
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/dflip.min.js') }}"></script>
    <script>
        async function getFileSize(url) {
            try {
                let response = await fetch(url, { method: "HEAD" });
                let fileSize = response.headers.get("Content-Length");

                if (fileSize) {
                    fileSize = (fileSize / 1024 / 1024).toFixed(2);
                    document.getElementById("showSizePdf").innerText = `${fileSize} MB`;
                } else {
                    document.getElementById("fileSize").innerText = "Unable to fetch file size.";
                }
            } catch (error) {
                console.error("Error fetching file size:", error);
                document.getElementById("fileSize").innerText = "Error fetching file size.";
            }
        }
        $(document).ready(function(){
            let url = "{{ $detail->file }}"
            getFileSize(url)
        })
    </script>
@endsection


