
@extends('layouts.app')
@section('meta')
    @if (Request::is('favorites'))
            <title>Favorites Openings & Endings</title>
            <meta title="Favorites Openings & Endings">
    @endif
@endsection
@section('content')
    <div class="container">
        <section>
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light mb-0">OPENINGS</h2>
            </div>
            <div class="contenedor-favoritos">
                @foreach ($openings as $post)
                <article class="tarjeta">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                        </div>
                            <div class="{{ $post->type === "op" ? "tag" : "tag2" }}">
                                <span class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
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
        <hr>
        <section>
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light mb-0">ENDINGS</h2>
            </div>
            <div class="contenedor-favoritos">
                @foreach ($endings as $post)
                <article class="tarjeta">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                        </div>
                            <div class="{{ $post->type === "op" ? "tag" : "tag2" }}">
                                <span class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
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
    </div>
@endsection
