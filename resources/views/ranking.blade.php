@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow, max-snippet:20, max-image-preview:standard">
    @if (isset($currentSeason->name))
        <link rel="canonical" href="{{ url()->current() }}">
        <title>Ranking Best Openings & Endings {{ $currentSeason->name }}</title>
        <meta title="Ranking Openings & Endings {{ $currentSeason->name }}">
        <meta name="description" content="Ranking Best Openings & Endings {{ $currentSeason->name }}">
        <meta name="keywords"
            content="ranking, top, anime openings {{ $currentSeason->name }}, openings anime {{ $currentSeason->name }}, anime endings {{ $currentSeason->name }}, endings anime {{ $currentSeason->name }}, of {{ $currentSeason->name }}">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
        <title>Ranking Openings & Endings of All Time</title>
        <meta title="Ranking Openings & Endings of All Time">
        <meta name="description" content="Ranking Best Openings & Endings of All Time">
        <meta name="keywords"
            content="ranking, top, anime openings, openings anime, anime endings, endings anime, of all time">
    @endif
@endsection
@section('content')
    <div class="container">
        <section class="container-top">
            <section class="container-items">
                <div class="top-header">
                    <div>
                        @if (Request::is('seasonal-ranking'))
                            <h2 class="text-light mb-0">Top Openings @isset($currentSeason)
                                    {{ $currentSeason->name }}
                                @endisset
                            </h2>
                        @endif
                        @if (Request::is('global-ranking'))
                            <h2 class="text-light mb-0">Global Rank Openings
                            </h2>
                        @endif
                    </div>
                </div>
                @php
                    $j = 1;
                @endphp
                @foreach ($openings->sortByDesc('averageRating') as $post)
                    <article class="top-item">
                        <div class="item-place">
                            <span><strong>{{ $j++ }}</strong></span>
                        </div>
                        <div class="item-info">
                            <div class="item-post-info">
                                <span><a href="{{ route('show', [$post->id, $post->slug]) }}"
                                        class="text-light no-deco text-uppercase">{{ $post->title }}
                                        @if ($post->themeNum != null)
                                            ({{ $post->type }}{{ $post->themeNum }})
                                        @endif
                                    </a></span>
                            </div>
                            @if (isset($post->song->song_romaji))
                                <div class="item-song-info">
                                    <span><strong><a href="{{ route('show', [$post->id, $post->slug]) }}"
                                                class="no-deco text-light">{{ $post->song->song_romaji }}</a></strong>
                                        @isset($post->artist->name)
                                            By
                                            <strong><a href="{{ route('from.artist', $post->artist->name_slug) }}"
                                                    class="no-deco text-light">{{ $post->artist->name }}</a></strong>
                                        @endisset
                                    </span>
                                </div>
                            @else
                                @if (isset($post->song->song_en))
                                    <div class="item-song-info">
                                        <span><strong><a href="{{ route('show', [$post->id, $post->slug]) }}"
                                                    class="no-deco text-light">{{ $post->song->song_en }}</a></strong>
                                            @isset($post->artist->name)
                                                By
                                                <strong><a href="{{ route('from.artist', $post->artist->name_slug) }}"
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
                <div class="top-header">
                    <div>
                        @if (Request::is('seasonal-ranking'))
                            <h2 class="text-light mb-0">Top Endings
                                @isset($currentSeason)
                                    {{ $currentSeason->name }}
                                @endisset
                            </h2>
                        @endif
                        @if (Request::is('global-ranking'))
                            <h2 class="text-light mb-0">Global Rank Endings
                            </h2>
                        @endif
                    </div>
                </div>
                @php
                    $j = 1;
                @endphp
                @foreach ($endings->sortByDesc('averageRating') as $post)
                    <article class="top-item">
                        <div class="item-place">

                            <span><strong>{{ $j++ }}</strong></span>
                        </div>
                        <div class="item-info">
                            <div class="item-post-info">
                                <span><a href="{{ route('show', [$post->id, $post->slug]) }}"
                                        class="text-light no-deco text-uppercase">{{ $post->title }}
                                        @if ($post->themeNum != null)
                                            ({{ $post->type }}{{ $post->themeNum }})
                                        @endif
                                    </a></span>
                            </div>
                            @if (isset($post->song->song_romaji))
                                <div class="item-song-info">
                                    <span><strong><a href="{{ route('show', [$post->id, $post->slug]) }}"
                                                class="no-deco text-light">{{ $post->song->song_romaji }}</a></strong>
                                        @isset($post->artist->name)
                                            By
                                            <strong><a href="{{ route('from.artist', $post->artist->name_slug) }}"
                                                    class="no-deco text-light">{{ $post->artist->name }}</a></strong>
                                        @endisset
                                    </span>
                                </div>
                            @else
                                @if (isset($post->song->song_en))
                                    <div class="item-song-info">
                                        <span><strong><a href="{{ route('show', [$post->id, $post->slug]) }}"
                                                    class="no-deco text-light">{{ $post->song->song_en }}</a></strong>
                                            @isset($post->artist->name)
                                                By
                                                <strong><a href="{{ route('from.artist', $post->artist->name_slug) }}"
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
        </section>
    </div>
@endsection
