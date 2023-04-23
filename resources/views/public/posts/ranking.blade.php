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
        <section class="container-top">
            <section class="container-items">
                <div class="top-header">
                    <div>
                        @if (Request::routeIs('seasonal.ranking'))
                            @if (isset($currentSeason))
                                <h2 class="text-light mb-0">Top Openings {{ $currentSeason->name }}</h2>
                            @else
                                <h2 class="text-light mb-0">Top Openings</h2>
                            @endif
                        @endif
                        @if (Request::routeIs('global.ranking'))
                            <h2 class="text-light mb-0">Global Rank Openings
                            </h2>
                        @endif
                    </div>
                </div>
                @php
                    $j = 1;
                @endphp
                {{-- OPENINGS --}}
                @foreach ($openings as $song)
                    @isset($song->post)
                        <div class="top-item">
                            <div class="item-place">
                                <span>{{ $j++ }}</span>
                            </div>

                            <div class="item-info"
                                @if (isset($song->post->banner)) style="background-image: url({{ asset('/storage/anime_banner/' . $song->post->banner) }})"
                        @else
                            style="background-image: url(https://s4.anilist.co/file/anilistcdn/media/anime/banner/98707-ZcFGfUAS4YwK.jpg);" @endif>
                                <div class="item-info-filter"></div>
                                @isset($song)
                                    <div class="item-song-info">
                                        @if (isset($song))
                                            @if (isset($song->song_romaji))
                                                <strong><a
                                                        href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_romaji }}</a
                                                        href=""></strong>
                                            @else
                                                @if (isset($song->song_en))
                                                    <strong><a
                                                            href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_en }}</a
                                                            href=""></strong>
                                                @else
                                                    @if (isset($song->song_jp))
                                                        <strong><a
                                                                href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_jp }}</a
                                                                href=""></strong>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            <strong>N/A</strong>
                                        @endif

                                        @if (isset($song->artist))
                                            <strong><a
                                                    href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}">{{ $song->artist->name }}</a
                                                    href=""></strong>
                                        @else
                                            <strong>N/A</strong>
                                        @endif
                                    </div>
                                @endisset
                                <div class="item-post-info">
                                    <span><a
                                            href="{{ route('post.show', [$song->post->id, $song->post->slug]) }}">{{ $song->post->title }}</a>
                                        {{ $song->suffix ? '(' . $song->suffix . ')' : '' }}</span>
                                </div>

                            </div>

                            <div class="item-score">
                                <span>
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($song->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($song->averageRating / 10, 1) }}
                                            @break

                                            @case('POINT_10')
                                                {{ round($song->averageRating / 10) }}
                                            @break

                                            @case('POINT_5')
                                                {{ round($song->averageRating / 20) }}
                                            @break

                                            @default
                                                {{ round($song->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($song->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    @endisset
                @endforeach
            </section>
            <section class="container-items">
                <div class="top-header">
                    <div>
                        @if (Request::routeIs('seasonal.ranking'))
                            @if (isset($currentSeason))
                                <h2 class="text-light mb-0">Top Endings {{ $currentSeason->name }}</h2>
                            @else
                                <h2 class="text-light mb-0">Top Endings</h2>
                            @endif
                        @endif
                        @if (Request::routeIs('global.ranking'))
                            <h2 class="text-light mb-0">Global Rank Endings
                            </h2>
                        @endif
                    </div>
                </div>
                @php
                    $j = 1;
                @endphp
                {{-- ENDINGS --}}
                @foreach ($endings as $song)
                    @isset($song->post)
                        <div class="top-item">
                            <div class="item-place">
                                <span>{{ $j++ }}</span>
                            </div>

                            <div class="item-info"
                                @if (isset($song->post->banner)) style="background-image: url({{ asset('/storage/anime_banner/' . $song->post->banner) }})"
                        @else
                            style="background-image: url(https://s4.anilist.co/file/anilistcdn/media/anime/banner/98707-ZcFGfUAS4YwK.jpg);" @endif>
                                <div class="item-info-filter"></div>
                                @isset($song)
                                    <div class="item-song-info">
                                        @if (isset($song))
                                            @if (isset($song->song_romaji))
                                                <strong><a
                                                        href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_romaji }}</a
                                                        href=""></strong>
                                            @else
                                                @if (isset($song->song_en))
                                                    <strong><a
                                                            href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_en }}</a
                                                            href=""></strong>
                                                @else
                                                    @if (isset($song->song_jp))
                                                        <strong><a
                                                                href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_jp }}</a
                                                                href=""></strong>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            <strong>N/A</strong>
                                        @endif

                                        @if (isset($song->artist))
                                            <strong><a
                                                    href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}">{{ $song->artist->name }}</a
                                                    href=""></strong>
                                        @else
                                            <strong>N/A</strong>
                                        @endif
                                    </div>
                                @endisset
                                <div class="item-post-info">
                                    <span><a
                                            href="{{ route('post.show', [$song->post->id, $song->post->slug]) }}">{{ $song->post->title }}</a>
                                        {{ $song->suffix ? '(' . $song->suffix . ')' : '' }}</span>
                                </div>

                            </div>

                            <div class="item-score">
                                <span>
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($song->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($song->averageRating / 10, 1) }}
                                            @break

                                            @case('POINT_10')
                                                {{ round($song->averageRating / 10) }}
                                            @break

                                            @case('POINT_5')
                                                {{ round($song->averageRating / 20) }}
                                            @break

                                            @default
                                                {{ round($song->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($song->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    @endisset
                @endforeach
            </section>
        </section>
    </div>
@endsection
