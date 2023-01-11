<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Security-Policy" content="block-all-mixed-content">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:site_name" content="{{ env('APP_NAME') }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="Author" lang="en" content="Luis Rodz">
    @yield('meta')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- STYLES --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('resources/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('resources/images/favicon-16x16.png') }}">
    <link rel="shortcut icon" sizes="512x512" href="{{ asset('resources/images/logo.svg') }}">
    <link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#0E3D5F">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ env('APP_NAME') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('resources/images/apple-touch-icon.png') }}">
    <link rel="mask-icon" href="{{ asset('resources/images/safari-pinned-tab.svg') }}" color="#0E3D5F">
    <meta name="msapplication-TileImage" content="{{ asset('resources/images/msapplication-icon-144x144.png') }}">
    <meta name="msapplication-TileColor" content="#0E3D5F">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('resources/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/owlcarousel/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/bootstrap-5.2.3-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/modalSearch.css') }}">

    <script src="{{ asset('resources/js/pwa-script.js') }}"></script>
    <script src="{{ asset('resources/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('resources/js/popper.min.js') }}"></script>
    <script src="{{ asset('resources/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('resources/owlcarousel/owl.carousel.min.js') }}"></script>
    {{-- @vite(['resources/js/ajaxSearch.js', 'resources/css/app.css', 'resources/css/modalSearch.css']) --}}
</head>

<body id="body" class="color2 hidden">
    <div id="app">
        <div class="loader-container">
            <div class="spinner"></div>
        </div>
        @include('layouts.navbar')
        <main class="py-2">
            @include('layouts.alerts')
            @yield('content')
            {{-- Modal Search --}}
            @include('layouts.modalSearch')
        </main>
        @include('layouts.footer')
    </div>
        <script src="{{ asset('resources/js/ajaxSearch.js') }}"></script>
        <script src="{{ asset('resources/js/owCarouselConfig.js') }}"></script>
</body>

</html>
