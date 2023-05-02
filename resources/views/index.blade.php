@extends('layouts.app')
@section('meta')
    @if (Request::is('/'))
        <title>Ranking Anime Openings & Endings | {{ env('APP_NAME') }}</title>
        <meta name="title" content="Search, play, and rate anime openings and endings">
        <meta name="description"
            content="The site you were looking for to rate openings and endings of your favorite animes. Discover which are the most popular opening and endings.">
        <meta name="keywords"
            content="top anime openings, top anime endings, ranking openings anime, ranking endings anime, Best Anime Openings Of All Time, openings anime, endings anime">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="robots" content="index, follow, max-image-preview:standard">
        <meta property="og:type" content="website" />
        <meta property="og:image" content="{{ asset('resources/images/og-image-wide.png') }}">
        <meta property="og:image:secure_url" content="{{ asset('resources/images/og-image-wide.png') }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="828">
        <meta property="og:image:height" content="450">
        <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:image:alt" content="Anirank banner" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@frodrigue60" />
        <meta name="twitter:creator" content="@frodrigue60" />
        <meta property="og:title" content="Search, play, and rate anime openings and endings" />
        <meta property="og:description"
            content="The site you were looking for to rate openings and endings of your favorite animes. Discover which are the most popular opening and endings." />
        {{-- <meta property="og:image" content="{{ asset('resources/images/og-twitter-image.png') }}" /> --}}
    @endif
@endsection
@section('content')
    <section class="container">

        {{-- TOP SECTION --}}
        <section class="contenedor-main">
            <div class="container-top">
                <section class="container-items limit-items-index">
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
                    @php
                        $j = 1;
                    @endphp
                    @include('layouts.top.openings')
                </section>

                <section class="container-items limit-items-index">
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
                                    <a href="{{ route('seasonal.ranking') }}" class="btn btn-sm color4">Seasonal
                                        Ranking</a>
                                @else
                                    @if (Request::routeIs('seasonal.ranking'))
                                        <a href="{{ route('global.ranking') }}" class="btn btn-sm color4">Global
                                            Ranking</a>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                    @php
                        $j = 1;
                    @endphp
                    @include('layouts.top.endings')
                </section>
            </div>
        </section>

        {{-- POSTS SECTION --}}
        @include('layouts.index.recents-carousel')
        @include('layouts.index.posts-index')

    </section>
@endsection
