@extends('layouts.app')

@section('content')
<div class="contenedor">
    <div class="contenedor-tarjetas">
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
                                    <button class="btn btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('like.post', $post->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                        </svg>
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
