@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow, max-snippet:20, max-image-preview:standard">
    @if (Request::routeIs('seasonal.ranking'))
        <link rel="canonical" href="{{ url()->current() }}">
        @isset($currentSeason)
            <title>Ranking Best Openings & Endings {{ $currentSeason->name }}</title>
            <meta title="Ranking Openings & Endings {{ $currentSeason->name }}">
            <meta name="description" content="Ranking Best Openings & Endings {{ $currentSeason->name }}">
            <meta name="keywords"
                content="ranking, top, anime openings {{ $currentSeason->name }}, openings anime {{ $currentSeason->name }}, anime endings {{ $currentSeason->name }}, endings anime {{ $currentSeason->name }}, of {{ $currentSeason->name }}">
        @endisset
    @else
        @if (Request::routeIs('global.ranking'))
            <link rel="canonical" href="{{ url()->current() }}">
            <title>Ranking Openings & Endings of All Time</title>
            <meta title="Ranking Openings & Endings of All Time">
            <meta name="description" content="Ranking Best Openings & Endings of All Time">
            <meta name="keywords"
                content="ranking, top, anime openings, openings anime, anime endings, endings anime, of all time">
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
                        <article class="top-item">
                            <div class="item-place">
                                <span><strong>{{ $j++ }}</strong></span>
                            </div>
                            <div class="item-info">
                                <div class="item-post-info">
                                    <span><a href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                            class="text-light no-deco text-uppercase">{{ $song->post->title }}
                                            @if (isset($song->suffix))
                                                ({{ $song->suffix }})
                                            @endif
                                        </a></span>
                                </div>
                                @if (isset($song->song_romaji) || isset($song->song_en) || isset($song->song_jp))
                                    <div class="item-song-info">
                                        <span id="song-title"><strong><a
                                                    href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                                    class="no-deco text-light">
                                                    @if (isset($song->song_romaji))
                                                        {{ $song->song_romaji }}
                                                    @else
                                                        @if (isset($song->song_en))
                                                            {{ $song->song_en }}
                                                        @else
                                                            @if (isset($song->song_jp))
                                                                {{ $song->song_jp }}
                                                            @endif
                                                        @endif
                                                    @endif
                                                </a></strong></span>
                                        @if (isset($song->artist->name))
                                            <span style="margin-left: 4px;margin-right:4px;">By</span>
                                            <span id="song-artist"><strong><a
                                                        href="{{ route('artist.show', $song->artist->name_slug) }}"
                                                        class="no-deco text-light">
                                                        {{ $song->artist->name }}
                                                    </a></strong></span>
                                        @endif
                                    </div>
                                @endif
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
                        </article>
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
                        <article class="top-item">
                            <div class="item-place">
                                <span><strong>{{ $j++ }}</strong></span>
                            </div>
                            <div class="item-info">
                                <div class="item-post-info">
                                    <span><a href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                            class="text-light no-deco text-uppercase">{{ $song->post->title }}
                                            @if (isset($song->suffix))
                                                ({{ $song->suffix }})
                                            @endif
                                        </a></span>
                                </div>
                                @if (isset($song->song_romaji) || isset($song->song_en) || isset($song->song_jp))
                                    <div class="item-song-info">
                                        <span id="song-title"><strong><a
                                                    href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                                    class="no-deco text-light">
                                                    @if (isset($song->song_romaji))
                                                        {{ $song->song_romaji }}
                                                    @else
                                                        @if (isset($song->song_en))
                                                            {{ $song->song_en }}
                                                        @else
                                                            @if (isset($song->song_jp))
                                                                {{ $song->song_jp }}
                                                            @endif
                                                        @endif
                                                    @endif
                                                </a></strong></span>
                                        @if (isset($song->artist->name))
                                            <span style="margin-left: 4px;margin-right:4px;">By</span>
                                            <span id="song-artist"><strong><a
                                                        href="{{ route('artist.show', $song->artist->name_slug) }}"
                                                        class="no-deco text-light">
                                                        {{ $song->artist->name }}
                                                    </a></strong></span>
                                        @endif
                                    </div>
                                @endif
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
                        </article>
                    @endisset
                @endforeach
            </section>
        </section>
    </div>
@endsection
