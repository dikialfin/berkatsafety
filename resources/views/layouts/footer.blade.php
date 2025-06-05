<footer class="bg-primary py-sm-5 py-3">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-3 px-3 px-sm-0">
                <img src="{{ asset('images/logo-white.png') }}" alt="" style="width: 12rem; ">
                <div class="d-flex text-white px-3 px-sm-0">
                    {{-- <i class="fe fe-address-book"></i> --}}
                    <p class="fz-14 ta-justify ms-2">Jl. Cideng Barat No.47D, RT.9/RW.4, Cideng, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10150</p>
                </div>
                <div class="d-flex text-white  px-3 px-sm-0">
                    <i class="fe fe-mail"></i>
                    <a href="mailto:info@bndsafety.net" class="text-white">
                        <p class="fz-14 ta-justify ms-2">info@bndsafety.net</p>
                    </a>
                </div>
                <div class="d-flex text-white  px-3 px-sm-0 mb-3">
                    <i class="fe fe-phone"></i>
                    <div>
                        <a href="tel:0216327060" class="text-white d-block">
                            <p class="fz-14 ta-justify ms-2 mb-0">(021) 6327060</p>
                        </a>
                        <a href="tel:0216327065" class="text-white d-block">
                            <p class="fz-14 ta-justify ms-2 mb-0">(021) 6327065</p>
                        </a>
                    </div>
                </div>
                <div class="d-flex text-white  px-3 px-sm-0 align-items-center mb-3">
                    <i class="fa fa-facebook-square"></i>
                    <a href="https://www.facebook.com/berkatsafety" target="_blank" class="text-white d-block">
                        <p class="fz-14 ta-justify ms-2 mb-0">berkatsafety</p>
                    </a>
                </div>
                <div class="d-flex text-white  px-3 px-sm-0 align-items-center mb-3">
                    <i class="fa fa-instagram"></i>
                    <a href="https://www.instagram.com/berkatsafety_id" target="_blank" class="text-white d-block">
                        <p class="fz-14 ta-justify ms-2 mb-0"> berkatsafety_id</p>
                    </a>
                </div>
                <div class="d-flex text-white  px-3 px-sm-0 align-items-center">
                    <i class="fa fa-youtube"></i>
                    <a href="https://www.youtube.com/@Berkatsafety_id" target="_blank" class="text-white d-block">
                        <p class="fz-14 ta-justify ms-2 mb-0"> @Berkatsafety_id</p>
                    </a>
                </div>
                
                
            </div>
            <div class="col-12 col-sm-9 mt-4">
                <div class="row">
                    <div class="col-12 col-sm-3 ps-sm-5 mb-4">
                        <h2 class="text-white mb-2 px-3 px-sm-0">{{ translate('The Company') }}</h2>
                        <ul class="list-group list-group-flush px-3 px-sm-0">
                            @foreach (aboutUs() as $val)
                                <li class="list-group-item p-1 border-0">
                                    <a class="text-white fz-12" href="{{ url('/'.app()->getLocale()."/about-us/$val->slug") }}" title="{{ $val->name }}">{{ $val->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-12 col-sm-3 ps-sm-5 mb-4">
                        <h2 class="text-white mb-2 px-3 px-sm-0">{{ translate('Products') }}</h2>
                        <ul class="list-group list-group-flush px-3 px-sm-0">
                            <li class="list-group-item p-1 border-0">
                                <a class="text-white fz-12" href="{{ url('/'.app()->getLocale().'/brands') }}" title="Brands">{{ translate('Brands') }}</a>
                            </li>
                            <li class="list-group-item p-1 border-0">
                                <a class="text-white fz-12" href="{{ url('/'.app()->getLocale().'/category') }}" title="Categories">{{ translate('Categories') }}</a>
                            </li>
                            {{-- <li class="list-group-item p-1 border-0">
                                <a class="text-white fz-12" href="{{ url('/'.app()->getLocale().'/about-us/industry-solution') }}">{{ translate('Industry Solution') }}</a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="col-12 col-sm-3 ps-sm-5 mb-4">
                        <h2 class="text-white mb-2 px-3 px-sm-0">{{ translate('Media') }}</h2>
                        <ul class="list-group list-group-flush px-3 px-sm-0">
                            <li class="list-group-item p-1 border-0">
                                <a class="text-white fz-12" href="{{ url('/'.app()->getLocale().'/media') }}" title="News">{{ translate('News') }}</a>
                            </li>
                            <li class="list-group-item p-1 border-0">
                                <a class="text-white fz-12" href="{{ url('/'.app()->getLocale().'/catalogue') }}" title="Catalogue">{{ translate('Catalogue') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-3 text-center">
                        <img src="{{ asset('images/certificate.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="copyright py-4 bg-518C24">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0 text-white fz-13">{{ translate('Copyright') }} &copy; {{ date('Y') }} {{ translate('All right reserved.') }}</p>
            </div>
        </div>
    </div>
</div>