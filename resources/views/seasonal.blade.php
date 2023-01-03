@extends('layouts.app')

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
            <div>
                <div id="top-header" class="color1">
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

                <div class="contenedor-tarjetas">
                    @foreach ($posts as $post)
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                    <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
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
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                {{-- DIV BANNER --}}

                <div class="contenedor-banner">
                    <div id="seasons-container banner">
                        <div id="top-header">
                            <div>
                                <span>Seasons</span>
                            </div>
                            <div>
                                <a href="{{ route('tags') }}" class="btn btn-sm color4">More</a>
                            </div>
                        </div>
                        <div id="seasons-content">
                            @foreach ($tags as $item)
                                <div id="season-item" class="color4">
                                    <span><a href="{{ route('fromtag', $item->slug) }}"
                                            class="no-deco text-light">{{ $item->name }}</a></span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="container-items-seasonal">
                        <div id="top-header">
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
                        @for ($j = 1; $j < 0; $j++)
                        @endfor
                        @foreach ($posts->sortByDesc('averageRating')->take(15) as $post)
                            <div class="top-item-seasonal">
                                <div id="item-place-seasonal">
                                    <span><strong>{{ $j++ }}</strong></span>
                                </div>
                                <div id="item-info-seasonal">
                                    <div id="item-post-info-seasonal">
                                        <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                                class="text-light no-deco">{{ $post->title }}</a></span>
                                    </div>
                                </div>
                                <div id="item-score-seasonal">
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
