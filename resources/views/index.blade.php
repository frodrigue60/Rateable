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
        <meta property="fb:app_id" content="1363850827699525" />
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
        <h1 hidden class="text-light">Best Anime Openings & Endings of All Time</h1>
        {{-- POSTS SECTION --}}
        @include('layouts.recentsCarousel')
        @include('layouts.postsIndex')
        {{--  @include('layouts.corousels') --}}
        {{-- TOP SECTION --}}
        <section class="contenedor-main">
            <h2 hidden>TOP ANIME OPENINGS & ENDINGS OF ALL TIME</h2>
            <div class="container-top">
                <section class="container-items">
                    <h3 hidden>TOP ANIME OPENINGS OF ALL TIME</h3>
                    <div class="top-header">
                        <div>
                            <span>Global Rank Openings</span>
                        </div>
                        <div>
                            <a href="{{ route('globalranking') }}" class="btn btn-sm color4">Global Ranking</a>
                        </div>
                    </div>
                    @php
                        $j = 1;
                    @endphp
                    @foreach ($openings->take(10)->sortByDesc('averageRating') as $post)
                        <article class="top-item">
                            <div class="item-place">
                                <span><strong>{{ $j++ }}</strong></span>
                            </div>
                            <div class="item-info">
                                <div class="item-post-info">
                                    <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                            class="text-light no-deco">{{ $post->title }}
                                            {{ $post->suffix != null ? $post->suffix : '' }}</a></span>
                                </div>
                                {{-- SONG ROMAJI --}}
                                @if (isset($post->song->song_romaji))
                                    <div class="item-song-info">
                                        <span id="song-title"><strong><a
                                                    href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                                    class="no-deco text-light">{{ $post->song->song_romaji }}</a></strong></span>
                                        @isset($post->artist->name)
                                            <span style="margin-left: 4px;margin-right:4px;">By</span>
                                            <span id="song-artist"><strong><a
                                                        href="{{ route('fromartist', $post->artist->name_slug) }}"
                                                        class="no-deco text-light">{{ $post->artist->name }}</a></strong></span>
                                        @endisset

                                    </div>
                                @else
                                    {{-- SONG ENG --}}
                                    @if (isset($post->song->song_en))
                                        <div class="item-song-info">
                                            <span><strong><a id="song-title"
                                                        href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                                        class="no-deco text-light">{{ $post->song->song_en }}</a></strong>
                                                @isset($post->artist->name)
                                                    <span style="margin-left: 4px;margin-right:4px;">By</span>
                                                    <strong><a id="song-artist"
                                                            href="{{ route('fromartist', $post->artist->name_slug) }}"
                                                            class="no-deco text-light">{{ $post->artist->name }}</a></strong>
                                                @endisset
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="item-score">
                                <span>
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($post->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($post->averageRating / 10, 1) }}
                                            @break

                                            @case('POINT_10')
                                                {{ round($post->averageRating / 10) }}
                                            @break

                                            @case('POINT_5')
                                                {{ round($post->averageRating / 20) }}
                                            @break

                                            @default
                                                {{ round($post->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($post->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                            </div>
                        </article>
                    @endforeach
                </section>
                <section class="container-items">
                    <h3 hidden>TOP ANIME ENDINGS OF ALL TIME</h3>
                    <div class="top-header">
                        <div>
                            <span>Global Rank Endings</span>
                        </div>
                        <div>
                            <a href="{{ route('globalranking') }}" class="btn btn-sm color4">Global Ranking</a>
                        </div>
                    </div>
                    @php
                        $j = 1;
                    @endphp
                    @foreach ($endings->take(10)->sortByDesc('averageRating') as $post)
                        <article class="top-item">
                            <div class="item-place">

                                <span><strong>{{ $j++ }}</strong></span>
                            </div>
                            <div class="item-info">
                                <div class="item-post-info">
                                    <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                            class="text-light no-deco">{{ $post->title }}</a></span>
                                </div>
                                @if (isset($post->song->song_romaji))
                                    <div class="item-song-info">
                                        <span><strong><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                                    class="no-deco text-light">{{ $post->song->song_romaji }}</a></strong>
                                            @isset($post->artist->name)
                                                By
                                                <strong><a href="{{ route('fromartist', $post->artist->name_slug) }}"
                                                        class="no-deco text-light">{{ $post->artist->name }}</a></strong>
                                            @endisset
                                        </span>
                                    </div>
                                @else
                                    @if (isset($post->song->song_en))
                                        <div class="item-song-info">
                                            <span><strong><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                                        class="no-deco text-light">{{ $post->song->song_en }}</a></strong>
                                                @isset($post->artist->name)
                                                    By
                                                    <strong><a href="{{ route('fromartist', $post->artist->name_slug) }}"
                                                            class="no-deco text-light">{{ $post->artist->name }}</a></strong>
                                                @endisset
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="item-score">
                                <span>
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($post->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($post->averageRating / 10, 1) }}
                                            @break

                                            @case('POINT_10')
                                                {{ round($post->averageRating / 10) }}
                                            @break

                                            @case('POINT_5')
                                                {{ round($post->averageRating / 20) }}
                                            @break

                                            @default
                                                {{ round($post->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($post->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                            </div>
                        </article>
                    @endforeach
                </section>
            </div>
        </section>
    </section>
@endsection
