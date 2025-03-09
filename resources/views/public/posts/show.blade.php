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
    <meta property="og:image:secure_url" content="{{ $thumbnail_url }}"
        alt="{{ $post->title }}">
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
    <div class="container text-light">
        <div class="banner-anime" style="background-image: url({{ $banner_url }});">
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
        </div>

        {{-- @auth
            @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                <div class="container d-flex pt-2 justify-content-center gap-2">

                    @if ($post->status == 'stagged')
                        <form action="{{ route('admin.post.approve', $post->id) }}" method="post">
                            @csrf
                            <button class="btn btn-warning btn-sm"> <i class="fa fa-clock" aria-hidden="true">
                                    {{ $post->id }}</i></button>
                        </form>
                    @endif
                    @if ($post->status == 'published')
                        <form action="{{ route('admin.post.unapprove', $post->id) }}" method="post">
                            @csrf
                            <button class="btn btn-primary btn-sm"> <i class="fa fa-check" aria-hidden="true">
                                    {{ $post->id }}</i></button>
                        </form>
                    @endif

                    <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-success btn-sm"><i
                            class="fa-solid fa-pencil"></i></a>
                    <a href="{{ route('admin.post.destroy', $post->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"
                            aria-hidden="true"></i></a>
                    <a class="btn btn-sm btn-primary" href="{{ route('song.post.create', $post->id) }}"><i
                            class="fa-solid fa-plus"></i></a>
                    <a class="btn btn-sm btn-success" href="{{ route('song.post.manage', $post->id) }}"><i
                            class="fa-solid fa-list-check"></i></a>

                </div>
            @endif
        @endauth --}}

        <div class="container text-light mt-2 container-songs gap-4">
            <div class="themes">
                <div>
                    <h3>Openings</h3>
                </div>
                <div>
                    @isset($openings)
                        @if ($openings->count() != null)
                            @foreach ($openings->sortBy('theme_num') as $song)
                                @php
                                    $songName = 'N/A';
                                    $themeSuffix = $song->slug != null ? $song->slug : $song->type;
                                    $songScore = $song->averageRating != null ? $song->averageRating / 1 : 'N/A';

                                    if (isset($song->song_romaji)) {
                                        $songName = $song->song_romaji;
                                    } else {
                                        if (isset($song->song_en)) {
                                            $songName = $song->song_en;
                                        } else {
                                            if (isset($song->song_jp)) {
                                                $songName = $song->song_jp;
                                            } else {
                                                $songName = 'N/A';
                                            }
                                        }
                                    }
                                @endphp
                                <div class="bg-dark p-2 d-flex flex-column gap-1 my-2 rounded-2">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="p-0 m-0">{{ $themeSuffix }} {{ $song->themeNum }}</h4>
                                        {{-- <h4 class="p-0 m-0">{{ $songScore }} <i class="fa-solid fa-star"></i></h4> --}}
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-light text-decoration-none"><span class="pe-1"><i
                                                    class="fa-solid fa-music"></i></span> {{ $songName }}</span>
                                        @isset($song->artists)
                                            <div>
                                                <span class="pe-2"><i class="fa-solid fa-user"></i></span>
                                                @foreach ($song->artists as $index => $item)
                                                    @php
                                                        $artistShowRoute = route('artist.show', [
                                                            $item->id,
                                                            $item->name_slug,
                                                        ]);
                                                        if ($item->name_jp != null) {
                                                            $artistName = $item->name . ' (' . $item->name_jp . ')';
                                                        } else {
                                                            $artistName = $item->name;
                                                        }
                                                    @endphp
                                                    <a class="text-light text-decoration-none"
                                                        href="{{ $artistShowRoute }}">{{ $artistName }}</a>
                                                    @if ($index < count($song->artists) - 1)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endisset
                                    </div>
                                    <hr class="p-0 m-0">
                                    @isset($song->songVariants)
                                        @if ($song->songVariants->count() != 0)
                                            <div class="d-flex flex-column gap-2 mx-2 py-2">
                                                @foreach ($song->songVariants->sortBy('version') as $variant)
                                                    @php
                                                        $song_id = $variant->song->id;
                                                        $slug = $variant->song->post->slug;
                                                        if ($variant->song->slug != null) {
                                                            $suffix = $variant->song->slug;
                                                        } else {
                                                            $suffix = $variant->song->type;
                                                        }
                                                        $version = $variant->version_number;

                                                    @endphp
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <a class="text-decoration-none text-light"
                                                            href="{{ $variant->url }}"><span>Version
                                                                {{ $variant->version_number }}</span></a>
                                                        <div class="d-flex flex-row align-items-center gap-4">
                                                            <div>
                                                                @if (isset($variant->score))
                                                                    <span>{{ $variant->score }} <i
                                                                            class="fa-solid fa-star"></i></span>
                                                                @else
                                                                    <span>N/A <i class="fa-solid fa-star"></i></span>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <a class="btn btn-sm btn-primary rounded-4"
                                                                    href="{{ $variant->url }}">{{ 'Show' }}
                                                                    <i class="fa-solid fa-play"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            {{-- <p class="text-light">{{$variant->score}}</p> --}}
                                        @else
                                            <h4 class="text-center">No videos</h4>
                                        @endif
                                    @endisset
                                </div>
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
            <div class="themes">
                <div>
                    <h3>Endings</h3>
                </div>
                <div>
                    @isset($endings)
                        @if ($endings->count() != null)
                            @foreach ($endings->sortBy('theme_num') as $song)
                                @php
                                    $songName = 'N/A';
                                    $themeSuffix = $song->slug != null ? $song->slug : $song->type;
                                    $songScore = $song->averageRating != null ? $song->averageRating / 1 : 'N/A';

                                    if (isset($song->song_romaji)) {
                                        $songName = $song->song_romaji;
                                    } else {
                                        if (isset($song->song_en)) {
                                            $songName = $song->song_en;
                                        } else {
                                            if (isset($song->song_jp)) {
                                                $songName = $song->song_jp;
                                            } else {
                                                $songName = 'N/A';
                                            }
                                        }
                                    }
                                @endphp
                                <div class="bg-dark p-2 d-flex flex-column gap-1 my-2 rounded-2">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="p-0 m-0">{{ $themeSuffix }} {{ $song->themeNum }}</h4>
                                        {{-- <h4 class="p-0 m-0">{{ $songScore }} <i class="fa-solid fa-star"></i></h4> --}}
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-light text-decoration-none"><span class="pe-1"><i
                                                    class="fa-solid fa-music"></i></span> {{ $songName }}</span>
                                        @isset($song->artists)
                                            <div class="d-flex align-items-center">
                                                <span class="pe-2"><i class="fa-solid fa-user"></i></span>
                                                @foreach ($song->artists as $index => $item)
                                                    @php
                                                        $artistShowRoute = route('artist.show', [
                                                            $item->id,
                                                            $item->name_slug,
                                                        ]);
                                                        if ($item->name_jp != null) {
                                                            $artistName = $item->name . ' (' . $item->name_jp . ')';
                                                        } else {
                                                            $artistName = $item->name;
                                                        }
                                                    @endphp
                                                    <a class="text-light text-decoration-none"
                                                        href="{{ $artistShowRoute }}">{{ $artistName }}</a>
                                                    @if ($index < count($song->artists) - 1)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endisset
                                    </div>
                                    <hr class="p-0 m-0">
                                    @isset($song->songVariants)
                                        @if ($song->songVariants->count() != 0)
                                            <div class="d-flex flex-column gap-3 mx-2 py-2">
                                                @foreach ($song->songVariants->sortBy('version') as $variant)
                                                    @php
                                                        $song_id = $variant->song->id;
                                                        $slug = $variant->song->post->slug;
                                                        if ($variant->song->slug != null) {
                                                            $suffix = $variant->song->slug;
                                                        } else {
                                                            $suffix = $variant->song->type;
                                                        }
                                                        $version = $variant->version_number;

                                                    @endphp
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <a class="text-decoration-none text-light"
                                                            href="{{ $variant->url }}"><span>Version
                                                                {{ $variant->version_number }}</span></a>
                                                        <div class="d-flex flex-row align-items-center gap-4">
                                                            <div>
                                                                @if (isset($variant->score))
                                                                    <span>{{ $variant->score }} <i
                                                                            class="fa-solid fa-star"></i></span>
                                                                @else
                                                                    <span>N/A <i class="fa-solid fa-star"></i></span>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <a class="btn btn-sm btn-primary rounded-4"
                                                                    href="{{ $variant->url }}">{{ 'Show' }}
                                                                    <i class="fa-solid fa-play"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <h4 class="text-center">No videos</h4>
                                        @endif
                                    @endisset
                                </div>
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
    @endsection
