@extends('layouts.app')
@section('meta')

    @php
        if ($post->thumbnail != null && Storage::disk('public')->exists($post->thumbnail)) {
            $thumbnail_url = Storage::url($post->thumbnail);
        } else {
            $thumbnail_url = $post->thumbnail_src;
        }

        if ($post->banner != null && Storage::disk('public')->exists($post->banner)) {
            $banner_url = Storage::url($post->banner);
        } else {
            $banner_url = $post->banner_src;
        }
    @endphp
    <title>
        {{ $post->title }} {{ $post->slug != null ? $post->slug : $post->type }}</title>
    <meta name="title" content="{{ $post->title }} {{ $post->slug != null ? $post->slug : $post->type }}">



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
    <meta property="og:title" content="{{ $post->title }} {{ $post->slug != null ? $post->slug : $post->type }}">

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
    <meta property="og:image" content="{{ $thumbnail_url }}" alt="{{ $post->title }}">
    <meta property="og:image:secure_url" content="{{ $thumbnail_url }}" alt="{{ $post->title }}">
    <meta property="og:image:width" content="460">
    <meta property="og:image:height" content="650">
    <meta property="og:image:alt" content="{{ $post->title }} {{ $post->slug != null ? $post->slug : $post->type }}">
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
    <div class="col-8 mx-auto">
        {{-- <div class="banner-anime" style="background-image: url({{ $banner_url }});">
            <div class="gradient"></div>
            <div class="post-info">
                <img class="thumbnail-post" src="{{ $thumbnail_url }}" alt="">
                <div class="post-data-anime">
                    <div class="title-post">
                        <span>{{ $post->title }}</span>
                    </div>
                    <div class="description-post">
                        <p>{!! $post->description !!}</p>
                    </div>
                </div>
            </div>
        </div> --}}
        <div>
            <h1 class="section-header fs-4">{{ $post->title }}</h1>
        </div>
        <div class="row mx-auto">
            <!-- ANIME INFO -->
            <div class="col-sm-12 col-md-4 col-lg-3  p-2 text-center">
                <div class="mb-3">
                    <img class="rounded-1 w-100" src="{{ $thumbnail_url }}" alt="" style="max-width: 300px">
                </div>
                <div>
                    <h5 class="fw-bold">Title</h5>
                    {{ $post->title }}
                </div>
                <div>
                    <h5 class="fw-bold">Release</h5>
                    <span>{{ $post->season->name }}</span> <span>{{ $post->year->name }}</span>
                </div>
                <div>
                    <h5 class="fw-bold">Format</h5>
                    <span>{{ 'format' }}</span>
                </div>
                <div>
                    <h5 class="fw-bold">Studios</h5>
                    <span>{{ 'studios' }}</span>
                </div>
                <div>
                    <h5 class="fw-bold">External Links</h5>
                    <ul class="list-unstyled">
                        @for ($i = 0; $i < 8; $i++)
                            <li>Link {{ $i }}</li>
                        @endfor
                    </ul>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-9 p-2">
                <!-- DESCRIPTION -->
                <h2 class="fs-4">Synopsis</h2>
                <div class=" rounded-1 mb-3">
                        <div class="description">
                            {!! $post->description !!}
                        </div>
                </div>
                <!--OPENINGS-->
                <div class="col mb-2">
                    <div>
                        <h3 class="fs-4">Openings</h3>
                    </div>
                    <div>
                        @isset($openings)
                            @if ($openings->count() != null)
                                @foreach ($openings->sortBy('theme_num') as $song)
                                    <!--CARD SONG V1-->
                                    {{-- @include('partials.posts.show.song-card-v1') --}}
                                    <!--CARD SONG V2-->
                                    @include('partials.posts.show.song-card-v2')
                                @endforeach
                            @else
                                <div class="d-flex flex-column align-items-center text-center">
                                    <figure class="mb-0">
                                        <img style="max-width:200px" src="{{ asset('resources/images/sad-cat-5.png') }}"
                                            alt="">
                                    </figure>
                                    <h4 class="">Nothing here</h4>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
                <!--ENDINGS-->
                <div class="col mb-2">
                    <div>
                        <h3 class="fs-4">Endings</h3>
                    </div>
                    <div>
                        @isset($endings)
                            @if ($endings->count() != null)
                                @foreach ($endings->sortBy('theme_num') as $song)
                                    <!--CARD SONG V1-->
                                    {{-- @include('partials.posts.show.song-card-v1') --}}
                                    <!--CARD SONG V2-->
                                    @include('partials.posts.show.song-card-v2')
                                @endforeach
                            @else
                                <div class="d-flex flex-column align-items-center text-center">
                                    <figure class="mb-0">
                                        <img style="max-width:200px" src="{{ asset('resources/images/sad-cat-5.png') }}"
                                            alt="">
                                    </figure>
                                    <h4 class="">Nothing here</h4>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
