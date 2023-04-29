@extends('layouts.app')
@section('meta')
    <title>{{ $song->post->title }} {{ $song->suffix != null ? $song->suffix : $song->type }}</title>
    <meta name="title" content="{{ $song->post->title }} {{ $song->suffix != null ? $song->suffix : $song->type }}">

    @if (isset($song->song_romaji))
        @if (isset($song->artist->name))
            <meta name="description" content="Song: {{ $song->song_romaji }} - Artist: {{ $song->artist->name }}">
        @else
            <meta name="description" content="Song: {{ $song->song_romaji }} - Artist: N/A">
        @endif
    @else
        @if (isset($song->song_en))
            @if (isset($song->artist->name))
                <meta name="description" content="Song: {{ $song->song_en }} - Artist: {{ $song->artist->name }}">
            @else
                <meta name="description" content="Song: {{ $song->song_en }} - Artist: N/A">
            @endif
        @endif
    @endif


    <meta name="robots" content="index, follow, max-image-preview:standard">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="article">
    <meta property="og:title"
        content="{{ $song->post->title }} {{ $song->suffix != null ? $song->suffix : $song->type }}">

    @if (isset($song->song_romaji))
        @if (isset($song->artist->name))
            <meta name="og:description" content="Song: {{ $song->song_romaji }} - Artist: {{ $song->artist->name }}">
        @else
            <meta name="og:description" content="Song: {{ $song->song_romaji }} - Artist: N/A">
        @endif
    @else
        @if (isset($song->song_en))
            @if (isset($song->artist->name))
                <meta name="og:description" content="Song: {{ $song->song_en }} - Artist: {{ $song->artist->name }}">
            @else
                <meta name="og:description" content="Song: {{ $song->song_en }} - Artist: N/A">
            @endif
        @endif
    @endif
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="article:section" content="{{ $song->type == 'OP' ? 'Opening' : 'Ending' }}">

    <meta property="og:image" content="{{ asset('/storage/thumbnails/' . $song->post->thumbnail) }}"
        alt="{{ $song->post->title }}">
    <meta property="og:image:secure_url" content="{{ asset('/storage/thumbnails/' . $song->post->thumbnail) }}"
        alt="{{ $song->post->title }}">
    <meta property="og:image:width" content="460">
    <meta property="og:image:height" content="650">
    <meta property="og:image:alt"
        content="{{ $song->post->title }} {{ $song->suffix != null ? $song->suffix : $song->type }}">
    <meta property="og:image:type" content="image/webp">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card-video card ">
                <div class="card-body ratio ratio-16x9" id="id_iframe">
                    @if ($song->video_src != null)
                        <iframe src="{{ asset('/storage/videos/' . $song->video_src) }}" frameborder="0" type="video/webm"></iframe>
                    @else
                        {!! $song->ytlink !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="father-container ">
            <div
                style="background-color: #0e3d5f;
                    border-bottom: #151C2E;margin: 10px 0px; border-radius: 5px;
                    ">
                <h3 class="mb-0 py-1 px-2 text-center text-light">
                    <a class="text-light text-decoration-none"
                        href="{{ route('post.show', [$song->post->id, $song->post->slug]) }}">{{ $song->post->title }}</a>
                    <span>{{ $song->suffix ? $song->suffix : '' }}</span>
                </h3>
            </div>
            <div class="all-buttons-container">
                <div class="buttons-container">
                    <div class="button-cont">
                        <button class="buttons-bottom">{{ $score != null ? $score : 'n/a' }} <i class="fa fa-star" aria-hidden="true"
                                style="color: rgb(240, 188, 43)"></i>
                        </button>
                    </div>
                    <div class="button-cont">
                        <button class="buttons-bottom">{{ $song->view_count }} <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="button-cont">
                        @guest
                            <a href="{{ route('login') }}" class="buttons-bottom">{{ $song->likeCount }} <i
                                    class="fa fa-heart-o" aria-hidden="true"></i>
                            </a>
                        @endguest
                        @auth
                            @if ($song->liked())
                                <form style="display: flex;width: 100%;height: 100%;"
                                    action="{{ route('song.unlike', $song->id) }}" method="post">
                                    @csrf
                                    <button class="buttons-bottom">{{ $song->likeCount }} <i class="fa fa-heart"
                                            aria-hidden="true" style="color: rgb(199, 59, 59)"></i>
                                    </button>
                                </form>
                            @else
                                <form style="display: flex;width: 100%;height: 100%;"
                                    action="{{ route('song.like', $song->id) }}" method="post">
                                    @csrf
                                    <button class="buttons-bottom">{{ $song->likeCount }} <i class="fa fa-heart-o"
                                            aria-hidden="true"></i>
                                    </button>
                                </form>
                            @endif
                        @endauth

                    </div>
                </div>
                <div class="options-container">
                    <div class="dropdown">
                        <button class="buttons-bottom dropdown-toggle border-0 px-3" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu" id="dropdown-menu">
                            <li><button class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#shareModal"><i class="fa fa-share-alt" aria-hidden="true"></i>
                                    Share</button></li>
                            <li><a href="{{ route('song.create.report', $song->id) }}" class="dropdown-item"
                                    type="button"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                    Report</a>
                            </li>
                            <li><button class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                    Info</button></li>
                            <li>
                                <div id="button-group">
                                    <button type="button" onclick="rv('{{ $song->ytlink }}')" class="dropdown-item"><i
                                            class="fa fa-play" aria-hidden="true"></i>
                                        Option 1</button>
                                    @if (isset($song->scndlink))
                                        <button type="button" onclick="rv('{{ $song->scndlink }}')"
                                            class="dropdown-item"><i class="fa fa-play" aria-hidden="true"></i>
                                            Option 2</button>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-light">Comments</h3>
            </div>
            @guest
                <div class="d-flex justify-content-center comment-form text-light">
                    <h3>Please <a class="text-light" href="{{ route('login') }}">login</a> or <a class="text-light"
                            href="{{ route('register') }}">register</a> for comment</h3>
                </div>
            @endguest
            @auth
                <div class="py-2">
                    <div class="comment-form">
                        <form action="{{ route('song.addrate', $song->id) }}" method="post"
                            class="d-flex flex-column gap-2">
                            @csrf
                            <div class="score-form text-light">
                                <span>Rate this theme:</span>
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
                                </div>
                            </div>
                            <textarea name="comment" class="form-control" id="exampleFormControlTextarea1" rows="2"
                                placeholder="Comment ... (optional)" maxlength="255"></textarea>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            @endauth
            <h4 class="text-light my-2">Featured comments</h4>
            @isset($comments_featured)
                @foreach ($comments_featured as $comment)
                    <div class="py-2">
                        <div class="comment-container">
                            <div class="profile-pic-container">
                                @if (isset($comment->user->image))
                                    <img class="user-profile-pic"
                                        src="{{ asset('/storage/profile/' . $comment->user->image) }}" alt="">
                                @else
                                    <img class="user-profile-pic" src="{{ asset('/storage/profile/' . 'default.jpg') }}"
                                        alt="">
                                @endif
                            </div>
                            <div class="comment-details">
                                <div>
                                    <div class="user-details">
                                        <div style="display: flex; gap: 10px;">
                                            <div class="user-name">
                                                <a class="no-deco text-light"
                                                    href="{{ route('user.list', $comment->user->id) }}">{{ $comment->user->name }}</a>
                                            </div>
                                            <div class="user-score">
                                                @switch($comment->rating)
                                                    @case($comment->rating <= 20)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    @break

                                                    @case($comment->rating > 20 && $comment->rating <= 40)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    @break

                                                    @case($comment->rating > 40 && $comment->rating <= 60)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    @break

                                                    @case($comment->rating > 60 && $comment->rating <= 80)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    @break

                                                    @case($comment->rating >= 80)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    @break

                                                    @default
                                                @endswitch

                                            </div>
                                        </div>
                                        <div class="like-buttons">
                                            @if ($comment->liked())
                                                <form action="{{ route('comment.unlike', $comment->id) }}" method="post">
                                                    @csrf
                                                    <button class="no-deco text-light"
                                                        style="background-color: transparent;border:none;">{{ $comment->likeCount }}
                                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('comment.like', $comment->id) }}" method="post">
                                                    @csrf
                                                    <button class="no-deco text-light"
                                                        style="background-color: transparent;border:none;">{{ $comment->likeCount }}
                                                        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="date">
                                        <span>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="comment-content">
                                        <span>{{ $comment->comment }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endisset
            <h4 class="text-light my-2">Recents comments</h4>
            @isset($comments)
                @foreach ($comments as $comment)
                    <div class="py-2">
                        <div class="comment-container">
                            <div class="profile-pic-container">
                                @if (isset($comment->user->image))
                                    <img class="user-profile-pic"
                                        src="{{ asset('/storage/profile/' . $comment->user->image) }}" alt="">
                                @else
                                    <img class="user-profile-pic" src="{{ asset('/storage/profile/' . 'default.jpg') }}"
                                        alt="">
                                @endif
                            </div>
                            <div class="comment-details">
                                <div>
                                    <div class="user-details">
                                        <div style="display: flex; gap: 10px;">
                                            <div class="user-name">
                                                <a class="no-deco text-light"
                                                    href="{{ route('user.list', $comment->user->id) }}">{{ $comment->user->name }}</a>
                                            </div>
                                            <div class="user-score">
                                                @switch($comment->rating)
                                                    @case($comment->rating <= 20)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    @break

                                                    @case($comment->rating > 20 && $comment->rating <= 40)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    @break

                                                    @case($comment->rating > 40 && $comment->rating <= 60)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    @break

                                                    @case($comment->rating > 60 && $comment->rating <= 80)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    @break

                                                    @case($comment->rating >= 80)
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    @break

                                                    @default
                                                @endswitch

                                            </div>
                                        </div>
                                        <div class="like-buttons">
                                            @if ($comment->liked())
                                                <form action="{{ route('comment.unlike', $comment->id) }}" method="post">
                                                    @csrf
                                                    <button class="no-deco text-light"
                                                        style="background-color: transparent;border:none;">{{ $comment->likeCount }}
                                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('comment.like', $comment->id) }}" method="post">
                                                    @csrf
                                                    <button class="no-deco text-light"
                                                        style="background-color: transparent;border:none;">{{ $comment->likeCount }}
                                                        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="date">
                                        <span>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="comment-content">
                                        <span>{{ $comment->comment }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endisset


        </div>

        <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <span class="modal-title fs-5" id="staticBackdropLabel">{{ $song->post->title }}</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center">
                            <h3>Average Score: <strong>
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
                            </h3>

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
                                <h4>Artist: <strong><a
                                            href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}"
                                            class="no-deco">{{ $song->artist->name }}</a></strong></h4>
                            @endisset
                            @isset($song->artist->name_jp)
                                <h4>Artist (JP): <strong><a
                                            href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}"
                                            class="no-deco">{{ $song->artist->name_jp }}</a></strong></h4>
                            @endisset
                        </div>
                    </div>
                    @guest
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    @endguest

                </div>
            </div>
        </div>

        <div class="modal fade" id="shareModal" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <span class="modal-title fs-5" id="staticBackdropLabel">Share</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank"
                                class="btn btn-primary">FaceBook</a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}" target="_blank"
                                class="btn btn-primary">Twitter</a>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @section('script')
            <script>
                const id_iframe = document.getElementById("id_iframe");

                function rv(link) {
                    id_iframe.innerHTML = link;
                }
            </script>
        @endsection
    </div>
@endsection
