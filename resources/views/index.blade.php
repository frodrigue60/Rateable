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
        <div class="container-top">
            <section class="container-items limit-items-index">
                @if (Request::routeIs('/') || Request::routeIs('global.ranking'))
                    <h2 hidden class="text-light">Best Anime Openings of All Time</h2>
                @endif
                <div class="top-header-ranking">
                    <div>
                        <span>Top Openings</span>
                    </div>
                    <div>
                        @if (Request::routeIs('/'))
                            <a href="{{ route('ranking') }}" class="btn btn-sm color4">Ranking</a>
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 w-100">
                    @include('partials.top.positions-index', ['items' => $openings])
                </div>

            </section>

            <section class="container-items limit-items-index">
                @if (Request::routeIs('/') || Request::routeIs('global.ranking'))
                    <h2 hidden class="text-light">Best Anime Endings of All Time</h2>
                @endif
                <div class="top-header-ranking">
                    <div>
                        <span>Top Endings</span>
                    </div>
                    <div>
                        @if (Request::routeIs('/'))
                            <a href="{{ route('ranking') }}" class="btn btn-sm color4">Ranking</a>
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 w-100">
                    @include('partials.top.positions-index', ['items' => $endings])
                </div>
            </section>
        </div>
        {{-- POSTS SECTION --}}
        <section class="contenedor-main">
            {{-- RECENTS --}}
            <section class="carouselContainermain">
                <div class="top-header">
                    <div>
                        <h2 class="text-light mb-0">Recently added</h2>
                    </div>
                    <div>
                        <a href="{{ route('themes', 'sort=recent') }}" class="btn btn-sm color4">All Recently Posts</a>
                    </div>
                </div>
                <div class="owl-carousel carousel-recents-main">
                    @foreach ($recently as $variant)
                        @php
                            $version = $variant->version_number;
                            $forward_text =
                                ($variant->song->slug ? $variant->song->slug : $variant->song->type) .
                                'v' .
                                $variant->version_number;

                            $post = $variant->song->post;
                            $title = $post->title;

                            if (Storage::disk('public')->exists($post->thumbnail)) {
                                $thumbnail_url = Storage::url($post->thumbnail);
                            } else {
                                $thumbnail_url = $post->thumbnail_src;
                            }
                        @endphp

                        <article class="tarjeta">
                            <a class="no-deco" href="{{ $variant->url }}" target="_blank"
                                rel="nofollow noopener noreferrer">
                                <div class="textos">
                                    <div class="tarjeta-header text-light">
                                        <h3 class="text-shadow text-uppercase post-titles">{{ $title }}</h3>
                                    </div>
                                    <div class="{{ $variant->song->type == '1' ? 'tag' : 'tag2' }}">
                                        <span class="tag-content ">{{ $forward_text }}</span>
                                    </div>
                                    <img class="thumb" loading="lazy" src="{{ $thumbnail_url }}"
                                        alt="{{ $title }}" title="{{ $title }}">
                                    {{-- <div class="tarjeta-footer text-light">
                                        <span></span>
                                    </div> --}}
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        </section>

        <section class="contenedor-main">
            <div class="top-header mb-2 mt-2">
                <div>
                    <h2 class="text-light mb-0">Most Pupular</h2>
                </div>
                <div>
                    <a href="{{ route('themes', 'sort=likeCount') }}" class="btn btn-sm color4">Most Popular</a>
                </div>
            </div>
            {{-- POPULAR POSTS --}}
            <section class="contenedor-tarjetas-main">
                @foreach ($popular->take(14) as $variant)
                    @isset($variant->song->post)
                        @include('partials.variants.card')
                    @endisset
                @endforeach
            </section>
            <div class="top-header mb-2 mt-2">
                <div>
                    <h2 class="text-light mb-0">Most Viewed</h2>
                </div>
                <div>
                    <a href="{{ route('themes', 'sort=view_count') }}" class="btn btn-sm color4">Most Viewed</a>
                </div>
            </div>
            {{-- MOST VIEWED --}}
            <section class="contenedor-tarjetas-main">
                @foreach ($viewed->take(14) as $variant)
                    @isset($variant->song->post)
                        @include('partials.variants.card')
                    @endisset
                @endforeach
            </section>
        </section>

    </section>
@endsection
