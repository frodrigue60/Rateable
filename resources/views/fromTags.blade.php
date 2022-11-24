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

                            <button class="btn btn-primary">{{ $post->averageRating / 10 }}

                                <svg style="color: white" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"
                                        fill="white"></path>
                                </svg>

                            </button>
                        </div>


                    </div>
                </div>
            @endforeach


        </div>

        
    </div>
@endsection
