@extends('layouts.web')

@section('style')
    <style>
        .navbar, .breadcrumbs{background-color: #F0F0F0 !important} 
        .card-title {
            height: 4.2rem;
        }
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
                <div class="col-12">
                    <div class="card-body p-0 wow zoomIn" data-wow-delay="0.6s">
                        <h1 class="text-primary mb-1">{{ translate('News & Insights') }}</h1>
                        <p class="card-text">{{ translate('Bringing you the latest stories, insights, and updates to keep your team protected.') }}</p>
                    </div>
                </div>
                <div class="col-12 col-sm-8 wow zoomIn" data-wow-delay="0.6s">
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <form action="{{ url()->current() }}" method="GET" class="mb-3">
                                <input type="text" name="search" class="form-control mt-3" placeholder="Search" value="{{ request('search') }}" >
                            </form>
                            <div class="row mt-4">
                                @foreach ($blogs as $val)
                                    <div class="col-12 col-sm-4">
                                        <a href="{{ url("$lang/media/$val->slug") }}">
                                            <div class="card border pt-2">
                                                <div class="h-144">
                                                    <img src="{{ $val->image }}" class="card-img-top" alt="{{ $val->name }}">
                                                </div>
                                                <div class="card-body p-3">
                                                    <p class="mb-0 text-muted">{{ date('d/m/Y H:i A', strtotime($val->created_at)) }}</p>
                                                    @if($val->is_announcement)
                                                    <span class="badge text-bg-warning p-2 my-3">Announcement</span>
                                                    @else
                                                    <span class="badge text-bg-primary p-2 my-3">News</span>
                                                    @endif
                                                    <h3 class="card-title fz-15">
                                                        {{ truncateWord($val->name, 9) }}
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($blogs->isEmpty())
                            <div class="col-12 text-center">
                                <div class="col-12 col-sm-4 mx-sm-auto text-center">
                                    <img src="{{ asset('images/no-data.png') }}" alt="" class="w-5rem">
                                    <p class="text-muted text-center">No Data</p>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            {{ $blogs->links() }}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 wow zoomIn" data-wow-delay="0.6s">
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
                                                <p class="card-text">{{ truncateWord($val->name, 6) }}</p></h3>
                                                <p class="card-text">
                                                    <small class="text-muted">{{ $val->date }}</small>
                                                </p>
                                                 @if($val->is_announcement)
                                                    <span class="badge text-bg-warning p-2 my-3">Announcement</span>
                                                    @else
                                                    <span class="badge text-bg-primary p-2 my-3">News</span>
                                                    @endif
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


