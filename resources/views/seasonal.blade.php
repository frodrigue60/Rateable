@extends('layouts.app')
@section('meta')
    @isset($currentSeason)
        @if (Request::is('openings'))
            <title>Anirank: Openings {{ $currentSeason->name }}</title>
            <meta title="Openings {{ $currentSeason->name }}">
        @endif
        @if (Request::is('endings'))
            <title>Anirank: Endings {{ $currentSeason->name }}</title>
            <meta title="Endings {{ $currentSeason->name }}">
        @endif
    @endisset
@endsection
@section('content')
    @if (session('status'))
        <div class="container">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
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
                                <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                            </div>
                            @if ($post->type == 'op')
                                <div class="tag">
                                    <span class="tag-content ">{{ $post->type }}{{ $post->opNum }}</span>
                                </div>
                            @else
                                <div class="tag2">
                                    <span class="tag-content ">{{ $post->type }}{{ $post->opNum }}</span>
                                </div>
                            @endif
                            <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                <img class="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}">
                            </a>
                            <div class="tarjeta-footer text-light">
                                <div>
                                    {{ $post->likeCount }} <i class="fa fa-heart"></i>
                                </div>
                                <div>
                                    {{ $post->view_count }} <i class="fa fa-eye"></i>
                                </div>
                                <div>
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
                                </div>
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
                                <a href="{{ route('tags') }}" class="btn btn-sm color4">More</a>
                            </div>
                        </div>
                        <div class="seasons-content">
                            @foreach ($tags as $item)
                                <article class="season-item color4">
                                    <span><a href="{{ route('fromtag', $item->slug) }}"
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
                                        <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
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
