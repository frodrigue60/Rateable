<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Anirank</title>
    <!-- -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- comment <meta title="Search, play, and rate the openings and endings of your favorite animes."> --}}
    <meta name="description"
        content="The site you were looking for to rate openings and endings of your favorite animes.">
    <meta name="keywords" content="anime, openings, endings, ranking, rating" />
    <meta name="robots" content="index, nofollow" />
    <meta name="Author" lang="es" content="Luis Rodz" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('support.png') }}">
    <link rel="shortcut icon" sizes="192x192" href="{{ asset('support.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{-- comment <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.css"> --}}

    <!-- JS -->
    {{-- comment <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.js"></script> --}}

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
</head>

<body style="background-color: #08263b;">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0e3d5f;">
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
                        <a class="nav-link active" href="{{ route('/') }}">Openings</a>
                        <a class="nav-link active" href="{{ route('endings') }}">Endings</a>

                        @auth
                            <a class="nav-link active" href="{{ route('favorites') }}">My Favorites</a>
                            @if (Auth::user()->type == 'admin')
                                <a class="nav-link active" aria-current="page" href="{{ route('admin.post.index') }}">Post
                                    index</a>
                                <a class="nav-link active" aria-current="page" href="{{ route('admin.tags.index') }}">Tags
                                    index</a>
                                <a class="nav-link active" aria-current="page"
                                    href="{{ route('admin.artist.index') }}">Artist
                                    index</a>
                                <a class="nav-link active" href="{{ route('admin.season.index') }}">Current Season</a>
                            @endif
                        @endauth



                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        {{--search form
                        <form class="d-flex" action="{{ route('search') }}" method="GET">
                            <input class="form-control me-2" type="text" name="search" placeholder="Search"
                                required />
                            <button class="btn btn-success" type="submit"><i class="fa fa-search"
                                    aria-hidden="true"></i></button>
                        </form>--}} 
                        <form class="nav-item d-flex" action="{{ route('search') }}" method="GET">
                            <div class="input-group mb-3">
                                <select class="btn btn-primary dropdown-toggle" name="search_type">
                                    <option value="op_ed">OP & ED</option>
                                    <option value="op">Only Openings</option>
                                    <option value="ed">Only Endings</option>
                                    <option value="artist">By Artist</option>
                                </select>
                                <input type="text" name="search" class="form-control"
                                    aria-label="Text input with dropdown button" placeholder="Type an anime title">

                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                        

                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"><i class="fa fa-user-circle-o" aria-hidden="true"></i>

                                </a>

                                <ul class="dropdown-menu">
                                    @if (Route::has('login'))
                                        <li class="dropdown-item">
                                            <a class="nav-link text-dark"
                                                href="{{ route('login') }}">{{ __('Login') }}</a>
                                        </li>
                                    @endif

                                    @if (Route::has('register'))
                                        <li class="dropdown-item">
                                            <a class="nav-link text-dark"
                                                href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endguest
                        @auth
                            <!-- AUTH USER -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
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
        </main>
        <footer class="text-center text-lg-start" style="background-color: #0e3d5f;">
            <div class="text-center p-3 text-light">
                © 2022 Copyright:
                <a class="no-deco text-light" href="#">{{ config('app.name', 'Laravel') }}</a>
            </div>
        </footer>
    </div>

</body>

</html>
