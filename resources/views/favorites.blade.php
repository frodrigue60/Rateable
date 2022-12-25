@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="background-color: #0e3d5f">
            <h1 class="text-light">OPENINGS</h1>
        </div>
        <div class="contenedor-favoritos">
            @foreach ($openings as $post)
                    <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}">
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
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @case('POINT_10')
                                                    <strong>{{ round($post->averageRating / 10) }}</strong> <i class="fa fa-star"></i>
                                                @break

                                                @case('POINT_5')
                                                    <strong>{{ round($post->averageRating / 20) }}</strong> <i class="fa fa-star"></i>
                                                @break

                                                @default
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                            @endswitch
                                        @else
                                            <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                class="fa fa-star"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
        </div>
        <hr>

        <div style="background-color: #0e3d5f">
            <h1 class="text-light">ENDINGS</h1>
        </div>
        <div class="contenedor-favoritos">
            @foreach ($endings as $post)
                    <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}">
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
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @case('POINT_10')
                                                    <strong>{{ round($post->averageRating / 10) }}</strong> <i class="fa fa-star"></i>
                                                @break

                                                @case('POINT_5')
                                                    <strong>{{ round($post->averageRating / 20) }}</strong> <i class="fa fa-star"></i>
                                                @break

                                                @default
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                            @endswitch
                                        @else
                                            <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                class="fa fa-star"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
        </div>
    </div>
@endsection
