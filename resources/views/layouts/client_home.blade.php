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
</head>

<style>
    .category-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: flex-start;
        height: auto;
    }

    .category-item {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
        width: 180px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .category-item:hover {
        transform: translateY(-5px);
    }

    .category-item-img img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .category-item-name h5 {
        margin: 5px 0;
        font-size: 16px;
    }

    .category-item-name h6 {
        margin: 0;
        font-size: 14px;
        color: #555;
    }

    /* Nút biến thể */
    .variant-btn {
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        margin: 3px;
        transition: all 0.2s ease;
    }

    .variant-btn:hover {
        border-color: #ff5722;
        background-color: #fff3e0;
        transform: translateY(-2px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .variant-btn.selected {
        border-color: #ff5722;
        background-color: #ffe0b2;
        box-shadow: 0 0 4px rgba(255, 87, 34, 0.5);
    }
</style>

<body>
    <div class="body-wrapper">
        @include('clients.partials.header_home')
        <main>
            @yield('content')
        </main>
        @include('clients.partials.footer_home')
    </div>

    <!-- ✅ Scripts -->
    <script src="{{ asset('assets/clients/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>
    <script src="{{ asset('assets/clients/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
        });
    </script>
</body>

</html>
