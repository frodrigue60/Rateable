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
        <div>
            <h1 class="text-light">Directory</h1>
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

        <div style="display: flex;justify-content: center;margin: 10px;">
            {{ $posts->links() }}
        </div>

    </div>
@endsection
