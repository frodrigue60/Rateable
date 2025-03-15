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
    <link rel="shortcut icon" sizes="512x512" href="{{ asset('resources/images/logo3.svg') }}">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#0E3D5F">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ env('APP_NAME') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('resources/images/apple-touch-icon.png') }}">
    <link rel="mask-icon" href="{{ asset('resources/images/safari-pinned-tab.svg') }}" color="#0E3D5F">
    <meta name="msapplication-TileImage" content="{{ asset('resources/images/msapplication-icon-144x144.png') }}">
    <meta name="msapplication-TileColor" content="#0E3D5F">


    <link rel="stylesheet" href="{{ asset('resources/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/owlcarousel/assets/owl.theme.default.min.css') }}">

    @if (config('app.env') === 'local')
        <!-- DEV ASSETS -->
        <link rel="stylesheet" href="{{ asset('resources/bootstrap-5.2.3-dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('resources/font-awesome-6.4.2/css/all.min.css') }}">
        @vite([
            /* 'resources/sass/app.scss',
             'resources/js/app.js', */
            'resources/js/ajaxSearch.js',
            'resources/css/app.css',
            'resources/css/modalSearch.css',
            'resources/css/userProfile.css',
            'resources/css/post.css',
            'resources/css/ranking.css',
            'resources/css/fivestars.css',
        ])
    @else
        <!-- PROD ASSETS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
            integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        {{-- BUILD --}}
        <link rel="stylesheet" href="{{ asset('build/userProfile.css') }}">
        <link rel="stylesheet" href="{{ asset('build/post.css') }}">
        <link rel="stylesheet" href="{{ asset('build/ranking.css') }}">
        <link rel="stylesheet" href="{{ asset('build/fivestars.css') }}">
        <link rel="stylesheet" href="{{ asset('build/modalSearch.css') }}">
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body id="body" class="color2" hidden>
    <div id="app">
        <div class="loader-container">
            <div class="my-spinner"></div>
        </div>
        @include('layouts.navbar')

        <main class="py-2">
            @include('layouts.breadcrumb')
            @include('layouts.alerts')
            @yield('content')
            {{-- Modal Search --}}
            @include('layouts.modal-search')
        </main>

        @if (Request::routeIs('/'))
            <script src="{{ asset('resources/owlcarousel/owl.carousel.min.js') }}" defer></script>
            <script src="{{ asset('resources/js/owCarouselConfig.js') }}" defer></script>
        @endif

        @if (config('app.env') === 'local')
            {{-- DEV SCRIPTS --}}
            @if (Request::routeIs('/'))
                <script src="{{ asset('resources/js/jquery-3.6.3.slim.min.js') }}"></script>
            @endif

            <script src="{{ asset('resources/js/popper.min.js') }}"></script>
            <script src="{{ asset('resources/font-awesome-6.4.2/js/all.min.js') }}"></script>
            <script src="{{ asset('resources/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js') }}"></script>
        @else
            @if (Request::routeIs('/'))
                <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"
                    integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>
            @endif

            {{-- PROD SCRIPTS --}}
            {{-- <script src="{{ asset('resources/js/pwa-script.js') }}"></script> --}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"
                integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous" defer>
            </script>

            {{-- BUILD --}}
            <script src="{{ asset('build/ajaxSearch.js') }}"></script>
        @endif

        @yield('script')

        @include('layouts.footer')
    </div>

</body>

</html>
