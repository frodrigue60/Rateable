@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="{{ url()->current() }}" />
    @if (isset($currentSeason->name))
        <meta name="keywords"
            content="ranking, top, anime openings {{ $currentSeason->name }}, openings anime {{ $currentSeason->name }}, anime endings {{ $currentSeason->name }}, endings anime {{ $currentSeason->name }}, of {{ $currentSeason->name }}">
        @if (Request::is('openings'))
            <title>Openings {{ $currentSeason->name }}</title>
            <meta title="Openings {{ $currentSeason->name }}">
            <meta name="description" content="Openings of {{ $currentSeason->name }} anime season">

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
            <meta property="og:title" content="Openings {{ $currentSeason->name }}" />
            <meta property="og:description" content="Openings of {{ $currentSeason->name }} anime season" />
        @endif
        @if (Request::is('endings'))
            <title>Endings {{ $currentSeason->name }}</title>
            <meta title="Endings {{ $currentSeason->name }}">
            <meta name="description" content="Endings of {{ $currentSeason->name }} anime season">

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
            <meta property="og:title" content="Endings {{ $currentSeason->name }}" />
            <meta property="og:description" content="Endings of {{ $currentSeason->name }} anime season" />
        @endif
    @endif
@endsection
@section('content')
    <div class="container">
        <div class="contenedor">
            {{-- DIV POSTS --}}
            <section>
                <div class="top-header color1 mb-1 mt-1">
                    @if (Request::is('openings'))
                        <div>
                            @if (isset($currentSeason->name))
                                <h2 class="text-light">Openings {{ $currentSeason->name }}</h2>
                            @else
                                <h2 class="text-light">Openings</h2>
                            @endif

                        </div>
                    @endif
                    @if (Request::is('endings'))
                        <div>
                            @if (isset($currentSeason->name))
                                <h2 class="text-light">Endings {{ $currentSeason->name }}</h2>
                            @else
                                <h2 class="text-light">Endings</h2>
                            @endif
                        </div>
                    @endif
                </div>

                <section class="contenedor-tarjetas mt-2">
                    @isset($song_variants)
                        @include('layouts.variant.cards')
                    @endisset
                </section>
            </section>
            <aside>
                {{-- DIV BANNER --}}
                <section class="contenedor-banner">
                    <section class="seasons-container">
                        <div class="top-header">
                            <div>
                                <span>Seasons</span>
                            </div>
                            <div>
                                <a href="{{ route('themes') }}" class="btn btn-sm color4">More</a>
                            </div>
                        </div>
                        <div class="seasons-content">
                            @foreach ($tags as $item)
                                @php
                                    [$season, $year] = explode(' ', $item->name);
                                @endphp
                                <article class="season-item color4">
                                    <span><a href="{{ route('animes', ['type=', 'year=' . $year, 'season=' . $season, 'sort=', 'char=']) }}"
                                            class="no-deco text-light">{{ $item->name }}</a></span>
                                </article>
                            @endforeach
                        </div>
                    </section>
                    <section class="container-items-seasonal">
                        <div class="top-header">
                            <div>
                                @if (Request::is('openings'))
                                    <span class="text-light mb-0">Top Openings @isset($currentSeason)
                                            {{ $currentSeason->name }}
                                        @endisset
                                    </span>
                                @endif
                                @if (Request::is('endings'))
                                    <span class="text-light mb-0">Top Endings @isset($currentSeason)
                                            {{ $currentSeason->name }}
                                        @endisset
                                    </span>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('seasonal.ranking') }}" class="btn btn-sm color4">More</a>
                            </div>
                        </div>
                        @php
                            $j = 1;
                        @endphp
                        @isset($song_variants)
                            @foreach ($song_variants->sortByDesc('averageRating')->take(10) as $song_variant)
                                @php
                                    $song_id = $song_variant->song->id;
                                    $post_slug = $song_variant->song->post->slug;
                                    $suffix = $song_variant->song->slug != null ? $song_variant->song->slug : $song_variant->song->type;
                                    $version = $song_variant->version_number;
                                    $forward_text = ($song_variant->song->slug ? $song_variant->song->slug : $song_variant->song->type) . 'v' . $song_variant->version_number;
                                    $title = $song_variant->song->post->title;
                                @endphp
                                <article class="top-item-seasonal">
                                    <div class="item-place-seasonal">
                                        <span><strong>{{ $j++ }}</strong></span>
                                    </div>
                                    <div class="item-info-seasonal">
                                        <div class="item-post-info-seasonal">
                                            <a href="{{ $song_variant->url }}"
                                                class="text-light no-deco">{{ $title . ' ' . $forward_text }}</a>
                                        </div>
                                    </div>
                                    <div class="item-score-seasonal">
                                        <span class="ms-2">{{ $song_variant->score != null ? $song_variant->score : 'n/a' }} <i
                                                class="fa fa-star" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </article>
                            @endforeach
                        @endisset
                    </section>
                </section>
            </aside>
        </div>
    </div>
@endsection
