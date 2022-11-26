@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="contenedor-favoritos">
            @foreach ($posts as $post)
                <div class="tarjeta" style="background-image: url('{{ $post->imagesrc }}')">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h4 class="text-shadow text-uppercase">{{ $post->title }}</h4>
                        </div>
                        <div class="tarjeta-footer">
                            <a href="{{ route('show', $post->id) }}" class="btn btn-primary"> Ver</a>
                            @auth
                                @if ($post->liked())
                                    <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-danger"><i class="fa fa-heart"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('like.post', $post->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-success"><i class="fa fa-heart"></i>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                            <button class="btn btn-warning">{{ $post->averageRating / 10 }}</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endsection
