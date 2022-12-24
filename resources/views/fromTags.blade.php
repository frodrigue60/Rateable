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
                @foreach ($openings as $opening)
                    <a class="no-deco" href="{{ route('showbyslug', [$opening->id, $opening->slug]) }}">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $opening->title }}</h6>
                                </div>
                                <img id="thumb" src="{{ asset('/storage/thumbnails/' . $opening->thumbnail) }}"
                                    alt="{{ $opening->title }}">
                                <div class="tarjeta-footer">
                                    {{-- <a href="{{ route('show', $opening->id) }}" class="btn btn-sm btn-primary">Show</a> --}}
                                    @auth
                                        @if ($opening->liked())
                                            <form action="{{ route('unlike.post', $opening->id) }}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-danger"><i class="fa fa-heart"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('like.post', $opening->id) }}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-success"><i class="fa fa-heart"></i></button>
                                            </form>
                                        @endif
                                    @endauth
                                    <button class="btn btn-sm btn-warning">
                                        @if (isset($score_format))
                                            @switch($score_format)
                                                @case('POINT_100')
                                                    {{ round($opening->averageRating) }}
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    {{ round($opening->averageRating / 10, 1) }}
                                                    <i class="fa fa-star"></i>
                                                @break

                                                @case('POINT_10')
                                                    {{ round($opening->averageRating / 10) }} <i class="fa fa-star"></i>
                                                @break

                                                @case('POINT_5')
                                                    {{ round($opening->averageRating / 20) }} <i class="fa fa-star"></i>
                                                @break

                                                @default
                                                    {{ round($opening->averageRating) }}
                                            @endswitch
                                        @else
                                            {{ round($opening->averageRating / 10, 1) }}
                                            <i class="fa fa-star"></i>
                                        @endif
                                    </button>
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
                @foreach ($endings as $ending)
                    <a class="no-deco" href="{{ route('showbyslug', [$ending->id, $ending->slug]) }}">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $ending->title }}</h6>
                                </div>
                                <img id="thumb" src="{{ asset('/storage/thumbnails/' . $ending->thumbnail) }}"
                                    alt="{{ $ending->title }}">
                                <div class="tarjeta-footer">
                                    {{-- <a href="{{ route('show', $ending->id) }}" class="btn btn-primary"> Ver</a> --}}
                                    @auth
                                        @if ($ending->liked())
                                            <form action="{{ route('unlike.post', $ending->id) }}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-danger"><i class="fa fa-heart"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('like.post', $ending->id) }}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-success"><i class="fa fa-heart"></i></button>
                                            </form>
                                        @endif
                                    @endauth
                                    <button class="btn btn-sm btn-warning">
                                        @if (isset($score_format))
                                            @switch($score_format)
                                                @case('POINT_100')
                                                    {{ round($ending->averageRating) }}
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    {{ round($ending->averageRating / 10, 1) }} <i class="fa fa-star"></i>
                                                @break

                                                @case('POINT_10')
                                                    {{ round($ending->averageRating / 10) }} <i class="fa fa-star"></i>
                                                @break

                                                @case('POINT_5')
                                                    {{ round($ending->averageRating / 20) }} <i class="fa fa-star"></i>
                                                @break

                                                @default
                                                    {{ round($ending->averageRating) }}
                                            @endswitch
                                        @else
                                            {{ round($ending->averageRating) }}
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endisset
        </div>
    </div>
@endsection
