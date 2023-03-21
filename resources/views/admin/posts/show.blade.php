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
                        <p>Song artist: <strong><a href="{{ route('from.artist', $artist->name_slug) }}"
                                    class="no-deco">{{ $post->artist->name }}</a></strong></p>
                    @endisset
                    @isset($post->artist->name_jp)
                        <p>Song artist (JP): <strong><a href="{{ route('from.artist', $artist->name_slug) }}"
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
    <div class="container">
        <h1 class="text-light text-center" hidden>{{ $post->title }}</h1>
        <div class="row justify-content-center">
            <div class="card-video card ">
                <div class="card-body ratio ratio-16x9" id="id_iframe">
                    {{-- comment <iframe id="id_iframe" src="{{ $post->ytlink }}" title="" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture;"
                        allowfullscreen>
                    </iframe> --}}
                    {!! $post->ytlink !!}
                </div>
                <div class="card-footer">
                    <h1 class="text-light show-view-title mb-0">{{ $post->title }}
                        {{ $post->suffix != null ? $post->suffix : $post->type }}</h1>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-start">
                    <div class="text-light">
                        <button class="button-border-1">{{ $post->viewCount }} <i class="fa fa-eye"></i></button>
                        <button class="button-border-1">{{ $post->averageRating / 1 }} <i class="fa fa-star"></i></button>
                    </div>
                    <div class="d-flex btn-group-show">
                        <button class="button2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            Rate
                            <i class="fa fa-star"></i></button>
                        @guest
                            <button class="button2" disabled id="like"> <i class="fa fa-heart"></i></button>
                        @endguest
                        @auth
                            @if ($post->liked())
                                <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                    @csrf
                                    <button class="button-liked" id="like"> <i class="fa fa-heart"></i></button>
                                </form>
                            @else
                                <form action="{{ route('like.post', $post->id) }}" method="post">
                                    @csrf
                                    <button class="button2" id="like"><i class="fa fa-heart"></i></button>
                                </form>
                            @endif
                        @endauth
                        <div class="btn-group" role="group">
                            <button type="button" class="button2 dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Server
                            </button>
                            <ul class="dropdown-menu" id="button-group">
                                @if ($post->ytlink != null)
                                    <li><button class="dropdown-item" value="{{ $post->ytlink }}" id="option1">Option
                                            1</button></li>
                                @endif
                                @if ($post->scndlink != null)
                                    <li><button class="dropdown-item" value="{{ $post->scndlink }}"
                                            id="option2">Option
                                            2</button></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content customModal text-light">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ $post->title }}</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center">
                            <h2>Average Score: <strong>
                                    @if (Auth::user())
                                        @switch(Auth::user()->score_format)
                                            @case('POINT_100')
                                                {{ round($post->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($post->averageRating / 10, 1) }}
                                            @break

                                            @case('POINT_10')
                                                {{ round($post->averageRating / 10) }}
                                            @break

                                            @case('POINT_5')
                                                {{ round($post->averageRating / 20) }}
                                            @break

                                            @default
                                                {{ round($post->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($post->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </strong>
                            </h2>

                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                            Song info:
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @isset($post->song->song_romaji)
                                                <h4>Song title (romaji): <strong>{{ $post->song->song_romaji }}</strong></h4>
                                            @endisset
                                            @isset($post->song->song_jp)
                                                <h4>Song title (JP): <strong>{{ $post->song->song_jp }}</strong></h4>
                                            @endisset
                                            @isset($post->song->song_en)
                                                <h4>Song title (EN): <strong>{{ $post->song->song_en }}</strong></h4>
                                            @endisset
                                            @isset($post->artist->name)
                                                <h4>Song artist: <strong><a
                                                            href="{{ route('from.artist', $artist->name_slug) }}"
                                                            class="no-deco">{{ $post->artist->name }}</a></strong></h4>
                                            @endisset
                                            @isset($post->artist->name_jp)
                                                <h4>Song artist (JP): <strong><a
                                                            href="{{ route('from.artist', $artist->name_slug) }}"
                                                            class="no-deco">{{ $post->artist->name_jp }}</a></strong></h4>
                                            @endisset

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @guest
                                <hr>
                                <h6>for voting</h6>
                                <div>
                                    <a name="" id="" class="btn btn-sm btn-primary"
                                        href="{{ route('login') }}" role="button">Login</a> or <a name=""
                                        id="" class="btn btn-sm btn-primary" href="{{ route('register') }}"
                                        role="button">Register</a>
                                </div>
                            @endguest
                        </div>
                        <br>

                        @auth
                            <div>
                                <form action="{{ route('post.addrate', $post->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @if (Auth::user())
                                        @switch(Auth::user()->score_format)
                                            @case('POINT_100')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="100" step="1"
                                                    placeholder="Your score: {{ round($post->userAverageRating) }}">
                                                <div id="passwordHelpBlock" class="form-text text-light">
                                                    Your score must be 1-100 values
                                                </div>
                                                <input type="hidden" name="score_format" value="{{ $score_format }}">
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="10" step=".1"
                                                    placeholder="Your score: {{ round($post->userAverageRating / 10, 1) }}">
                                                <div id="passwordHelpBlock" class="form-text  text-light">
                                                    Your score must be 1-10 values (can use decimals)
                                                </div>
                                                <input type="hidden" name="score_format" value="{{ $score_format }}">
                                            @break

                                            @case('POINT_10')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="10" step="1"
                                                    placeholder="Your score: {{ round($post->userAverageRating / 10) }}">
                                                <div id="passwordHelpBlock" class="form-text text-light">
                                                    Your score must be 1-10 values (only integer values)
                                                </div>
                                                <input type="hidden" name="score_format" value="{{ $score_format }}">
                                            @break

                                            @case('POINT_5')
                                                <div class="d-flex justify-content-center">
                                                    <div class="stars">
                                                        <input class="star star-5" id="star-5" type="radio" name="score"
                                                            value="100" />
                                                        <label class="star star-5" for="star-5"></label>

                                                        <input class="star star-4" id="star-4" type="radio" name="score"
                                                            value="80" />
                                                        <label class="star star-4" for="star-4"></label>

                                                        <input class="star star-3" id="star-3" type="radio" name="score"
                                                            value="60" />
                                                        <label class="star star-3" for="star-3"></label>

                                                        <input class="star star-2" id="star-2" type="radio" name="score"
                                                            value="40" />
                                                        <label class="star star-2" for="star-2"></label>

                                                        <input class="star star-1" id="star-1" type="radio" name="score"
                                                            value="20" />
                                                        <label class="star star-1" for="star-1"></label>

                                                        <div class="row">
                                                            <p class="text-center">click the stars</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @default
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="100" step="1">
                                                <div id="passwordHelpBlock" class="form-text text-light">
                                                    Your score must be 1-100 values
                                                </div>
                                        @endswitch
                                    @else
                                        <strong>{{ round($post->averageRating) }}</strong>
                                    @endif
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Send Score</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        @endauth
                    </div>
                    @guest
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    @endguest

                </div>
            </div>
        </div>
        @section('script')
            <script>
                const buttonGroup = document.getElementById("button-group");
                const buttonGroupPressed = e => {

                    var isButton = e.target.nodeName === 'BUTTON';

                    if (!isButton) {
                        return
                    }

                    var option = document.getElementById(e.target.id);
                    var link = option.getAttribute('value');

                    const id_iframe = document.getElementById("id_iframe");
                    //id_iframe.setAttribute("src", link);
                    id_iframe.innerHTML = link;
                    //console.log(e.target.id);
                    //console.log(link);
                }
                buttonGroup.addEventListener("click", buttonGroupPressed);
            </script>
        @endsection
    </div>
@endsection
