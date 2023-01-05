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
        <div class="contenedor-main">
            {{-- DIV POSTS --}}

            <div id="top-header" class="mb-1 mt-1">
                <div>
                    <h2 class="text-light mb-0">Recently added</h2>
                </div>
                <div>
                    <a href="{{ route('filter', 'sort=null') }}" class="btn btn-sm color4">More</a>
                </div>
            </div>
            <div id="carousel-recents-main" class="owl-carousel">
                @foreach ($recently as $post)
                    <div class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header text-light">
                                <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                            </div>
                            
                            @if ($post->type == 'op')
                                <div class="tag">
                                    <span class="tag-content ">{{$post->type}}{{ $post->opNum }}</span>
                                </div>
                            @else
                                <div class="tag2">
                                    <span class="tag-content ">{{$post->type}}{{ $post->opNum }}</span>
                                </div>
                            @endif
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
            <div id="top-header" class="mb-1 mt-1">
                <div>
                    <h2 class="text-light mb-0">Most popular</h2>
                </div>
                <div>
                    <a href="{{ route('filter', 'sort=likeCount') }}" class="btn btn-sm color4">More</a>
                </div>
            </div>
            <div id="carousel-recents-main" class="owl-carousel">
                @foreach ($popular as $post)
                    <div class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header text-light">
                                <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                            </div>
                            @if ($post->type == 'op')
                                <div class="tag">
                                    <span class="tag-content ">{{$post->type}}{{ $post->opNum }}</span>
                                </div>
                            @else
                                <div class="tag2">
                                    <span class="tag-content ">{{$post->type}}{{ $post->opNum }}</span>
                                </div>
                            @endif
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
            <div id="top-header" class="mb-1 mt-1">
                <div>
                    <h2 class="text-light mb-0">Most viewed</h2>
                </div>
                <div>
                    <a href="{{ route('filter', 'sort=view_count') }}" class="btn btn-sm color4">More</a>
                </div>
            </div>
            <div id="carousel-recents-main" class="owl-carousel">
                @foreach ($viewed as $post)
                    <div class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header text-light">
                                <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                            </div>
                            @if ($post->type == 'op')
                                <div class="tag">
                                    <span class="tag-content ">{{$post->type}}{{ $post->opNum }}</span>
                                </div>
                            @else
                                <div class="tag2">
                                    <span class="tag-content ">{{$post->type}}{{ $post->opNum }}</span>
                                </div>
                            @endif
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

            <div class="container-top">
                <div class="container-items">
                    <div id="top-header">
                        <div>
                            <span>Global Rank Openings</span>
                        </div>
                        <div>
                            <a href="{{ route('globalranking') }}" class="btn btn-sm color4">More</a>
                        </div>
                    </div>
                    @for ($j = 1; $j < 0; $j++)
                    @endfor
                    @foreach ($openings->take(10)->sortByDesc('averageRating') as $post)
                        <div class="top-item">
                            <div id="item-place">
                                <span><strong>{{ $j++ }}</strong></span>
                            </div>
                            <div id="item-info">
                                <div id="item-post-info">
                                    <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                            class="text-light no-deco">{{ $post->title }}</a></span>
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
                        </div>
                    @endforeach
                </div>
                <div class="container-items">
                    <div id="top-header">
                        <div>
                            <span>Global Rank Endings</span>
                        </div>
                        <div>
                            <a href="{{ route('globalranking') }}" class="btn btn-sm color4">More</a>
                        </div>
                    </div>
                    @for ($j = 1; $j < 0; $j++)
                    @endfor
                    @foreach ($endings->take(10)->sortByDesc('averageRating') as $post)
                        <div class="top-item">
                            <div id="item-place">

                                <span><strong>{{ $j++ }}</strong></span>
                            </div>
                            <div id="item-info">
                                <div id="item-post-info">
                                    <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                            class="text-light no-deco">{{ $post->title }}</a></span>
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
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {
            $(".owl-carousel").owlCarousel({
                //stagePadding: 1,
                loop: false,
                margin: 8,
                autoWidth: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                rewind: true,
            });
        });
    </script>
@endsection
