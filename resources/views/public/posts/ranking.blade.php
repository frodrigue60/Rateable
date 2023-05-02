@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    @if (Request::routeIs('seasonal.ranking'))
        @isset($currentSeason)
            <title>Ranking Best Openings & Endings {{ $currentSeason->name }}</title>
            <meta title="Ranking Openings & Endings {{ $currentSeason->name }}">
            <meta name="description" content="Ranking Best Openings & Endings {{ $currentSeason->name }}">
            <meta name="keywords"
                content="ranking, top, anime openings {{ $currentSeason->name }}, openings anime {{ $currentSeason->name }}, anime endings {{ $currentSeason->name }}, endings anime {{ $currentSeason->name }}, of {{ $currentSeason->name }}">

            <meta property="og:type" content="website" />
            {{-- <meta property="og:image" content="{{ asset('resources/images/og-image-wide.png') }}">
            <meta property="og:image:secure_url" content="{{ asset('resources/images/og-image-wide.png') }}">
            <meta property="og:image:type" content="image/png">
            <meta property="og:image:width" content="828">
            <meta property="og:image:height" content="450">
            <meta property="og:image:alt" content="Anirank banner" /> --}}
            <meta property="og:url" content="{{ url()->current() }}" />

            <meta name="twitter:card" content="summary" />
            <meta name="twitter:site" content="@frodrigue60" />
            <meta name="twitter:creator" content="@frodrigue60" />
            <meta property="og:title" content="Ranking Openings & Endings {{ $currentSeason->name }}" />
            <meta property="og:description" content="Ranking Best Openings & Endings {{ $currentSeason->name }}" />
        @endisset
    @else
        @if (Request::routeIs('global.ranking'))
            <title>Ranking Openings & Endings of All Time</title>
            <meta title="Ranking Openings & Endings of All Time">
            <meta name="description" content="Ranking Best Openings & Endings of All Time">
            <meta name="keywords"
                content="ranking, top, anime openings, openings anime, anime endings, endings anime, of all time">

            <meta property="og:type" content="website" />
            {{-- <meta property="og:image" content="{{ asset('resources/images/og-image-wide.png') }}">
                <meta property="og:image:secure_url" content="{{ asset('resources/images/og-image-wide.png') }}">
                <meta property="og:image:type" content="image/png">
                <meta property="og:image:width" content="828">
                <meta property="og:image:height" content="450">
                <meta property="og:image:alt" content="Anirank banner" /> --}}
            <meta property="og:url" content="{{ url()->current() }}" />

            <meta name="twitter:card" content="summary" />
            <meta name="twitter:site" content="@frodrigue60" />
            <meta name="twitter:creator" content="@frodrigue60" />
            <meta property="og:title" content="Ranking Openings & Endings of All Time" />
            <meta property="og:description" content="Ranking Best Openings & Endings of All Time" />
        @endif
    @endif
@endsection
@section('content')
    <div class="container">
        @if (Request::routeIs('global.ranking'))
            <h1 class="text-center text-light">Ranking Openings & Endings Of All Time</h1>
        @else
            @if (Request::routeIs('seasonal.ranking') && isset($currentSeason))
                <h1 class="text-center text-light">Ranking Openings & Endings {{ $currentSeason->name }}</h1>
            @endif
        @endif
        <div class="container-top">
            <section class="container-items" id="openings">
                @if (Request::routeIs('/') || Request::routeIs('global.ranking'))
                    <h2 hidden class="text-light">Best Anime Openings of All Time</h2>
                @else
                    @if (Request::routeIs('seasonal.ranking') && isset($currentSeason))
                        <h2 hidden>Top Anime Openings {{ $currentSeason->name }}</h2>
                    @endif
                @endif
                <div class="top-header-ranking">
                    <div>
                        <span>Top Openings</span>
                    </div>
                    <div>
                        @if (Request::routeIs('/'))

                            <a href="{{ route('global.ranking') }}" class="btn btn-sm color4">Ranking</a>
                        @else
                            @if (Request::routeIs('global.ranking'))
                                <a href="{{ route('seasonal.ranking') }}" class="btn btn-sm color4">Seasonal Ranking</a>
                            @else
                                @if (Request::routeIs('seasonal.ranking'))
                                    <a href="{{ route('global.ranking') }}" class="btn btn-sm color4">Global Ranking</a>
                                @endif
                            @endif
                        @endif
                    </div>


                </div>
                @include('layouts.top.openings')
            </section>
            {{-- ENDINGS --}}
            <section class="container-items">
                @if (Request::routeIs('/') || Request::routeIs('global.ranking'))
                    <h2 hidden class="text-light">Best Anime Endings of All Time</h2>
                @else
                    @if (Request::routeIs('seasonal.ranking') && isset($currentSeason))
                        <h2 hidden>Top Anime Endings {{ $currentSeason->name }}</h2>
                    @endif
                @endif
                <div class="top-header-ranking">
                    <div>
                        <span>Top Endings</span>
                    </div>
                    <div>
                        @if (Request::routeIs('/'))

                            <a href="{{ route('global.ranking') }}" class="btn btn-sm color4">Ranking</a>
                        @else
                            @if (Request::routeIs('global.ranking'))
                                <a href="{{ route('seasonal.ranking') }}" class="btn btn-sm color4">Seasonal Ranking</a>
                            @else
                                @if (Request::routeIs('seasonal.ranking'))
                                    <a href="{{ route('global.ranking') }}" class="btn btn-sm color4">Global Ranking</a>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
                @include('layouts.top.endings')
            </section>
        </div>

    </div>

@endsection
{{-- @section('script')
    @if (Request::routeIs('global.ranking'))
        <script type="text/javascript">
            let pageOp = 1;
            let lastPageOp = undefined;

            window.addEventListener('scroll', function() {
                if (window.pageYOffset + window.innerHeight >= document.documentElement.scrollHeight) {
                    console.log("Scrolled");
                    if (lastPageOp != undefined) {
                        if (pageOp <= lastPageOp) {
                            pageOp++;
                            loadMoreData(pageOp);
                        }
                    } else {
                        pageOp++;
                        loadMoreData(pageOp);
                    }
                }
            });

            function loadMoreData(pageOp) {
                fetch('http://127.0.0.1:8000/global-ranking' + '?openings_page=' + pageOp, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.openings == " ") {
                            console.log("No data from the backend");
                            lastPageOp = 1;
                            return;
                        }
                        console.log("new request");
                        console.log(data);
                        lastPageOp = data.lastPageOp;
                        document.querySelector("#openings").innerHTML += data.openings;

                    })
                    .catch(error => console.log(error));
            }
        </script>
    @endif
@endsection --}}
