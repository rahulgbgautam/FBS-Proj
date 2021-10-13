@include('layouts.front-meta')
    <!-- Google Noto Sans KR + Open Sans & Playfair Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&family=Open+Sans:wght@300;400;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('js/core/core.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin-style.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin-responsive-style.css')}}">
    <!-- End layout styles -->
</head>

<body>
    <div class="main-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @include('layouts.left-nav')
        <!-- partial -->
        <div class="page-wrapper">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar">
                <a href="{{url('/')}}" class="navbar-brand"><img src="{{asset('img/logo.png')}}"></a>
                <a href="#" class="sidebar-toggler">
                    <i data-feather="menu"></i>
                </a>
                <div class="navbar-content header-nav-content d-flex align-items-center">
                    @if(session('subscription') == 'yes')
                        <p>Purchase credits to add more domains</p>
                        <a type="submit" href="{{route('subscription-plan')}}" class="btn btn-outline-primary">Purchase Credits</a>
                    @else
                        @if(session('trial_time') == 'expired')
                            <p class="text-danger pr-4">Your trial period has been expired, please purchase subscription.</p>
                        @endif
                        <p>Subscribe to see your domain's full report</p>
                        <a type="submit" href="{{route('subscription-plan')}}" class="btn btn-outline-primary">Purchase Subscription</a>  
                    @endif
                         <a class="btn btn-outline-primary" target="_blank" href="{{asset('PDF/sample-report.pdf')}}">  Dummy Report</a>  
                </div>
            </nav>
            @yield('content')
        </div>
    </div>
    <!-- plugin js for this page -->
    <script src="{{asset('js/core/core.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>
</body>

</html>
