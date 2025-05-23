@extends('layouts.app')

@section('content')
    <div class="container">
        <div
            style="
        display: grid;
    grid-template-columns: repeat(auto-fill,150px);
    gap: 15px;
    ">
            @foreach ($posts as $post)
                <article class="tarjeta">
                    <div class="textos">
                        <div class="tarjeta-header ">
                            <h3 class="text-shadow text-uppercase post-titles">{{ $post->title->romaji }}</h3>
                        </div>
                        @php
                            //$anirank_id = [];
                            $anirank_id = ['id'=>$post->id];
                        @endphp
                        <a class="no-deco" href="{{ route('get.by.id', $anirank_id) }}">
                            <img class="thumb" src="{{ $post->coverImage->extraLarge }}" alt="{{ $post->title->romaji }}"
                                title="{{ $post->title->romaji }}">
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endsection
