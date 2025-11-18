<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>
</head>
<body>

    {{-- Menu điều hướng admin --}}
    @include('admin.partials.nagivation')

    {{-- Nội dung chính --}}
    <div class="container">
        @yield('content')
    </div>

</body>
</html>
