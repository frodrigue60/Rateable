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
    <meta property="fb:app_id" content="1363850827699525" />

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('resources/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('resources/images/favicon-16x16.png') }}">
    <link rel="shortcut icon" sizes="512x512" href="{{ asset('resources/images/logo.svg') }}">
    {{-- <link rel="manifest" href="manifest.json"> --}}
    {{-- <link rel="manifest" href="{{ asset('build/manifest.json') }}"> --}}
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#0E3D5F">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ env('APP_NAME') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('resources/images/apple-touch-icon.png') }}">
    <link rel="mask-icon" href="{{ asset('resources/images/safari-pinned-tab.svg') }}" color="#0E3D5F">
    <meta name="msapplication-TileImage" content="{{ asset('resources/images/msapplication-icon-144x144.png') }}">
    <meta name="msapplication-TileColor" content="#0E3D5F">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        as="style">
    <link rel="stylesheet" href="{{ asset('resources/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/owlcarousel/assets/owl.theme.default.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">


    {{-- <link rel="stylesheet" href="{{ asset('resources/bootstrap-5.2.3-dist/css/bootstrap.min.css') }}"> --}}
   {{--  <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/modalSearch.css') }}"> --}}
    @vite([/* 'resources/sass/app.scss','resources/js/app.js', */ 'resources/js/ajaxSearch.js', 'resources/css/app.css', 'resources/css/modalSearch.css','resources/css/userProfile.css'])

</head>

<body id="body" class="color2 hidden">
    <div id="app">
        <div class="loader-container">
            <div class="spinner"></div>
        </div>
        @include('layouts.navbar')
        @include('layouts.alerts')
        <main class="py-2">
            @yield('content')
            {{-- Modal Search --}}
            @include('layouts.modal-search')
        </main>

        <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"
            integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>
        {{-- <script src="{{ asset('resources/js/pwa-script.js') }}"></script> --}}
        {{-- <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js" integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script> --}}
        {{-- <script src="{{ asset('resources/js/jquery-3.6.3.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('resources/js/popper.min.js') }}"></script> --}}
        <script src="{{ asset('resources/owlcarousel/owl.carousel.min.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous" defer>
        </script>
        {{-- <script src="{{ asset('resources/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('build/assets/ajaxSearch.js') }}"></script> --}}
        <script src="{{ asset('resources/js/owCarouselConfig.js') }}" defer></script>
        @yield('script')

        @include('layouts.footer')
    </div>

</body>

</html>
