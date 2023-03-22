@extends('layouts.app')
@section('meta')
    <title>
        {{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}</title>
    <meta name="title" content="{{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}">

    <link rel="stylesheet" href="{{ asset('/resources/css/fivestars.css') }}">

    {{-- @if ($post->song->song_romaji != null and $post->artist->name != null)
        <meta name="description" content="Song: {{ $post->song->song_romaji }} - Artist: {{ $post->artist->name }}">
    @else
        <meta name="description" content="Song: N/A - Artist: N/A">
    @endif --}}

    <meta name="robots" content="index, follow, max-image-preview:standard">
    <link rel="canonical" href="{{ url()->current() }}">
    {{-- <meta property="og:locale" content="es_MX"> --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}">
    {{-- @if ($post->song->song_romaji != null and $post->artist->name != null)
        <meta property="og:description" content="{{ $post->song->song_romaji }} - {{ $post->artist->name }}">
    @else
        <meta property="og:description" content="Song: N/A - Artist: N/A">
    @endif --}}
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="article:section" content="{{ $post->type == 'OP' ? 'Opening' : 'Ending' }}">
    {{-- <meta property="og:updated_time" content="2022-09-04T20:03:37-05:00"> --}}
    <meta property="og:image" content="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}" alt="{{ $post->title }}">
    <meta property="og:image:secure_url" content="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
        alt="{{ $post->title }}">
    <meta property="og:image:width" content="460">
    <meta property="og:image:height" content="650">
    <meta property="og:image:alt" content="{{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}">
    <meta property="og:image:type" content="image/webp">
    {{-- <meta property="article:published_time" content="2022-09-04T20:03:32-05:00">
    <meta property="article:modified_time" content="2022-09-04T20:03:37-05:00"> --}}

    {{-- <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="The Idolmaster Cinderella Girls U149: se estrenará en 2023 - Ayamari Network">
    <meta name="twitter:description" content="">
    <meta name="twitter:image" content="https://ayamari.me/wp-content/uploads/2022/09/THE-iDOLMASTER.png">
    <meta name="twitter:label1" content="Written by">
    <meta name="twitter:data1" content="Akaza">
    <meta name="twitter:label2" content="Time to read">
    <meta name="twitter:data2" content="2 minutos"> --}}
@endsection
@section('content')
    @if ((Auth::User() && Auth::User()->isEditor()) || Auth::User()->isAdmin())
        <div class="container mb-4">
            <div class="post-data">
                <div class="preview-thumbnail">
                    <img src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}" alt="" style="width: 150px">
                </div>
                <div class="text-light">
                    <p>Title: {{ $post->title }}</p>
                    <p>Tags: @foreach ($post->tags as $item)
                            {{ $item->name }}
                        @endforeach
                    </p>
                    <p>Type: {{ $post->type }}</p>
                    <p>Theme No. {{ $post->themeNum != null ? $post->themeNum : 'N/A' }}</p>
                    <p>
                        @isset($post->song->song_romaji)
                        <p>Song title (romaji): <strong>{{ $post->song->song_romaji }}</strong></p>
                    @endisset
                    @isset($post->song->song_jp)
                        <p>Song title (JP): <strong>{{ $post->song->song_jp }}</strong></p>
                    @endisset
                    @isset($post->song->song_en)
                        <p>Song title (EN): <strong>{{ $post->song->song_en }}</strong></p>
                    @endisset
                    @isset($post->artist->name)
                        <p>Song artist: <strong><a href="{{ route('artist.show', $artist->name_slug) }}"
                                    class="no-deco">{{ $post->artist->name }}</a></strong></p>
                    @endisset
                    @isset($post->artist->name_jp)
                        <p>Song artist (JP): <strong><a href="{{ route('artist.show', $artist->name_slug) }}"
                                    class="no-deco">{{ $post->artist->name_jp }}</a></strong></p>
                    @endisset
                    </p>
                    <p>First link: {{ $post->ytlink != null ? 'true' : 'N/A' }}</p>
                    <p>Second link: {{ $post->scndlink != null ? 'true' : 'N/A' }}</p>
                    <p>thumbnail: {{ $post->imageSrc != null ? 'from url' : 'from file' }} </p>

                </div>
            </div>
            <div>
                <div id="videos">
                    <div class="video-container ratio ratio-16x9">
                        {!! $post->ytlink !!}
                    </div>
                    <div class="video-container ratio ratio-16x9">
                        {!! $post->scndlink !!}
                    </div>
                </div>
            </div>

            @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                <div class="container d-flex justify-content-center m-2">
                    @if ($post->status == null)
                        <button disabled="disabled" class="btn btn-secondary">N/A</button>
                    @endif
                    @if ($post->status == 'stagged')
                            <form action="{{ route('admin.post.approve', $post->id) }}" method="post">
                                @csrf
                                <button class="btn btn-warning"> <i class="fa fa-clock-o" aria-hidden="true">
                                        {{ $post->id }}</i></button>
                            </form>
                    @endif
                    @if ($post->status == 'published')
                            <form action="{{ route('admin.post.unapprove', $post->id) }}" method="post">
                                @csrf
                                <button class="btn btn-primary"> <i class="fa fa-check" aria-hidden="true">
                                        {{ $post->id }}</i></button>
                            </form>
                    @endif
                        <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-success"><i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ $post->id }}</a>
                        <a href="{{ route('admin.post.destroy', $post->id) }}" class="btn btn-danger"><i
                            class="fa fa-trash" aria-hidden="true"></i>
                        {{ $post->id }}</a>
                </div>
            @endif
        </div>
    @endif
@endsection
