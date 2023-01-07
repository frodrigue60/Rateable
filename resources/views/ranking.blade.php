@extends('layouts.app')
@section('meta')
    @if (isset($currentSeason->name))
        <title>Ranking {{ $currentSeason->name }} Openings & Endings</title>
        <meta title="Ranking {{ $currentSeason->name }} Openings & Endings">
    @else
        <title>Ranking Openings & Endings</title>
        <meta title="Ranking Openings & Endings">
    @endif
@endsection
@section('content')
    <div class="container">
        <section class="container-top">
            <section class="container-items">
                <div id="top-header">
                    <div>
                        @if (Request::is('seasonal-ranking'))
                            <h2 class="text-light mb-0">Top Openings: @isset($currentSeason)
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
                        <div id="item-place">
                            <span><strong>{{ $j++ }}</strong></span>
                        </div>
                        <div id="item-info">
                            <div id="item-post-info">
                                <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                        class="text-light no-deco text-uppercase">{{ $post->title }}
                                        @if ($post->opNum != null)
                                            ({{ $post->type }}{{ $post->opNum }})
                                        @endif
                                    </a></span>
                            </div>
                            @if (isset($post->song->song_romaji))
                                <div id="item-song-info">
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
                                    <div id="item-song-info">
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
                        <div id="item-score">
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
                <div id="top-header">
                    <div>
                        @if (Request::is('seasonal-ranking'))
                            <h2 class="text-light mb-0">Top Openings:
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
                        <div id="item-place">

                            <span><strong>{{ $j++ }}</strong></span>
                        </div>
                        <div id="item-info">
                            <div id="item-post-info">
                                <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                        class="text-light no-deco text-uppercase">{{ $post->title }}
                                        @if ($post->opNum != null)
                                            ({{ $post->type }}{{ $post->opNum }})
                                        @endif
                                    </a></span>
                            </div>
                            @isset($post->song->song_romaji)
                                <div id="item-song-info">
                                    <span><strong>{{ $post->song->song_romaji }}</strong> By
                                        <strong>{{ $post->artist->name }}</strong></span>
                                </div>
                            @endisset
                        </div>
                        <div id="item-score">
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
