@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container text-center text-light">
            @isset($tagName)
                <h1>{{ $tagName->name }}</h1>
            @endisset
            @isset($artist)
                <h1>{{ $artist->name }}
                    @isset($artist->name_jp)
                        ({{ $artist->name_jp }})
                    @endisset
                </h1>
            @endisset
        </div>
        @if (session('status'))
            <div class="container">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Holy guacamole!</strong> {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        <div>
            <h2 class="text-light" style="background-color: #0e3d5f">OPENINGS</h2>
        </div>
        <div class="contenedor-favoritos">
            @isset($openings)
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
            @endisset
        </div>
        <br>
        <div>
            <h2 class="text-light" style="background-color: #0e3d5f">ENDINGS</h2>
        </div>
        <div class="contenedor-favoritos">
            @isset($endings)
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
            @endisset
        </div>
    </div>
@endsection
