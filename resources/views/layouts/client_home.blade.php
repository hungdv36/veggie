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

    <!-- CSS -->
    <link rel="shortcut icon" href="{{ asset('assets/clients/img/favicon.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/clients/css/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/custom.css') }}">

    <!-- Toastr CSS (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>

<body>
    <div class="body-wrapper">
        @include('clients.partials.header_home')
        <main>@yield('content')</main>
        @include('clients.partials.footer_home')
    </div>

    <!-- SCRIPTS: jQuery -> Plugins -> Toastr -> main/custom -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('assets/clients/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- <script src="{{ asset('assets/clients/js/custom.js') }}"></script> --}}

    <!-- Render server flash messages via Toastr -->
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

    <!-- CSRF setup for AJAX -->
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    </script>

    <!-- allow child views to push scripts -->
    @stack('scripts')


    <!-- Chatbot -->
    <div id="chatbot-icon">💬</div>
    @include('clients.partials.chat_ai')
    <script>
        window.chatConfig = {
            sendUrl: "{{ route('chat.send') }}",
            historyUrl: "{{ route('chat.history') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ asset('assets/clients/js/chat.js') }}"></script>
</body>
</html>
