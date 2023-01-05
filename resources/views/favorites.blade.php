@if (Request::is('favorites'))
    <title>Favorites Openings & Endings</title>
    <meta title="Favorites Openings & Endings">
@endif
@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="background-color: #0e3d5f">
            <h2 class="text-light">OPENINGS</h2>
        </div>
        <div class="contenedor-favoritos">
            @foreach ($openings as $post)
            <div class="tarjeta">
                <div class="textos">
                    <div class="tarjeta-header text-light">
                        <span class="text-shadow text-uppercase post-titles">{{ $post->title }}</span>
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
        <hr>

        <div style="background-color: #0e3d5f">
            <h2 class="text-light">ENDINGS</h2>
        </div>
        <div class="contenedor-favoritos">
            @foreach ($endings as $post)
            <div class="tarjeta">
                <div class="textos">
                    <div class="tarjeta-header text-light">
                        <span class="text-shadow text-uppercase post-titles">{{ $post->title }}</span>
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
@endsection
