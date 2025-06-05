<nav class="navbar navbar-expand-lg navbar-light bg-light nav-landing sticky-top" id="menu-sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/'.app()->getLocale()) }}">
            <img src="{{asset('/images/logo-home.png')}}" alt="" height="50" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse me-auto ms-3" id="navbarNavDropdown">
            <ul class="navbar-nav" style="gap:14px">
                <li class="nav-item">
                    <a class="nav-link {{ empty(Request::segment(2)) ? 'active' : '' }}" aria-current="page" href="{{ url('/'.app()->getLocale()) }}" title="{{ translate('Home') }}">{{ translate('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'category' ? 'active' : '' }}" href="{{ url('/'.app()->getLocale().'/category') }}" title="{{ translate('Category') }}">{{ translate('Category') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'brands' ? 'active' : '' }}" href="{{ url('/'.app()->getLocale().'/brands') }}" title="{{ translate('Brands') }}">{{ translate('Brands') }}</a>
                </li>
              
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'catalogue' ? 'active' : '' }}" href="{{ url('/'.app()->getLocale().'/catalogue') }}" title="{{  translate('Catalogue') }}">{{  translate('Catalogue') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'about-us' ? 'active' : '' }}" href="{{ url('/'.app()->getLocale().'/about-us') }}" title="{{ translate('Menu - About Us') }}">{{ translate('Menu - About Us') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'media' ? 'active' : '' }}" href="{{ url('/'.app()->getLocale().'/media') }}" title="{{ translate('Media') }}">{{ translate('Media') }}</a>
                </li>
            </ul>
            <div class="d-flex d-lg-none gap-3">
                <a href="{{ url('/'.app()->getLocale().'/contact-us') }}" class="btn btn-primary btn-contact">
                    {{ translate('Contact Us') }} <img src="{{ asset('images/phone-icon.png') }}" alt="" style="width:20px;">
                </a>
                <div class="dropdown">
                    <button class="btn btn-white btn-contact dropdown-toggle text-uppercase d-flex align-items-center" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (strtolower(app()->getLocale()) == 'en')
                            <img src="{{ asset('images/flag_eng.png') }}" alt="" style="width: 20px"> <span class="ms-2 fw-bold">ENGLISH</span>
                        @else 
                            <img src="{{ asset('images/flag_indo.webp') }}" alt="" style="width: 20px"> <span class="ms-2 fw-bold">INDONESIA</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <a class="dropdown-item" href="{{ url('/id' . substr(Request::getRequestUri(), 3)) }}">
                                INDONESIA
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/en' . substr(Request::getRequestUri(), 3)) }}">
                                ENGLISH
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
       
        <div class="d-none d-lg-flex gap-3">
            <a href="{{ url('/'.app()->getLocale().'/contact-us') }}" class="btn btn-primary btn-contact">
                {{ translate('Contact Us') }} <img src="{{ asset('images/phone-icon.png') }}" alt="" style="width:20px;">
            </a>
            <div class="dropdown">
                <button class="btn btn-white btn-contact dropdown-toggle text-uppercase d-flex align-items-center" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    @if (strtolower(app()->getLocale()) == 'en')
                        <img src="{{ asset('images/flag_eng.png') }}" alt="" style="width: 20px"> <span class="ms-2 fw-bold">ENGLISH</span>
                    @else 
                        <img src="{{ asset('images/flag_indo.webp') }}" alt="" style="width: 20px"> <span class="ms-2 fw-bold">INDONESIA</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="{{ url('/id' . substr(Request::getRequestUri(), 3)) }}">INDONESIA</a></li>
                    <li><a class="dropdown-item" href="{{ url('/en' . substr(Request::getRequestUri(), 3)) }}">ENGLISH</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>