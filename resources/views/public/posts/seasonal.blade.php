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
                <div class="top-header color1">
                    @if (Request::is('openings'))
                        <div>
                            <h2 class="text-light mb-0">Openings @isset($currentSeason)
                                    {{ $currentSeason->name }}
                                @endisset
                            </h2>
                        </div>
                    @endif
                    @if (Request::is('endings') && isset($currentSeason))
                        <div>
                            <h2 class="text-light mb-0">Endings {{ $currentSeason->name }}</h2>
                        </div>
                    @endif

                    <div>
                        {{-- <a href="{{route('globalranking')}}" class="btn btn-sm btn-primary">More</a> --}}
                    </div>
                </div>

                <section class="contenedor-tarjetas">
                    @foreach ($songs as $song)
                        @isset($song->post)
                            <article class="tarjeta">
                                <div class="textos">
                                    <div class="tarjeta-header text-light">
                                        <h3 class="text-shadow text-uppercase post-titles">{{ $song->post->title }}</h3>
                                    </div>
                                    @if ($song->type === 'OP' && $song->suffix != null)
                                        <div class="tag">
                                            <span
                                                class="tag-content ">{{ $song->suffix != null ? $song->suffix : $song->type }}</span>
                                        </div>
                                    @else
                                        @if ($song->type === 'ED' && $song->suffix != null)
                                            <div class="tag2">
                                                <span
                                                    class="tag-content ">{{ $song->suffix != null ? $song->suffix : $song->type }}</span>
                                            </div>
                                        @endif
                                    @endif
                                    <a class="no-deco"
                                        href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">
                                        <img class="thumb" loading="lazy"
                                            src="{{ asset('/storage/thumbnails/' . $song->post->thumbnail) }}"
                                            alt="{{ $song->post->title }}" title="{{ $song->post->title }}">
                                    </a>
                                    <div class="tarjeta-footer text-light">
                                        <span>{{ $song->likeCount }} <i class="fa fa-heart"></i></span>
                                        <span>{{ $song->view_count }} <i class="fa fa-eye"></i></span>
                                        <span>{{$song->score != null ? $song->score : 'n/a'}} <i class="fa fa-star" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @endisset
                    @endforeach
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
                                <article class="season-item color4">
                                    <span><a href="{{ route('themes', 'tag=' . str_replace(' ', '+', $item->name)) }}"
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
                        @isset($songs)
                            @foreach ($songs->sortByDesc('averageRating')->take(15) as $song)
                                <article class="top-item-seasonal">
                                    <div class="item-place-seasonal">
                                        <span><strong>{{ $j++ }}</strong></span>
                                    </div>
                                    <div class="item-info-seasonal">
                                        <div class="item-post-info-seasonal">
                                            <a href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                                class="text-light no-deco">{{ $song->post->title }}</a>
                                        </div>
                                    </div>
                                    <div class="item-score-seasonal">
                                        <span>{{$song->score != null ? $song->score : 'n/a'}} <i class="fa fa-star" aria-hidden="true"></i>
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
