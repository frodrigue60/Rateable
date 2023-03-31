@extends('layouts.app')
{{-- @section('meta')
    <title>
        {{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}</title>
    <meta name="title"
        content="{{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}">

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
    <meta property="og:title" content="{{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}">

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
    <meta property="og:image:alt" content="{{ $post->title }} {{ $post->suffix != null ? $post->suffix : $post->type }}">
    <meta property="og:image:type" content="image/webp">
    
@endsection --}}
@section('content')
    <div class="container">
        {{-- @isset($song->post)
            <h1 class="text-light text-center" hidden>{{ $song->post->title }}</h1>
        @endisset --}}
        <div class="row justify-content-center">
            <div class="card-video card ">
                <div class="card-body ratio ratio-16x9" id="id_iframe">
                    {!! $song->ytlink !!}
                </div>
                <div class="card-footer">
                    <h1 class="text-light show-view-title mb-0">
                        <a class="no-deco text-light"
                            href="{{ route('post.show', [$song->post->id, $song->post->slug]) }}">{{ $song->post->title }}</a>
                        {{ $song->suffix != null ? $song->suffix : $song->type }}
                    </h1>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-start">
                    <div class="text-light">
                        <button class="button-border-1">{{ $song->post->view_count }} <i class="fa fa-eye"></i></button>
                        <button class="button-border-1">{{ $song->post->averageRating / 1 }} <i
                                class="fa fa-star"></i></button>
                    </div>
                    <div class="d-flex btn-group-show">
                        @auth
                            <a href="{{ route('post.create.report', $song->post->id) }}" class="button2 no-deco"> Report <i
                                    class="fa fa-exclamation-circle" aria-hidden="true"></i>
                            </a>
                        @endauth
                        <button class="button2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            Rate
                            <i class="fa fa-star"></i></button>
                        @guest
                            <button class="button2" disabled id="like"> <i class="fa fa-heart"></i></button>
                        @endguest
                        @auth
                            @if ($song->liked())
                                <form action="{{ route('song.unlike', $song->id) }}" method="post">
                                    @csrf
                                    <button class="button-liked" id="like"> <i class="fa fa-heart"></i></button>
                                </form>
                            @else
                                <form action="{{ route('song.like', $song->id) }}" method="post">
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
                                @if ($song->ytlink != null)
                                    <li><button class="dropdown-item" value="{{ $song->ytlink }}" id="option1">Option
                                            1</button></li>
                                @endif
                                @if ($song->scndlink != null)
                                    <li><button class="dropdown-item" value="{{ $song->scndlink }}" id="option2">Option
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
                        <span class="modal-title fs-5" id="staticBackdropLabel">{{ $song->post->title }}</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center">
                            <h2>Average Score: <strong>
                                    @if (Auth::user())
                                        @switch(Auth::user()->score_format)
                                            @case('POINT_100')
                                                {{ round($song->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($song->averageRating / 10, 1) }}
                                            @break

                                            @case('POINT_10')
                                                {{ round($song->averageRating / 10) }}
                                            @break

                                            @case('POINT_5')
                                                {{ round($song->averageRating / 20) }}
                                            @break

                                            @default
                                                {{ round($song->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($song->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </strong>
                            </h2>

                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Song info:
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @isset($song->song_romaji)
                                                <h4>Song title (romaji): <strong>{{ $song->song_romaji }}</strong></h4>
                                            @endisset
                                            @isset($song->song_jp)
                                                <h4>Song title (JP): <strong>{{ $song->song_jp }}</strong></h4>
                                            @endisset
                                            @isset($song->song_en)
                                                <h4>Song title (EN): <strong>{{ $song->song_en }}</strong></h4>
                                            @endisset
                                            @isset($song->artist->name)
                                                <h4>Artist: <strong><a href="{{ route('artist.show', [$song->artist->id,$song->artist->name_slug]) }}"
                                                            class="no-deco">{{ $song->artist->name }}</a></strong></h4>
                                            @endisset
                                            @isset($song->artist->name_jp)
                                                <h4>Artist (JP): <strong><a
                                                            href="{{ route('artist.show', [$song->artist->id,$song->artist->name_slug]) }}"
                                                            class="no-deco">{{ $song->artist->name_jp }}</a></strong></h4>
                                            @endisset

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @guest
                                <hr>
                                <h6>for voting</h6>
                                <div>
                                    <a class="btn btn-sm btn-primary" href="{{ route('login') }}" role="button">Login</a> or
                                    <a class="btn btn-sm btn-primary" href="{{ route('register') }}"
                                        role="button">Register</a>
                                </div>
                            @endguest
                        </div>
                        <br>

                        @auth
                            <div>
                                <form action="{{ route('song.addrate', $song->id) }}" method="post">
                                    @csrf
                                    @if (Auth::user())
                                        @switch($score_format)
                                            @case('POINT_100')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="100" step="1"
                                                    placeholder="Your score: {{ round($song->userAverageRating) }}">
                                                <div id="passwordHelpBlock" class="form-text text-light">
                                                    Your score must be 1-100 values
                                                </div>
                                                <input type="hidden" name="score_format" value="{{ $score_format }}">
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="10" step=".1"
                                                    placeholder="Your score: {{ round($song->userAverageRating / 10, 1) }}">
                                                <div id="passwordHelpBlock" class="form-text  text-light">
                                                    Your score must be 1-10 values (can use decimals)
                                                </div>
                                                <input type="hidden" name="score_format" value="{{ $score_format }}">
                                            @break

                                            @case('POINT_10')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="10" step="1"
                                                    placeholder="Your score: {{ round($song->userAverageRating / 10) }}">
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
                                        <strong>{{ round($song->averageRating) }}</strong>
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
