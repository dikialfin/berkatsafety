@extends('layouts.web')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        p{text-align: justify}
        nav{z-index: 9999999;}
        .rounded-contact-top{width: 100%; height: 96px; top: 0;left: 0;}
        .rounded-contact-top img{width: 100%; height: 100%; object-fit: cover; height: 100%; object-position: center; border-top-left-radius: 20px; border-top-right-radius: 20px;}
        .rounded-contact-bottom{width: 100%;  bottom: 0;left: 0;}
        .rounded-contact-bottom img{width: 100%; height: 100%; object-fit: cover; height: 100%; object-position: center; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;}
        .content-left{z-index: 4;}
        .lable {position: relative; display: inline-block; margin-left: 10px}
        .lable i{position: absolute; top: 5px; font-size: 5px; color: red; right: -6px;}
        .tagline-about{top: 30% !important}
    </style>
@endsection

@section('content')
    <div class="banner-about position-relative pt-103px">
        <img src="{{ asset('images/bg-contact.webp') }}" alt="">
        <div class="tagline-about position-absolute wow zoomIn" data-wow-delay="0.6s">
            <h1 class="text-white mb-2 fz-64">{{ translate("Contact Us - Welcome to Berkat Safety") }}</h1>
            <p class="text-white text-center fz-32">{{ translate('Contact Us -Need expert advice on the right protective') }}</p>
        </div>
    </div>
    <div class="container-fluid bg-sidebar-web position-relative py-5">
        <div class="row">
            <div class="col-12 col-sm-11 mx-sm-auto">
                <div class="row ">
                    <div class="order-2 order-sm-1 col-12 col-sm-4 position-relative p-4 pt-5" style="height: 600px; background:#012266; border-radius:20px">
                        <div class="position-relative content-left text-white mt-5">
                            <h2 class="mb-2">{{ translate('Contact Us - Get in Touch with Us!') }}</h2>
                            <p>{{ translate('Contact Us - Fill out the contact form below') }}</p>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d991.6769552349465!2d106.810527!3d-6.1698640000000005!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f67bc1746d5b%3A0x3239cb65a8de3a30!2sBerkat%20Niaga%20Dunia!5e0!3m2!1sen!2sus!4v1738843515412!5m2!1sen!2sus" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="rounded-contact-top position-absolute">
                            <img src="{{ asset('images/rounded-contact-top.webp') }}" alt="" class="">
                        </div>
                        <div class="rounded-contact-bottom position-absolute">
                            <img src="{{ asset('images/rounded-contact-bottom.webp') }}" alt="" class="">
                        </div>
                        <div class="row mt-5">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/phone-icon.png') }}" alt="">
                                    <div class="text-white ms-3">
                                        <h4 class="mb-0">PHONE</h4>
                                        <a href="tel:0216327060" class="text-white"><p class="fz-13 mb-0">(021) 6327060</p></a>
                                        <a href="tel:0216327065" class="text-white"><p class="fz-13 mb-0">(021) 6327065</p></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/mail-icon.png') }}" alt="">
                                    <div class="text-white ms-3">
                                        <h4 class="mb-0">EMAIL</h4>
                                        <a href="mailto:info@bndsafety.net" class="text-white"><p class="fz-13 mb-0">info@bndsafety.net</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order-1 order-sm-2 col-12 col-sm-8 px-sm-4 mb-5 mb-sm-0">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session('success') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                        @endif

                        <form action="{{ route('contacts.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="lable">{{ translate('Contact Us - Full Name') }} <i class='fa fa-asterisk'></i></label>
                                <input type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname') }}">
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="lable">{{ translate('Contact Us - Company') }} <i class='fa fa-asterisk'></i></label>
                                <input type="text" class="form-control @error('company') is-invalid @enderror" name="company" value="{{ old('company') }}">
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="lable">{{ translate('Contact Us - Job Title') }} <i class='fa fa-asterisk'></i></label>
                                <input type="text" class="form-control @error('job_title') is-invalid @enderror" name="job_title" value="{{ old('job_title') }}">
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="lable">Email <i class='fa fa-asterisk'></i></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="lable">{{ translate('Contact Us - Message') }} <i class='fa fa-asterisk'></i></label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-primary w-100">SEND</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection


