<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img class="logo-navbar" src="{{ asset('resources/images/logo-2-dark.svg') }}" alt="Anirank Logo" title="Anirank Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ 'Toggle navigation' }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- LINKS NAVBAR --}}
            <ul class="navbar-nav mx-auto gap-2">
                {{--  <li><a class="nav-link {{ Request::is('openings') ? 'active' : '' }}"
                        href="{{ route('openings') }}">Openings</a></li>
                <li><a class="nav-link {{ Request::is('endings') ? 'active' : '' }}"
                        href="{{ route('endings') }}">Endings</a></li> --}}

                <li><a class="nav-link {{ Request::is('seasonal') ? 'active' : '' }}"
                        href="{{ route('seasonal') }}">Seasonal</a></li>

                <li><a class="nav-link {{ Request::is('ranking') ? 'active' : '' }}"
                        href="{{ route('ranking') }}">Top</a></li>
                {{-- <li><a class="nav-link {{ Request::is('animes') ? 'active' : '' }}"
                        href="{{ route('animes') }}">Animes</a></li>
                <li><a class="nav-link {{ Request::is('artists.index') ? 'active' : '' }}"
                        href="{{ route('artists.index') }}">Artists</a></li> --}}

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown"aria-expanded="false">Filter</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('animes') }}">Animes</a></li>
                        <li><a class="dropdown-item" href="{{ route('themes') }}">Openings & Endings</a></li>
                        <li><a class="dropdown-item" href="{{ route('artists.index') }}">Artists</a></li>
                    </ul>
                </li>
                {{-- @auth
                    <li><a class="nav-link {{ Request::is('favorites') ? 'active' : '' }}"
                            href="{{ route('favorites') }}">My Favorites</a></li>
                @endauth --}}
                {{-- @if (Auth::check() && Auth::user()->isStaff())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown"aria-expanded="false">ADMIN</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.posts.index') }}">Post index</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.artists.index') }}">Artist index</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('admin.years.index') }}">Years index</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.seasons.index') }}">Seasons index</a>
                            </li>
                            @if (Auth::User()->isAdmin())
                                <li> <a class="dropdown-item" href="{{ route('admin.users.index') }}">Users index</a>
                                </li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}">Reports index</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('admin.requests.index') }}">Requests index</a>
                            </li>
                        </ul>
                    </li>
                @endif --}}
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto gap-2">
                <li class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-primary" id="themeToggle">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                </li>
                <li class="d-flex">
                    {{-- <input id="searchInput" type="text" name="search" class="form-control" placeholder="Search..."> --}}
                    <button type="button" class="bg-transparent rounded-pill m-auto border-0 fs-5 "
                        aria-label="search" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </li>
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-user"></i>
                            Guest
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('login') }}">{{ 'Login' }}
                            </a>
                            <a class="dropdown-item" href="{{ route('register') }}">{{ 'Register' }}
                            </a>
                        </div>
                    </li>
                @endguest
                @auth
                    <!-- AUTH USER -->
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            @if ((Auth::user()->image) && (Storage::disk('public')->exists(Auth::user()->image)))
                                <img src="{{ Storage::url(Auth::user()->image) }}" alt="profile pic" height="40px"
                                    title="profile pic" class="rounded-circle">
                            @else
                                <i class="fa-solid fa-user"></i>
                            @endif
                            {{-- {{ Auth::user()->name }} --}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @if (Auth::check() && Auth::user()->isStaff())
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fa-solid fa-table-columns"></i>
                                    Dashboard
                                </a>
                            @endif
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fa-solid fa-user"></i>
                                Profile
                            </a>

                            <a class="dropdown-item" href="{{ route('favorites') }}">
                                <i class="fa-solid fa-heart"></i>
                                Favorites
                            </a>

                            <a class="dropdown-item" href="{{ route('request.create') }}">
                                <i class="fa-solid fa-pen"></i> Request</a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();localStorage.removeItem('api_token');document.getElementById('logout-form').submit();"><i
                                    class="fa fa-sign-out" aria-hidden="true"></i>
                                {{ 'Logout' }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
