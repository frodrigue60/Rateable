@extends('layouts.app')
@section('meta')
    @isset($currentSeason)
        <meta name="robots" content="index, follow, max-snippet:20, max-image-preview:standard">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="keywords"
            content="ranking, top, anime openings {{ $currentSeason->name }}, openings anime {{ $currentSeason->name }}, anime endings {{ $currentSeason->name }}, endings anime {{ $currentSeason->name }}, of {{ $currentSeason->name }}">
        @if (Request::is('openings'))
            <title>Anirank: Openings {{ $currentSeason->name }}</title>
            <meta title="Openings {{ $currentSeason->name }}">
            <meta name="description" content="Openings of {{ $currentSeason->name }} anime season">
        @endif
        @if (Request::is('endings'))
            <title>Anirank: Endings {{ $currentSeason->name }}</title>
            <meta title="Endings {{ $currentSeason->name }}">
            <meta name="description" content="Endings of {{ $currentSeason->name }} anime season">
        @endif
    @endisset
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
                    @if (Request::is('endings'))
                        <div>
                            <h2 class="text-light mb-0">Endings @isset($currentSeason)
                                    {{ $currentSeason->name }}
                                @endisset
                            </h2>
                        </div>
                    @endif

                    <div>
                        {{-- <a href="{{route('globalranking')}}" class="btn btn-sm btn-primary">More</a> --}}
                    </div>
                </div>

                <section class="contenedor-tarjetas">
                    @foreach ($posts as $post)
                    <article class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header text-light">
                                <h3 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h3>
                            </div>
                            <div class="{{ $post->type == 'OP' ? 'tag' : 'tag2' }}">
                                <span class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
                            </div>
                            <a class="no-deco" href="{{ route('post.show', [$post->id, $post->slug]) }}">
                                <img class="thumb" loading="lazy" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}" title="{{ $post->title }}">
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
                                <a href="{{ route('filter') }}" class="btn btn-sm color4">More</a>
                            </div>
                        </div>
                        <div class="seasons-content">
                            @foreach ($tags as $item)
                                <article class="season-item color4">
                                    <span><a href="{{ route('filter', 'tag='.str_replace(' ', '+', $item->name)) }}"
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
                                <a href="{{ route('seasonalranking') }}" class="btn btn-sm color4">More</a>
                            </div>
                        </div>
                        @php
                            $j = 1;
                        @endphp
                        @foreach ($posts->sortByDesc('averageRating')->take(15) as $post)
                            <article class="top-item-seasonal">
                                <div class="item-place-seasonal">
                                    <span><strong>{{ $j++ }}</strong></span>
                                </div>
                                <div class="item-info-seasonal">
                                    <div class="item-post-info-seasonal">
                                        <span><a href="{{ route('post.show', [$post->id, $post->slug]) }}"
                                                class="text-light no-deco">{{ $post->title }}</a></span>
                                    </div>
                                </div>
                                <div class="item-score-seasonal">
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
            </aside>
        </div>
    </div>
@endsection
