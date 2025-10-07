<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <!-- ✅ Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/toastr.min.css') }}">


    {{-- Import custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/clients/css/custom.css') }}">
    <script src="{{ asset('assets/clients/js/jquery.min.js') }}"></script>
    <!-- ✅ Toastr CSS -->
    <script src="{{ asset('assets/clients/js/toastr.min.js') }}"></script>
</head>

<body>
    <div class="body-wrapper">
        @include('clients.partials.header_home')
        <main>
            @yield('content')
        </main>
        @include('clients.partials.footer_home')
    </div>

    <!-- preloader area start -->
    <script src="{{ asset('assets/clients/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/toastr.min.js') }}"></script>

    <!-- ✅ Script đổi mật khẩu -->
    <script src="{{ asset('assets/clients/js/custom.js') }}"></script>
    <!-- preloader area end -->

</body>

</html>
