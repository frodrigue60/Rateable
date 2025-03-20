@extends('layouts.app')
{{-- @section('meta')
    <title>
        {{ $post->title }} {{ $post->slug != null ? $post->slug : $post->type }}</title>
    <meta name="title"
        content="{{ $post->title }} {{ $post->slug != null ? $post->slug : $post->type }}">

    <link rel="stylesheet" href="{{ asset('/resources/css/fivestars.css') }}">

    @if (isset($post->song->song_romaji))
        @if (isset($post->artist->name))
            <meta name="description" content="Song: {{ $post->song->song_romaji }} - Artist: {{ $post->artist->name }}">
        @else
            <meta name="description" content="Song: {{ $post->song->song_romaji }} - Artist: N/A">
        @endif
    @else
        @if (isset($post->song->song_en))
            @if (isset($post->artist->name))
                <meta name="description" content="Song: {{ $post->song->song_en }} - Artist: {{ $post->artist->name }}">
            @else
                <meta name="description" content="Song: {{ $post->song->song_en }} - Artist: N/A">
            @endif
        @endif
    @endif

    <meta name="robots" content="index, follow, max-image-preview:standard">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }} {{ $post->slug != null ? $post->slug : $post->type }}">

    @if (isset($post->song->song_romaji))
        @if (isset($post->artist->name))
            <meta name="og:description" content="Song: {{ $post->song->song_romaji }} - Artist: {{ $post->artist->name }}">
        @else
            <meta name="og:description" content="Song: {{ $post->song->song_romaji }} - Artist: N/A">
        @endif
    @else
        @if (isset($post->song->song_en))
            @if (isset($post->artist->name))
                <meta name="og:description" content="Song: {{ $post->song->song_en }} - Artist: {{ $post->artist->name }}">
            @else
                <meta name="og:description" content="Song: {{ $post->song->song_en }} - Artist: N/A">
            @endif
        @endif
    @endif
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="article:section" content="{{ $post->type == 'OP' ? 'Opening' : 'Ending' }}">
    
    <meta property="og:image" content="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}" alt="{{ $post->title }}">
    <meta property="og:image:secure_url" content="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
        alt="{{ $post->title }}">
    <meta property="og:image:width" content="460">
    <meta property="og:image:height" content="650">
    <meta property="og:image:alt" content="{{ $post->title }} {{ $post->slug != null ? $post->slug : $post->type }}">
    <meta property="og:image:type" content="image/webp">
    
@endsection --}}
@section('content')
    <div class="container text-light">
        <div class="artist-menu py-4">
                <div class="artist-item">
                    <a class="artist-link" href="#special">[0-9]</a>
                </div>
                @foreach ($characters as $item)
                    <div class="artist-item">
                        <a class="artist-link" href="#{{ $item }}">{{ $item }}</a>
                    </div>
                @endforeach
        </div>
        <div class="py-4">
            <div class="artist-char">
                <h3 id="special">[0-9]</h3>
            </div>
            <div class="artist-list py-2">
                @foreach ($artists as $artist)
                    @if (!preg_match('/^[a-zA-Z]/', $artist->name))
                        <div class="artist-list-item">
                            <a class="no-deco"
                                href="{{ $artist->url }}" target="_blank" rel="noopener">{{ $artist->name }}</a>
                        </div>
                    @endif
                @endforeach
            </div>
            @foreach ($characters as $char)
                <div class="artist-char">
                    <h3 id="{{ $char }}">{{ $char }}</h3>
                </div>
                <div class="artist-list py-2">

                    @foreach ($artists as $artist)
                    @php
                        $countThemes = count($artist->songs)
                    @endphp
                        @if (Str::startsWith($artist->name, $char))
                            <div class="artist-list-item">
                                <a class="no-deco"
                                    href="{{ $artist->url }}" target="_blank" rel="noopener">{{ $artist->name }} {{"(".$countThemes.")"}}</a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
