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
                <div class="searchPanel">
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
                        <section class="searchItem">
                            <span class="text-light">Filter By</span>
                            <select id="chzn-filterBy" name="filterBy" class="form-select"
                                aria-label="Default select example">
                                <option value="">Select a filter method</option>
                                @foreach ($filters as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $requested->filterBy == $item['value'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </section>
                    @endif
                    {{-- TYPE --}}
                    <section class="searchItem">
                        <span class="text-light">Select Type</span>
                        <select id="chzn-type" name="type" class="form-select" aria-label="Default select example">
                            <option value="">Select the type</option>
                            @foreach ($types as $item)
                                <option value="{{ $item['value'] }}"
                                    {{ $requested->type == $item['value'] ? 'selected' : '' }}>
                                    {{ $item['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </section>
                    {{-- TAGS --}}
                    <section class="searchItem">
                        <span class="text-light">Select Season</span>
                        <select id="chzn-tag" name='tag' class="form-select" aria-label="Default select example">
                            <option value="">Select the season</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->name }}" {{ $requested->tag == $tag->name ? 'selected' : '' }}>
                                    {{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </section>
                    {{-- SORT --}}
                    <section class="searchItem">
                        <span class="text-light">Sort By</span>
                        <select id="chzn-sort" name="sort" class="form-select" aria-label="Default select example">
                            <option value="">Select order method</option>
                            @foreach ($sortMethods as $item)
                                <option value="{{ $item['value'] }}"
                                    {{ $requested->sort == $item['value'] ? 'selected' : '' }}>
                                    {{ $item['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </section>
                    {{-- LETTER --}}
                    <section class="searchItem">
                        <span class="text-light">Filter by Letter</span>
                        <select id="chzn-char" name="char" class="form-select" aria-label="Default select example">
                            <option value="">Select a letter</option>
                            @foreach ($characters as $item)
                                <option value="{{ $item }}" class="text-uppercase"
                                    {{ $requested->char == $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </section>
                    <br>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary w-100" type="submit">Do it</button>
                    </div>
                    </form>
                </div>
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

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">
    <script type="text/javascript">
        $(document).ready(function() {
            $("#chzn-filterBy").chosen();
            $("#chzn-type").chosen();
            $("#chzn-tag").chosen();
            $("#chzn-sort").chosen();
            $("#chzn-char").chosen();
        });
    </script>
@endsection
