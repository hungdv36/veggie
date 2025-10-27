<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/index-8.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 12 Feb 2025 04:54:29 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield(section: 'title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('assets/clients/img/favicon.png') }}" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/font-icons.css') }}">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/plugins.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/responsive.css') }}">

    
</head>
<style>
    
</style>
<body>
    <div class="wrapper">
        @include('clients.partials.header')
        @hasSection('breadcrumb')
            @include('clients.partials.breadcrumb')
            
        @endif
        <main>
            @yield('content')
        </main>
        @include('clients.partials.feature')
        @include('clients.partials.footer')
    </div>

    <!-- preloader area start -->
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->

    <!-- All JS Plugins -->
   <script src="https://cdn.jsdelivr.net/npm/@flasher/flasher-notyf@1.3.1/dist/flasher-notyf.min.js"></script>

<!-- Hiển thị thông báo nếu có -->
@if (session('success'))
    <script>
        notyf.success("{{ session('success') }}");
    </script>
@endif

@if (session('error'))
    <script>
        notyf.error("{{ session('error') }}");
    </script>
@endif

    <script src="{{ asset('assets/clients/js/plugins.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>
    <script src="{{ asset('assets/clients/js/custom.js') }}"></script>

</body>

</html>