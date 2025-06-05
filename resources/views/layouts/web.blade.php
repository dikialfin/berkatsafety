<!DOCTYPE html>
<html class="no-js" lang="id">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="robots" content="index, follow">
        <meta name="googlebot" content="index, follow">

        <title>{{env('APP_NAME', 'Berkat Safety')}} - {{ $meta_title ?? '' }}</title>

        <meta name="description" content="{{ $meta_description ?? '' }}">
        <meta name="keywords" content="{{ $meta_keyword ?? '' }}">
        <meta name="author" content="{{env('APP_NAME')}}">

        <meta property="og:title" content="{{ $meta_title ?? '' }}">
        <meta property="og:description" content="{{ $meta_description ?? '' }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ $meta_image ?? '' }}"> 

        <meta name="twitter:card" content="{{ $meta_image ?? '' }}">
        <meta name="twitter:title" content="{{ $meta_title ?? '' }}">
        <meta name="twitter:description" content="{{ $meta_description ?? '' }}">
        <meta name="twitter:image" content="{{ $meta_image ?? '' }}">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
        <link rel="icon" href="/images/icons.png" type="image/x-icon">
        <link rel="shortcut icon" href="/images/icons.png" type="image/x-icon">
        <link rel="stylesheet" href="{{mix('/dist/css/app.css')}}">
        <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
        <style>
          body{
            padding-top: 76px;
          }
          .nav-landing {
              background-color: #fff !important;
              position: fixed;
              top: 0;
              z-index: 9999;
          }
          @media only screen and (max-width:575px){
            body{
                padding-top: 68px;
            }
            .sticky-top {
                  padding-bottom: 5px !important;
                  padding-top: 5px !important;
              }
          }
          html, body {
              width: 100vw;
              overflow-x: hidden;
          }

          .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple {
            width: 100%!important;
            padding: 0.8rem 1rem !important;
            border-radius: 8px !important;
            border: 1px solid #D2DDEC !important;
            line-height: 1.5 !important;
            font-weight: 400 !important;
            font-size: 0.9375rem !important;
            height: 40px !important;

            border-radius: 12px;
            color: #8a8a8a;
            font-family: Nunito Sans, sans-serif;
            font-size: 16px;
            line-height: 24px;
            padding: 12px 40px 12px 2px!important;
          }
          .select2-container{
            width: 100%!important;
          }

          .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px!important;
            position: absolute;
            top: 6px;
            right: 10px;
            width: 36px;
            font-size: 14pt;
          }

          .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 0;
          }

          .select2-container--default .select2-selection--multiple .select2-search__field {
            margin-top: 0;
          }
          .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height: 15px!important;
          }
          /* .table-select .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple{
            border-left: none!important;
            border-top-left-radius: 0!important;
            border-bottom-left-radius: 0!important;
          } */
          .v-toast{
            z-index: 9999!important;
          }
          .modal-content{
            border-radius: 16px;
          }
          .form-control:disabled{
            background-color: #f8f8f8!important;
          }
          a.card:hover{
              border:1px solid #f15d22!important;
          }
          a.card{
              text-decoration: none!important;
          }
          a.card{
              color: black;
          }
          .card-header{
            background-color:#f9fbfd;
          }
          .MathJax_Display{
            display: inline!important;
            margin:0px!important;
            text-align: left!important;
            height: 10px!important;
          }
          .carousel__prev, .carousel__next{
            z-index: 99;
            color: #f15d22;
          }

          .carousel{
            width: 100%;
          }
          .ellipsis {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            overflow: hidden;
            text-overflow: ellipsis;
          }
          .split-bg{background: #fff; height: 100%; top: 0; right: 0}
          .bg-F0F0F0{background-color: #F0F0F0}
        </style>
        @yield('style')
    </head>
    <body>
        @include('layouts.navbar')
          @yield('content')
        @include('layouts.footer')

        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script>
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
          var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
          })
          $(window).scroll(function(){
            $('nav').toggleClass('scrolled', $(this).scrollTop() > 20);
          });

          window.addEventListener('pagehide', () => {
              console.log('Halaman masuk ke bfcache');
              // Bersihkan event listener atau saluran pesan di sini
          });

          window.addEventListener('pageshow', (event) => {
              if (event.persisted) {
                  console.log('Halaman kembali dari bfcache');
                  // Pulihkan komunikasi atau saluran pesan di sini
              }
          });

        </script>
        <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
        <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
        <script>
            $(document).ready(function () {
                new WOW().init();
                const totalLebarViewport = $(window).width();
                const containerLebar = $('.bg-split').width();
                $('.split-bg').css('width', (totalLebarViewport - containerLebar) / 2)
                $('.dropdown-toggle').dropdown();
            });
        </script>
        @yield('scripts')
    </body>
</html>
