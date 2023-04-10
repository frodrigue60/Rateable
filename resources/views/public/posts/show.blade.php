@extends('layouts.app')
@section('meta')
    <title>
        {{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}</title>
    <meta name="title" content="{{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}">

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
    {{-- <meta property="og:locale" content="es_MX"> --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}">

    @if (isset($post->song->song_romaji))
        @if (isset($post->artist->name))
            <meta name="og:description"
                content="Song: {{ $post->song->song_romaji }} - Artist: {{ $post->artist->name }}">
        @else
            <meta name="og:description" content="Song: {{ $post->song->song_romaji }} - Artist: N/A">
        @endif
    @else
        @if (isset($post->song->song_en))
            @if (isset($post->artist->name))
                <meta name="og:description"
                    content="Song: {{ $post->song->song_en }} - Artist: {{ $post->artist->name }}">
            @else
                <meta name="og:description" content="Song: {{ $post->song->song_en }} - Artist: N/A">
            @endif
        @endif
    @endif
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
    <div>
        <span id="media-query"></span>
    </div>
    <div class="container text-light">
        <div class="banner-anime" style="background-image: url({{ asset('/storage/anime_banner/' . $post->banner) }});">
            <div class="gradient"></div>
            <div class="post-info">
                <img class="thumbnail-post" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}" alt="">

                <div class="post-data-anime">
                    <div class="title-post">
                        <span>{{ $post->title }}</span>
                    </div>
                    <div class="description-post">
                        <p>{!! $post->description !!}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container text-light mt-2 container-songs">
            <div class="themes">
                <div>
                    <h3>Openings</h3>
                </div>
                <div>
                    @isset($openings)
                        @foreach ($openings->sortByDesc('theme_num') as $song)
                            <div class="post-song">
                                <div style="overflow: hidden">
                                    <div class="theme-info">
                                        <strong>
                                            <i class="fa fa-music" aria-hidden="true"></i>
                                            <a class="no-deco text-light"
                                                href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">
                                                @if (isset($song->song_romaji))
                                                    {{ $song->song_romaji }}
                                                @else
                                                    @if (isset($song->song_en))
                                                        {{ $song->song_en }}
                                                    @else
                                                        @if (isset($song->song_jp))
                                                            {{ $song->song_jp }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    @endif
                                                @endif
                                            </a>
                                        </strong>
                                        <span
                                            style="padding-left: 5px; padding-right: 5px;">({{ $song->suffix != null ? $song->suffix : $song->type }})</span>
                                    </div>
                                    @isset($song->artist->name)
                                        <div>
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            <strong>
                                                <a class="no-deco text-light"
                                                    href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}">
                                                    {{ $song->artist->name }}
                                                    @if ($song->artist->name_jp)
                                                        ({{ $song->artist->name_jp }})
                                                    @endif
                                                </a>
                                            </strong>
                                        </div>
                                    @endisset
                                </div>
                                <div style="display: inline;
                                align-self: center;">
                                    <span>{{ $song->averageRating != null ? $song->averageRating / 1 : 'N/A' }}</span>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </div>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>
            <div class="themes">
                <div>
                    <h3>Endings</h3>
                </div>
                <div>
                    @isset($endings)
                        @foreach ($endings->sortByDesc('theme_num') as $song)
                            <div class="post-song">
                                <div style="overflow: hidden">
                                    <div class="theme-info">
                                        <strong>
                                            <i class="fa fa-music" aria-hidden="true"></i>
                                            <a class="no-deco text-light"
                                                href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">
                                                @if (isset($song->song_romaji))
                                                    {{ $song->song_romaji }}
                                                @else
                                                    @if (isset($song->song_en))
                                                        {{ $song->song_en }}
                                                    @else
                                                        @if (isset($song->song_jp))
                                                            {{ $song->song_jp }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    @endif
                                                @endif
                                            </a>
                                        </strong>
                                        <span
                                            style="padding-left: 5px; padding-right: 5px;">({{ $song->suffix != null ? $song->suffix : $song->type }})</span>
                                    </div>
                                    @isset($song->artist->name)
                                        <div>
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            <strong>
                                                <a class="no-deco text-light"
                                                    href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}">
                                                    {{ $song->artist->name }}
                                                    @if ($song->artist->name_jp)
                                                        ({{ $song->artist->name_jp }})
                                                    @endif
                                                </a>
                                            </strong>
                                        </div>
                                    @endisset
                                </div>
                                <div style="display: inline;
                            align-self: center;">
                                    <span>{{ $song->averageRating != null ? $song->averageRating / 1 : 'N/A' }}</span>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </div>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>

        </div>
    @endsection
