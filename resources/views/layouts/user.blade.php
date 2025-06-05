<!DOCTYPE html>
<html class="no-js" lang="zxx">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{env('APP_NAME')}}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
        <link rel="icon" href="/images/icons.png" type="image/x-icon">
        <link rel="shortcut icon" href="/images/icons.png" type="image/x-icon">
        <link rel="stylesheet" href="{{mix('/dist/css/app.css')}}">
        <style>
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
        </style>
    </head>
    <body>

        <div id="app">
        @yield('content')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>

       
        <script src="{{mix('/dist/js/app.js')}}" defer></script>
        <script src="https://static-contents-smartjen.s3.ap-southeast-1.amazonaws.com/library/opus-media-recorder/OpusMediaRecorder.umd.js"></script>
        <script src="https://static-contents-smartjen.s3.ap-southeast-1.amazonaws.com/library/opus-media-recorder/encoderWorker.umd.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=TeX-AMS_HTML"></script>
        {{-- <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script> --}}
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.1.2/es5/tex-mml-chtml.js"  crossorigin="anonymous"></script> --}}
        <script type="text/javascript">
          // solving dollar issue
          MathJax.Hub.Config({
            tex2jax: { inlineMath: [["$","$"],["\\(","\\)"],["\\[","\\]"]] },
            "HTML-CSS": { linebreaks: { automatic: false } },
            SVG: { linebreaks: { automatic: true } },
            styles: {
              ".MathJax_Display": {
                "display": "inline",
                "text-align": "center",
                margin:       "1em 0em"
              },
            }
          });
      
        </script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
        {{-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
        <script>
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
          var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
          })
        </script>
    </body>
</html>
