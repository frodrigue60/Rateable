@extends('layouts.app')
@section('meta')
    @if (Request::routeIs('themes'))
        <title>Search Openings & Endings</title>
        <meta title="Search Openings & Endings">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="description" content="Search Openings & Endings by type, season, order as you want, and filter by letter">
        <meta name="robots" content="index, follow, max-image-preview:standard">
    @endif
    @if (Request::routeIs('user.list'))
        <title>{{ $user->name }} - list</title>
        <meta title="{{ $user->name }} - Themes list">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="description" content="Openings & Endings from {{ $user->name }}">
        <meta name="robots" content="index, follow, max-image-preview:standard">
    @endif
@endsection

@section('content')
    @if (Request::routeIs('user.list') || Request::routeIs('favorites'))
        @include('layouts.userBanner')
    @endif
    <div class="container text-light">
        <div id="log">

        </div>
        @if (Request::routeIs('themes'))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light mb-0">Filter Themes</h2>
            </div>
        @endif
        @if (Request::routeIs('favorites'))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light mb-0">My Favorites</h2>
            </div>
        @endif
        <div class="contenedor-filtro">
            {{-- SEARCH PANEL --}}
            <aside>
                <section class="searchPanel">
                    @if (Request::routeIs('favorites'))
                        <form id="myForm" action="{{ route('favorites') }}" method="get">
                    @endif
                    @if (Request::routeIs('themes'))
                        <form id="myForm" action="{{ route('themes') }}" method="get">
                    @endif
                    @if (Request::routeIs('user.list'))
                        <form id="myForm" action="{{ route('user.list', $user->id) }}" method="get">
                    @endif
                    @if (Request::routeIs('favorites') || Request::routeIs('user.list'))
                        {{-- FILTER BY --}}
                        <div class="searchItem">
                            <label for="select-filterBy" class="form-label text-light">Filter By</label>
                            <select class="form-select" aria-label="Default select example" id="select-filterBy"
                                name="filterBy">
                                <option value="">Select a filter method</option>
                                @foreach ($filters as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $requested->filterBy == $item['value'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- TYPE --}}
                    <div class="searchItem">
                        <label for="select-type" class="form-label text-light">Select type</label>
                        <select class="form-select" aria-label="Default select example" id="select-type" name="type">
                            <option value="">Select a theme type</option>
                            @foreach ($types as $item)
                                <option value="{{ $item['value'] }}"
                                    {{ $requested->type == $item['value'] ? 'selected' : '' }}>
                                    {{ $item['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- TAGS --}}
                    <div class="searchItem">
                        <div class="w-100 mb-1">
                            <label class="text-light" for="select-year">Year:</label>
                            <select class="form-select" aria-label="Default select example" name="year" id="select-year">
                                <option selected value="">Select a year</option>
                                @foreach ($years as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $requested->year == $item['value'] ? 'selected' : '' }}>{{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100 mb-1">
                            <label class="text-light" for="select-season">Season:</label>
                            <select class="form-select" aria-label="Default select example" name="season"
                                id="select-season">
                                <option selected value="">Select a season</option>
                                @foreach ($seasons as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $requested->season == $item['value'] ? 'selected' : '' }}>{{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- SORT --}}
                    <div class="searchItem">
                        <label for="select-sort" class="form-label text-light">Select order method</label>
                        <select class="form-select" aria-label="Default select example" id="select-sort" name="sort">
                            <option value="">Select a sort method</option>
                            @foreach ($sortMethods as $item)
                                <option value="{{ $item['value'] }}"
                                    {{ $requested->sort == $item['value'] ? 'selected' : '' }}>
                                    {{ $item['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- LETTER --}}
                    <div class="searchItem">
                        <label for="select-char" class="form-label text-light">Select a character</label>
                        <select class="form-select" aria-label="Default select example" id="select-char" name="char">
                            <option value="">Select a character</option>
                            @foreach ($characters as $item)
                                <option value="{{ $item }}" class="text-uppercase"
                                    {{ $requested->char == $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary w-100" type="submit">Do it</button>
                    </div>
                    </form>
                </section>
            </aside>
            {{-- POSTS --}}
            <section class="text-light">
                <div class="contenedor-tarjetas-filtro" id="data">
                    {{--  @include('public.songs.songs-cards') --}}
                </div>
                {{-- PAGINATOR --}}
                {{-- <div style="display: flex;justify-content: center;
                margin-top: 10px;">
                    {{ $songs->links() }}
                </div> --}}
            </section>
        </div>
    </div>


@endsection
@section('script')
    {{-- FAVORITES --}}
    @if (Request::routeIs('favorites'))
        {{-- INFINITE SCROLL --}}
        <script>
            let currentUrl = window.location.href;
            let pageName = undefined;
            let page = 1;
            let lastPage = undefined;

            document.body.onload = function() {
                let urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('filterBy') || urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
                    urlParams.has('char')) {
                    pageName = "&page=";
                } else {
                    pageName = "?page=";
                }

                url = currentUrl + pageName + page;
                //console.log("fetch to: " + url);

                fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            lastPage = 0;
                            //console.log(response.status);
                            return;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data.html === "") {
                            lastPage = 0;
                            //console.log("No data from backend");
                            return;
                        } else {
                            //console.log(data);
                            lastPage = data.lastPage;
                            document.querySelector("#data").innerHTML += data.html;

                            let titles = document.querySelectorAll('.post-titles');

                            function cutTitles() {
                                titles.forEach(title => {
                                    if (title.textContent.length > 25) {
                                        title.textContent = title.textContent.substr(0, 25) + "...";
                                    }
                                });
                            }
                            cutTitles();
                        }
                    })
                    .catch(error => console.error(error));
            }

            window.addEventListener("scroll", function() {
                if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight) {

                    if (lastPage == undefined) {
                        page++;
                        loadMoreData(page);
                    } else {
                        if (page <= lastPage) {
                            page++;
                            loadMoreData(page);
                        }
                    }
                }
            });

            function loadMoreData(page) {
                let urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('filterBy') || urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
                    urlParams.has('char')) {
                    pageName = "&page=";
                } else {
                    pageName = "?page=";
                }

                url = currentUrl + pageName + page;
                console.log("fetch to: " + url);

                fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            lastPage = 0;
                            //console.log(response.status);
                            return;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data.html === "") {
                            lastPage = 0;
                            //console.log("No data from backend");
                            return;
                        } else {
                            //console.log(data);
                            lastPage = data.lastPage;
                            document.querySelector("#data").innerHTML += data.html;

                            let titles = document.querySelectorAll('.post-titles');

                            function cutTitles() {
                                titles.forEach(title => {
                                    if (title.textContent.length > 25) {
                                        title.textContent = title.textContent.substr(0, 25) + "...";
                                    }
                                });
                            }
                            cutTitles();
                        }
                    })
                    .catch(error => console.error(error));
            }
        </script>
    @endif
    {{-- USER LIST --}}
    @if (Request::routeIs('user.list'))
        <script>
            let currentUrl = window.location.href;
            let pageName = undefined;
            let page = 1;
            let lastPage = undefined;

            document.body.onload = function() {
                let urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('filterBy') || urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
                    urlParams.has('char')) {
                    pageName = "&page=";
                } else {
                    pageName = "?page=";
                }

                url = currentUrl + pageName + page;
                //console.log("fetch to: " + url);

                fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            lastPage = 0;
                            //console.log(response.status);
                            return;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data.html === "") {
                            lastPage = 0;
                            //console.log("No data from backend");
                            return;
                        } else {
                            //console.log(data);
                            lastPage = data.lastPage;
                            document.querySelector("#data").innerHTML += data.html;

                            let titles = document.querySelectorAll('.post-titles');

                            function cutTitles() {
                                titles.forEach(title => {
                                    if (title.textContent.length > 25) {
                                        title.textContent = title.textContent.substr(0, 25) + "...";
                                    }
                                });
                            }
                            cutTitles();
                        }
                    })
                    .catch(error => console.error(error));
            }

            window.addEventListener("scroll", function() {
                if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight) {

                    if (lastPage == undefined) {
                        page++;
                        loadMoreData(page);
                    } else {
                        if (page <= lastPage) {
                            page++;
                            loadMoreData(page);
                        }
                    }
                }
            });

            function loadMoreData(page) {
                let urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('filterBy') || urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
                    urlParams.has('char')) {
                    pageName = "&page=";
                } else {
                    pageName = "?page=";
                }

                url = currentUrl + pageName + page;
                console.log("fetch to: " + url);

                fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            lastPage = 0;
                            //console.log(response.status);
                            return;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data.html === "") {
                            lastPage = 0;
                            //console.log("No data from backend");
                            return;
                        } else {
                            //console.log(data);
                            lastPage = data.lastPage;
                            document.querySelector("#data").innerHTML += data.html;
                        }
                    })
                    .catch(error => console.error(error));
            }
        </script>
    @endif
    {{-- THEMES --}}
    @if (Request::routeIs('themes'))
        {{-- INFINITE SCROLL --}}
        <script>
            let currentUrl = window.location.href;
            let pageName = undefined;
            let page = 1;
            let lastPage = undefined;

            document.body.onload = function() {
                let urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
                    urlParams.has('char')) {
                    pageName = "&page=";
                } else {
                    pageName = "?page=";
                }

                url = currentUrl + pageName + page;
                //console.log("fetch to: " + url);

                fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            lastPage = 0;
                            //console.log("response status: "+response.status);
                            return;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data.html === "") {
                            lastPage = 0;
                            //console.log("No data from backend");
                            return;
                        } else {
                            //console.log("data html: "+data);
                            lastPage = data.lastPage;
                            document.querySelector("#data").innerHTML += data.html;

                            let titles = document.querySelectorAll('.post-titles');

                            function cutTitles() {
                                titles.forEach(title => {
                                    if (title.textContent.length > 25) {
                                        title.textContent = title.textContent.substr(0, 25) + "...";
                                    }
                                });
                            }
                            cutTitles();
                        }
                    })
                    .catch(error => console.error(error));
            }

            window.addEventListener("scroll", function() {
                if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight) {

                    if (lastPage == undefined) {
                        page++;
                        loadMoreData(page);
                    } else {
                        if (page <= lastPage) {
                            page++;
                            loadMoreData(page);
                        }
                    }
                }
            });

            function loadMoreData(page) {
                let urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('filterBy') || urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
                    urlParams.has('char')) {
                    pageName = "&page=";
                } else {
                    pageName = "?page=";
                }

                url = currentUrl + pageName + page;
                console.log("fetch to: " + url);

                fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            lastPage = 0;
                            //console.log(response.status);
                            return;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data.html === "") {
                            lastPage = 0;
                            //console.log("No data from backend");
                            return;
                        } else {
                            //console.log(data);
                            lastPage = data.lastPage;
                            document.querySelector("#data").innerHTML += data.html;

                            let titles = document.querySelectorAll('.post-titles');

                            function cutTitles() {
                                titles.forEach(title => {
                                    if (title.textContent.length > 25) {
                                        title.textContent = title.textContent.substr(0, 25) + "...";
                                    }
                                });
                            }
                            cutTitles();
                        }
                    })
                    .catch(error => console.error(error));
            }
        </script>
    @endif
@endsection
