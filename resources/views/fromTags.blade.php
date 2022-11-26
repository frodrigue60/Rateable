@extends('layouts.app')

@section('content')
    <div class="container">
        <div>
            <h2 class="text-light" style="background-color: #0e3d5f">OPENINGS</h2>
        </div>
        <div class="contenedor-favoritos">
            @foreach ($openings as $opening)
                <div class="tarjeta" style="background-image: url('{{ $opening->imagesrc }}')">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h4 class="text-shadow text-uppercase">{{ $opening->title }}</h4>
                        </div>
                        <div class="tarjeta-footer">
                            <a href="{{ route('show', $opening->id) }}" class="btn btn-primary"> Ver</a>
                            @auth
                                @if ($opening->liked())
                                    <form action="{{ route('unlike.post', $opening->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-danger"><i class="fa fa-heart"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('like.post', $opening->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-success"><i class="fa fa-heart"></i></button>
                                    </form>
                                @endif
                            @endauth
                            <button class="btn btn-primary">{{ $opening->averageRating / 10 }} <i
                                    class="fa fa-star"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <br>
        <div>
            <h2 class="text-light" style="background-color: #0e3d5f">ENDINGS</h2>
        </div>
        <div class="contenedor-favoritos">
            @foreach ($endings as $ending)
                <div class="tarjeta" style="background-image: url('{{ $ending->imagesrc }}')">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h4 class="text-shadow text-uppercase">{{ $ending->title }}</h4>
                        </div>
                        <div class="tarjeta-footer">
                            <a href="{{ route('show', $ending->id) }}" class="btn btn-primary"> Ver</a>
                            @auth
                                @if ($ending->liked())
                                    <form action="{{ route('unlike.post', $ending->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-danger"><i class="fa fa-heart"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('like.post', $ending->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-success"><i class="fa fa-heart"></i></button>
                                    </form>
                                @endif
                            @endauth
                            <button class="btn btn-primary">{{ $ending->averageRating / 10 }} <i
                                    class="fa fa-star"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
