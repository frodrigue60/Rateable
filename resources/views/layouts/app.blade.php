<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="{{ asset('support.png') }}">
    {{-- <link rel="shortcut icon" sizes="192x192" href="{{ asset('support.png') }}"> --}}

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])

</head>

<body class="bg-dark">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('support.png') }}" alt="Bootstrap" width="25" height="25">
                    {{ config('app.name', 'Laravel') }} - {{ str_replace('_', '-', app()->getLocale()) }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <a class="nav-link active" href="{{ route('endings') }}">Endings</a>

                        @auth
                            <a class="nav-link active" href="{{ route('favorites') }}">My Favorites</a>
                            <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
                            @if (Auth::user()->type == 'admin')
                                <a class="nav-link active" aria-current="page" href="{{ route('admin.post.index') }}">Post
                                    index</a>
                                <a class="nav-link active" aria-current="page" href="{{ route('admin.tags.index') }}">Tags
                                    index</a>

                                <a class="nav-link active" href="{{ route('admin.season.index') }}">Current Season</a>
                            @endif

                        @endauth



                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        {{-- search form --}}
                        <form class="d-flex" action="{{ route('search') }}" method="GET">
                            <input class="form-control me-2" type="text" name="search" placeholder="Search"
                                required />
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>


                        <!-- Authentication Links -->
                        @guest
                        {{--  
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif 
                        --}}
                            <li class="nav-item dropdown">
                                
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    GUEST
                                </a>
                                <ul class="dropdown-menu">
                                    @if (Route::has('login'))
                                        <li class="dropdown-item">
                                            <a class="nav-link text-dark" href="{{ route('login') }}">{{ __('Login') }}</a>
                                        </li>
                                    @endif

                                    @if (Route::has('register'))
                                        <li class="dropdown-item">
                                            <a class="nav-link text-dark" href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @else
                            <!-- AUTH USER -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <footer class="bg-dark text-center text-lg-start shadow-lg">
        <div class="text-center p-3 bg-dark text-light">
            © 2022 Copyright:
            <a class="no-deco " href="#">{{ config('app.name', 'Laravel') }}</a>
        </div>
    </footer>
</body>

</html>
