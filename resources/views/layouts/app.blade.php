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

    <link rel="icon" type="image/png" href="{{ asset('logo.svg') }}">
    <link rel="shortcut icon" sizes="192x192" href="{{ asset('logo.svg') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>


    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('/resources/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/resources/owlcarousel/assets/owl.theme.default.min.css') }}">


    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('/resources/css/app.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('/resources/bootstrap-5.2.3-dist/css/bootstrap.css') }}"> --}}

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <!-- JS -->
    <script src="{{ asset('resources/js/jquery-3.6.3.js') }}"></script>
    <script src="{{ asset('/resources/owlcarousel/owl.carousel.js') }}"></script>
    {{-- <script src="{{ asset('resources/js/popper.min.js') }}"></script>
    <script src="{{ asset('resources/bootstrap-5.2.3-dist/js/bootstrap.js') }}"></script> --}}

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css', 'resources/css/modalSearch.css'])

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script> --}}


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
                        <a class="nav-link {{ Request::is('openings') ? 'active' : '' }}"
                            href="{{ route('openings') }}">Openings</a>
                        <a class="nav-link {{ Request::is('endings') ? 'active' : '' }}"
                            href="{{ route('endings') }}">Endings</a>
                        <a class="nav-link {{ Request::is('global-ranking') ? 'active' : '' }}"
                            href="{{ route('globalranking') }}">Global Ranking</a>
                        <a class="nav-link {{ Request::is('filter') ? 'active' : '' }}"
                            href="{{ route('filter') }}">Filter</a>
                        @auth
                            <a class="nav-link {{ Request::is('favorites') ? 'active' : '' }}"
                                href="{{ route('favorites') }}">My Favorites</a>
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
                        {{-- search form
                        <form class="d-flex" action="{{ route('search') }}" method="GET">
                            <input class="form-control me-2" type="text" name="search" placeholder="Search"
                                required />
                            <button class="btn btn-success" type="submit"><i class="fa fa-search"
                                    aria-hidden="true"></i></button>
                        </form> --}}
                        {{-- <form class="d-flex" role="search" action="{{ route('search') }}" method="GET">
                            <select class="btn btn-primary dropdown-toggle" name="search_type">
                                <option value="anime">Anime</option>
                                <option value="artist">Artist</option>
                                <option value="season">Season</option>
                            </select>
                            <input id="searchInput" type="text" name="search" class="form-control"
                                aria-label="search" placeholder="Search..." data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                        </form>--}}
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#" role="button"
                            data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                                <i class="fa fa-search" aria-hidden="true"></i></a>
                        </li> --}}
                        
                        <div class="d-flex">
                            <input id="searchInput" type="text" name="search" class="form-control"
                                aria-label="search" placeholder="Search..." data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                            
                        </div>

                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
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
                                <span id="catTitle">Anime</span>
                                <div id="posts">

                                </div>


                                <span id="catTitle">Artist</span>
                                <div id="artists">
                                </div>

                                <span id="catTitle">Tag</span>
                                <div id="tagsRes">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center customModal">
                            <div class="d-flex">
                                <a href="{{ route('filter') }}" type="button" class="btn btn-primary color3">More
                                    options</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                const myModal = document.getElementById('exampleModal');
                const postsDiv = document.querySelector("#posts");
                const artistsDiv = document.querySelector("#artists");
                const tagsDiv = document.querySelector("#tagsRes");
                const input = document.getElementById('searchInputModal');
                const token = document.querySelector('meta[name="csrf-token"]').content;

                let typingTimer; //timer identifier
                let doneTypingInterval = 300; //time in ms (5 seconds)

                document.addEventListener("DOMContentLoaded", function() {
                    nullValueInput();
                    myModal.addEventListener('shown.bs.modal', function() {
                        input.focus();

                        input.addEventListener('keyup', () => {
                            postsDiv.innerHTML =
                                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                            artistsDiv.innerHTML =
                                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                            tagsDiv.innerHTML =
                                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                            console.log('input: ' + input.value);

                            clearTimeout(typingTimer);
                            if (input.value.length >= 1) {
                                typingTimer = setTimeout(doneTyping, doneTypingInterval);
                            } else {
                                postsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                                    '</span></div>';
                                artistsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                                    '</span></div>';
                                tagsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                                    '</span></div>';
                            }

                        })

                        function doneTyping() {
                            try {
                                fetch('http://localhost:8000/api/posts/search?q=' + input.value, {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token,
                                    },
                                    method: "get",
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    postsDiv.innerHTML = "";
                                    artistsDiv.innerHTML = "";
                                    tagsDiv.innerHTML = "";

                                    data.posts.forEach(element => {
                                        postsDiv.innerHTML +=
                                            '<div class="result"><a href="http://127.0.0.1:8000/show/' +
                                            element.id + '/' + element.slug + '"><span>' +
                                            element
                                            .title + '</span></a></div>';
                                    });

                                    data.artists.forEach(element => {
                                        artistsDiv.innerHTML +=
                                            '<div class="result"><a href="http://127.0.0.1:8000/artist/' +
                                            element.name_slug + '"><span>' + element.name +
                                            '</span></a></div>';
                                    });

                                    data.tags.forEach(element => {
                                        tagsDiv.innerHTML +=
                                            '<div class="result"><a href="http://127.0.0.1:8000/tag/' +
                                            element.slug + '"><span>' + element.name +
                                            '</span></a></div>';
                                    });
                                });
                            } catch (error) {
                                console.log(error)
                            }
                        }
                    });
                    function nullValueInput() {
                            postsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                                '</span></div>';
                            artistsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                                '</span></div>';
                            tagsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                                '</span></div>';
                        }
                });
            </script>
        </main>
        <footer class="text-center text-lg-start py-1 mt-1 color1">
            <div class="text-center p-3 text-light">
                © 2022 Copyright:
                <a class="no-deco text-light" href="#">{{ config('app.name', 'Laravel') }}</a>
            </div>
        </footer>
    </div>
</body>

</html>
