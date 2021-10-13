@include('layouts.front-meta')
    <!-- Google Noto Sans KR + Open Sans & Playfair Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&family=Open+Sans:wght@300;400;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- endinject -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/animate.min.css')}}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/front-style.css')}}">
    <link rel="stylesheet" href="{{asset('css/front-responsive-style.css')}}">
    <!-- End layout styles -->
</head>

<body>
    @include('layouts.front-header')
    @yield('content')
    @include('layouts.front-footer')
    <!-- plugin js for this page -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <!-- end plugin js for this page -->
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <!-- inject:js -->
    <script src="{{asset('js/custom.js')}}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>
