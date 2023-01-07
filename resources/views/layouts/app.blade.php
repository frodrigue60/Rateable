<!DOCTYPE html>
<html lang="en">

<head>
    {{-- <title>Anirank</title> --}}
    <link rel="canonical" href="https://anirank.ddns.net">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- comment <meta title="Search, play, and rate the openings and endings of your favorite animes."> --}}
    <meta name="description" content="The site you were looking for to rate openings and endings of your favorite animes.">
    <meta name="keywords" content="anime, openings, endings, ranking, rating">
    <meta name="robots" content="index, follow">
    <meta name="Author" lang="en" content="Luis Rodz">
    @yield('meta')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('logo.svg') }}">
    <link rel="shortcut icon" sizes="192x192" href="{{ asset('logo.svg') }}">

    <!-- CSS -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    {{-- <link rel="stylesheet" href="{{ asset('/resources/bootstrap-5.2.3-dist/css/bootstrap.min.css') }}"> --}}
    <link rel="preload" href="{{ asset('/resources/owlcarousel/assets/owl.carousel.min.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('/resources/owlcarousel/assets/owl.theme.default.min.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    {{-- <link rel="stylesheet" href="{{ asset('/resources/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/resources/css/modalSearch.css') }}"> --}}

    <!-- JS -->
    <script src="{{ asset('/resources/js/jquery-3.6.3.min.js') }}"></script>
    {{-- <script src="{{ asset('/resources/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('resources/js/popper.min.js') }}"></script> --}}
    <script src="{{ asset('/resources/owlcarousel/owl.carousel.min.js') }}"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/ajaxSearch.js', 'resources/css/app.css', 'resources/css/modalSearch.css'])
</head>

<body class="color2">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark color1">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('text4491.png') }}" alt="Logo" width="157" height="25">
                    {{-- {{ config('app.name', 'Laravel') }}    - {{ str_replace('_', '-', app()->getLocale()) }} --}}
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
    <script src="{{ asset('/resources/js/ajaxSearch.js') }}"></script>
</body>

</html>
