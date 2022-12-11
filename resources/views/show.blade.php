@extends('layouts.app')
<!doctype html>

<head>
    <link rel="stylesheet" href="{{ asset('/resources/css/fivestars.css') }}">
    <title>{{ $post->title }}</title>
</head>

@section('content')
    <div class="container">
        <div class="text-center text-light">
            <h1>{{ $post->title }}</h1>
        </div>
        @if (session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="col-9 mx-auto">
            <div class="card">
                <div class="card-header row justify-content-between">
                    <div id="button-group" class="col-9">
                        <button class="btn btn-secondary" value="{{ $post->ytlink }}" id="option1">Option1</button>
                        @if ($post->scndlink != null)
                           <button class="btn btn-secondary" value="{{ $post->scndlink }}" id="option2">Option 2</button> 
                        @endif
                        
                    </div>
                    <div class="col-md-auto">
                        @auth
                            @if ($post->liked())
                                <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-danger" id="like">Favorite <i class="fa fa-heart"></i></button>
                                </form>
                            @else
                                <form action="{{ route('like.post', $post->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-success" id="like">Favorite <i class="fa fa-heart"></i></button>
                                </form>
                            @endif
                        @endauth

                    </div>
                </div>
                <div class="card-body ratio ratio-16x9">
                    <iframe id="id_iframe" src="{{ $post->ytlink }}" title="" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture;"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="card-footer row justify-content-between">
                    <div class="col-9">
                        <a name="" id="" class="btn btn-success" href="#" role="button">Spotify</a>
                        <a name="" id="" class="btn btn-success" href="#" role="button">Apple
                            Music</a>
                    </div>
                    <div class="col-md-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">
                            More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ $post->title }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center text-dark">
                            <h2>Average Score: <strong>
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                <strong>{{ round($post->averageRating) }}</strong>
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                    class="fa fa-star"></i>
                                            @break

                                            @case('POINT_10')
                                                <strong>{{ round($post->averageRating / 10) }}</strong> <i class="fa fa-star"></i>
                                            @break

                                            @case('POINT_5')
                                                <strong>{{ round($post->averageRating / 20) }}</strong> <i class="fa fa-star"></i>
                                            @break

                                            @default
                                                <strong>{{ round($post->averageRating) }}</strong>
                                        @endswitch
                                    @else
                                        <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                            class="fa fa-star"></i>
                                    @endif
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
                                            @isset($post->song_romaji)
                                                <h4>Song title (romaji): <strong>{{ $post->song_romaji }}</strong></h4>
                                            @endisset
                                            @isset($post->song_jp)
                                                <h4>Song title (JP): <strong>{{ $post->song_jp }}</strong></h4>
                                            @endisset
                                            @isset($post->song_en)
                                                <h4>Song title (EN): <strong>{{ $post->song_en }}</strong></h4>
                                            @endisset
                                            @isset($post->artist->name)
                                                <h4>Song artist: <strong><a
                                                            href="{{ route('fromartist', $artist->name_slug) }}"
                                                            class="no-deco">{{ $post->artist->name }}</a></strong></h4>
                                            @endisset
                                            @isset($post->artist->name_jp)
                                                <h4>Song artist (JP): <strong><a
                                                            href="{{ route('fromartist', $artist->name_slug) }}"
                                                            class="no-deco">{{ $post->artist->name_jp }}</a></strong></h4>
                                            @endisset

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        @auth
                            <div>
                                <form action="{{ route('post.addrate', $post->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="100" step="1"
                                                    placeholder="Your score: {{ round($post->userAverageRating) }}">
                                                <div id="passwordHelpBlock" class="form-text">
                                                    Your score must be 1-100 values
                                                </div>
                                                <input type="hidden" name="score_format" value="{{ $score_format }}">
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="10" step=".1"
                                                    placeholder="Your score: {{ round($post->userAverageRating / 10, 1) }}">
                                                <div id="passwordHelpBlock" class="form-text">
                                                    Your score must be 1-10 values (can use decimals)
                                                </div>
                                                <input type="hidden" name="score_format" value="{{ $score_format }}">
                                            @break

                                            @case('POINT_10')
                                                <label for="inputNumber" class="form-label">Score</label>
                                                <input name="score" type="number" id="inputNumber" class="form-control"
                                                    aria-describedby="" min="1" max="10" step="1"
                                                    placeholder="Your score: {{ round($post->userAverageRating / 10) }}">
                                                <div id="passwordHelpBlock" class="form-text">
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
                                                <div id="passwordHelpBlock" class="form-text">
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

        <div id="result"></div>
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
                id_iframe.setAttribute("src", link);

                console.log(e.target.id);
                console.log(link);
            }
            buttonGroup.addEventListener("click", buttonGroupPressed);
        </script>
    </div>
@endsection
