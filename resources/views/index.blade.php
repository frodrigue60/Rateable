@extends('layouts.app')
@section('meta')
    @if (Request::is('/'))
        <title>Ranking Anime Openings & Endings | {{ env('APP_NAME') }}</title>
        <meta name="title" content="Search, play, and rate anime openings and endings">
        <meta name="description"
            content="The site you were looking for to rate openings and endings of your favorite animes. Discover which are the most popular opening and endings, as well as the best valued.">
        <meta name="keywords" content="top anime openings, top anime endings, ranking openings anime, ranking endings anime">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">

        <meta property="og:image" content="{{ asset('resources/images/logo2.png') }}" />
        <meta property="og:image:secure_url" content="{{ asset('resources/images/logo2.png') }}" />
        <meta property="og:image:type" content="image/png" />
        <meta property="og:image:width" content="256" />
        <meta property="og:image:height" content="256" />
        {{-- <meta property="og:image:alt" content="A shiny red apple with a bite taken out" /> --}}
    @endif
@endsection
@section('content')
    <section class="container">
        <h1 hidden>TOP ANIME OPENINGS & ENDINGS</h1>
        {{-- POSTS SECTION --}}
        <section class="contenedor-main">
            <h2 hidden>ANIME OPENINGS & ENDINGS</h2>
            {{-- RECENTS --}}
            <section class="carouselContainermain">
                <div class="top-header mb-1 mt-1">
                    <div>
                        <h3 class="text-light mb-0">Recently added</h3>
                    </div>
                    <div>
                        <a href="{{ route('filter', 'sort=null') }}" class="btn btn-sm color4">All Recently Posts</a>
                    </div>
                </div>
                <div class="owl-carousel carousel-recents-main">
                    @foreach ($recently as $post)
                        <article class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                                </div>
                                <div class="{{ $post->type === 'op' ? 'tag' : 'tag2' }}">
                                    <span
                                        class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
                                </div>
                                <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                    <img class="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                        alt="{{ $post->title }}">
                                </a>
                                <div class="tarjeta-footer text-light">
                                    <span>{{ $post->likeCount }} <i class="fa fa-heart"></i></span>
                                    <span>{{ $post->viewCount }} <i class="fa fa-eye"></i></span>
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
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
            {{-- POPULAR --}}
            <section class="carouselContainermain">
                <div class="top-header mb-1 mt-1">
                    <div>
                        <h3 class="text-light mb-0">Most popular</h3>
                    </div>
                    <div>
                        <a href="{{ route('filter', 'sort=likeCount') }}" class="btn btn-sm color4">All Most Populars</a>
                    </div>
                </div>
                <div class="owl-carousel carousel-recents-main">
                    @foreach ($popular as $post)
                        <article class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                                </div>
                                <div class="{{ $post->type === 'op' ? 'tag' : 'tag2' }}">
                                    <span
                                        class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
                                </div>
                                <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                    <img class="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                        alt="{{ $post->title }}">
                                </a>
                                <div class="tarjeta-footer text-light">
                                    <span>{{ $post->likeCount }} <i class="fa fa-heart"></i></span>
                                    <span>{{ $post->viewCount }} <i class="fa fa-eye"></i></span>
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
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
            {{-- MOST VIEWED --}}
            <section class="carouselContainermain">
                <div class="top-header mb-1 mt-1">
                    <div>
                        <h3 class="text-light mb-0">Most viewed</h3>
                    </div>
                    <div>
                        <a href="{{ route('filter', 'sort=viewCount') }}" class="btn btn-sm color4">All Most Viewed</a>
                    </div>
                </div>
                <div class="owl-carousel carousel-recents-main">
                    @foreach ($viewed as $post)
                        <article class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                                </div>
                                <div class="{{ $post->type === 'op' ? 'tag' : 'tag2' }}">
                                    <span
                                        class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
                                </div>
                                <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                    <img class="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                        alt="{{ $post->title }}">
                                </a>
                                <div class="tarjeta-footer text-light">
                                    <span>{{ $post->likeCount }} <i class="fa fa-heart"></i></span>
                                    <span>{{ $post->viewCount }} <i class="fa fa-eye"></i></span>
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
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </section>
        {{-- TOP SECTION --}}
        <section class="contenedor-main">
            <h2 hidden>RANKING ANIME OPENINGS & ENDINGS</h2>
            <div class="container-top">
                <section class="container-items">
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
                <section class="container-items">
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
