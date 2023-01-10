<!DOCTYPE html>
<html lang="en">

<head>
    {{-- <meta http-equiv="Content-Security-Policy" content="block-all-mixed-content"> --}}
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
    <link rel="manifest" href="/manifest.json">
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
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('/resources/bootstrap-5.2.3-dist/css/bootstrap.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/resources/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/resources/owlcarousel/assets/owl.theme.default.min.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('/build/assets/modalSearch.00eba843.css') }}">
    <link rel="stylesheet" href="{{ asset('/build/assets/app.243c9c4b.css') }}"> --}}

    <!-- JS -->
    <script src="{{ asset('/resources/js/pwa-script.js') }}"></script>
    <script src="{{ asset('/resources/js/jquery-3.6.3.min.js') }}"></script>
    {{-- <script src="{{ asset('/resources/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/resources/js/popper.min.js') }}"></script> --}}
    <script src="{{ asset('/resources/owlcarousel/owl.carousel.min.js') }}"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/ajaxSearch.js', 'resources/css/app.css', 'resources/css/modalSearch.css'])

</head>

<body class="color2">
    <div id="app">
        <div class="loader-container">
            <div class="spinner"></div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark color1">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('resources/images/logo.png') }}" alt="Logo" width="157" height="25">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li><a class="nav-link {{ Request::is('openings') ? 'active' : '' }}"
                                href="{{ route('openings') }}">Openings</a></li>
                        <li><a class="nav-link {{ Request::is('endings') ? 'active' : '' }}"
                                href="{{ route('endings') }}">Endings</a></li>
                        <li><a class="nav-link {{ Request::is('global-ranking') ? 'active' : '' }}"
                                href="{{ route('globalranking') }}">Global Ranking</a></li>
                        <li><a class="nav-link {{ Request::is('filter') ? 'active' : '' }}"
                                href="{{ route('filter') }}">Filter</a></li>
                        @auth
                            <li><a class="nav-link {{ Request::is('favorites') ? 'active' : '' }}"
                                    href="{{ route('favorites') }}">My Favorites</a></li>
                            @if (Auth::user()->type == 'admin')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        ADMIN
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.post.index') }}">Post
                                                index</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('admin.tags.index') }}">Tags
                                                index</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.artist.index') }}">Artist
                                                index</a></li>
                                        <li> <a class="dropdown-item" href="{{ route('admin.season.index') }}">Current
                                                Season</a></li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <input id="searchInput" type="text" name="search" class="form-control"
                            aria-label="search" placeholder="Search..." data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                    Guest
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('login') }}">{{ __('Login') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('register') }}">{{ __('Register') }}
                                    </a>
                                </div>
                            </li>
                        @endguest
                        @auth
                            <!-- AUTH USER -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (Auth::user()->image)
                                        <img src="{{ asset('/storage/profile/' . Auth::user()->image) }}"
                                            alt="profile pic" width="25" height="25">
                                    @else
                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                    @endif
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}"><i class="fa fa-user-circle-o"
                                            aria-hidden="true"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                                            class="fa fa-sign-out" aria-hidden="true"></i>
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @include('layouts.alerts')
            @yield('content')
            {{-- Modal Search --}}
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content customModal">
                        <div class="modal-header mt-2 customModal">
                            <form class="d-flex w-100" role="search">
                                <input id="searchInputModal" class="form-control" type="search"
                                    placeholder="Search" aria-label="Search" autofocus>
                            </form>
                        </div>
                        <div id="modalBody" class="modal-body p-2 customModal">
                            <div class="res">
                                <span class="catTitle">Anime</span>
                                <div id="posts">
                                </div>
                                <span class="catTitle">Artist</span>
                                <div id="artists">
                                </div>
                                <span class="catTitle">Tag</span>
                                <div id="tags">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center customModal">
                            <div class="d-flex">
                                <a href="{{ route('filter') }}" class="btn btn-primary color3">More
                                    options</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="text-center text-lg-start py-1 mt-1 color1">
            <div class="text-center p-3 text-light">
                © 2022 Copyright:
                <a class="no-deco text-light" href="#">{{ config('app.name', 'Laravel') }}</a>
            </div>
        </footer>
    </div>
    <script src="{{ asset('/build/assets/ajaxSearch.2bf24ff5.js') }}"></script>
</body>

</html>
