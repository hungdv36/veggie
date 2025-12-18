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

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/clients/img/favicon.png') }}" type="image/x-icon" />

    <!-- CSS chính -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/chat.css') }}">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <!-- jQuery (bắt buộc cho Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

<style>
    #chatbot-icon {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #007bff;
        color: white;
        font-size: 22px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        z-index: 9999;
    }

    #chatbot-icon:hover {
        background-color: #0056b3;
    }
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

    <!-- Chatbot -->
    <div id="chatbot-icon">💬</div>
    @include('clients.partials.chat_ai')

    <script>
        window.chatConfig = {
            userId: {{ Auth::check() ? Auth::id() : 'null' }},
            userName: "{{ Auth::check() ? Auth::user()->name : '' }}",
            sendUrl: "{{ route('chat.send') }}",
            historyUrl: "{{ route('chat.history') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

    <script src="{{ asset('assets/clients/js/chat.js') }}"></script>

    <!-- Preloader -->
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>

    <!-- JS Plugins -->
    <script src="{{ asset('assets/clients/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>
    <script src="{{ asset('assets/clients/js/custom.js') }}"></script>

    <!-- Toastr hiển thị thông báo -->
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    @stack('scripts')

</body>
</html>
