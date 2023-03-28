<nav class="navbar navbar-expand-lg navbar-dark color1">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('resources/images/logo.png') }}" alt="Logo" title="Anirank Logo" width="157"
                height="25">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                        href="{{ route('global.ranking') }}">Ranking</a></li>
                <li><a class="nav-link {{ Request::is('filter') ? 'active' : '' }}"
                        href="{{ route('filter') }}">Filter</a></li>
                @auth
                    <li><a class="nav-link {{ Request::is('favorites') ? 'active' : '' }}"
                            href="{{ route('favorites') }}">My Favorites</a></li>
                @endauth
                @if (Auth::check() && Auth::user()->isStaff())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown"aria-expanded="false">ADMIN</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.post.index') }}">Post index</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.tags.index') }}">Tags index</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.artist.index') }}">Artist index</a></li>
                            @if (Auth::User()->isAdmin())
                                <li> <a class="dropdown-item" href="{{ route('admin.users.index') }}">Users index</a>
                                </li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('admin.report.index') }}">Reports index</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('admin.request.index') }}">Requests index</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <li><input id="searchInput" type="text" name="search" class="form-control" aria-label="search"
                        placeholder="Search..." data-bs-toggle="modal" data-bs-target="#exampleModal"></li>
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
                                <img src="{{ asset('/storage/profile/' . Auth::user()->image) }}" alt="profile pic"
                                    width="25" height="25" title="profile pic">
                            @else
                                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                            @endif
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile') }}"><i class="fa fa-user-circle-o"
                                    aria-hidden="true"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="{{ route('request.create') }}"><i class="fa fa-comment-o"
                                    aria-hidden="true"></i> Create Request</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                                    class="fa fa-sign-out" aria-hidden="true"></i>
                                {{ __('Logout') }}
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
