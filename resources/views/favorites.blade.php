@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="background-color: #0e3d5f">
            <h1 class="text-light">OPENINGS</h1>
        </div>
        <div class="contenedor-favoritos">
            @foreach ($openings as $opening)
                <div onclick="location.href='{{ route('showbyslug', [$opening->id, $opening->slug]) }}';" class="tarjeta" style="background-image: url('{{ asset('/storage/thumbnails/' . $opening->thumbnail) }}')">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h4 class="text-shadow text-uppercase">{{ $opening->title }}</h4>
                        </div>
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
                                        <button class="btn btn-sm btn-success"><i class="fa fa-heart"></i>
                                        </button>
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
                                        {{ round($opening->averageRating) }}
                                    @endif
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <hr>

        <div style="background-color: #0e3d5f">
            <h1 class="text-light">ENDINGS</h1>
        </div>
        <div class="contenedor-favoritos">
            @foreach ($endings as $ending)
                <div class="tarjeta"
                    style="background-image: url('{{ asset('/storage/thumbnails/' . $ending->thumbnail) }}')">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h4 class="text-shadow text-uppercase">{{ $ending->title }}</h4>
                        </div>
                        <div class="tarjeta-footer">
                            <a href="{{ route('show', $ending->id) }}" class="btn btn-sm btn-primary"> Ver</a>
                            @auth
                                @if ($ending->liked())
                                    <form action="{{ route('unlike.post', $ending->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="fa fa-heart"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('like.post', $ending->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-sm btn-success"><i class="fa fa-heart"></i>
                                        </button>
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
            @endforeach
        </div>
    </div>
@endsection
