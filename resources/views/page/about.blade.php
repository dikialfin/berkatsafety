@extends('layouts.web')

@section('style')
    <style>
        p{text-align: justify}
        nav{z-index: 9999999;}
        .content-about img{
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="banner-about position-relative pt-103px">
        <img src="{{ asset('images/banner-about-us.webp') }}" alt="">
        <div class="tagline-about position-absolute wow zoomIn" data-wow-delay="0.6s">
            <h1 class="text-white mb-2">{{ translate('About - Brings Future Goodness through Prime Protection') }}</h1>
            <p class="text-white text-center">{{ translate('Shaping a Safer Future with Reliable Protection Gear') }}</p>
        </div>
    </div>
    <div class="container-fluid bg-sidebar-web position-relative">
        <div class="container bg-split">
            <div class="row">
                <div class="col-12 col-sm-3 pt-5">
                    <div class="mb-5">
                        <h3 class="text-primary mb-2">{{ translate('Get to Know More') }}:</h3>
                        <hr style="height: 3px" class="bg-primary my-2">
                        <ul class="list-group bg-white border-rounded about-us-side">
                            @foreach ($menu as $val)
                                <a href="{{ url("$lang/about-us/$val->slug") }}" title="{{ $val->name }}" class="{{ $page == $val->slug ? 'active' : '' }}">
                                    <li class="list-group-item p-3 border-bottom d-flex align-items-center ">
                                        <span class="ms-2 fz-13 fw-bold text-dark">{{ $val->name }}</span>
                                        <i class="fe fe-chevron-right fz-16 fw-bold ms-auto"></i>
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-9 bg-white py-5 px-sm-4 wow zoomIn" data-wow-delay="0.6s">
                    <div class="content-about">
                        {!! $about->{'description_'.$lang} !!}
                    </div>
                    @if ($page == 'csr')
                        <form action="{{ url()->current() }}" method="GET" class="mb-3">
                            <input type="text" name="search" class="form-control mt-3" placeholder="Search" value="{{ request('search') }}" >
                        </form>
                        <div class="row">
                            @foreach ($csr as $val)
                                <div class="col-12 col-sm-4">
                                    <a href="{{ url("$lang/about-us/csr/$val->slug") }}">
                                        <div class="card border">
                                            <div class="h-144">
                                                <img src="{{ $val->image }}" class="card-img-top" alt="{{ $val->name }}">
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="mb-0 text-muted">{{ date('d/m/Y H:i A', strtotime($val->created_at)) }}</p>
                                                <h3 class="card-title fz-15">{{ descriptionProduct($val->name) }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                            @if ($csr->isEmpty())
                                <div class="col-12 text-center">
                                    <div class="col-12 col-sm-4 mx-sm-auto text-center">
                                        <img src="{{ asset('images/no-data.png') }}" alt="" class="w-5rem">
                                        <p class="text-muted text-center">No Data</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-12">
                                {{ $csr->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="split-bg position-absolute"></div>
    </div>
@endsection
@section('scripts')

@endsection


