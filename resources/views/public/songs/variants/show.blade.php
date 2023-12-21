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
        <div class="social-share-panel text-light">
            <div class="share-fb">
                <button><i class="fa-brands fa-facebook"></i></button>
            </div>
            <div class="share-ig">
                <button><i class="fa-brands fa-instagram"></i></button>
            </div>
            <div class="share-x-tw">
                <button><i class="fa-brands fa-x-twitter"></i></button>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="card-video card">
                <div class="card-header" id="card-header">
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($song_variant->videos as $video)
                        <button type="button" class="btn btn-sm btn-primary" value="{{ $video->id }}">Option
                            {{ $i++ }}</button>
                    @endforeach
                </div>
                <div class="card-body p-0 ratio ratio-16x9" id="video_container">
                    @if ($song_variant->videos->count() != 0)
                        <h3 class="text-light d-flex align-items-center justify-content-center">Select an video option</h3>
                    @else
                        <h3 class="text-light d-flex align-items-center justify-content-center">Videos not found</h3>
                    @endif
                </div>
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
                <span>{{ $song->suffix ? $song->suffix : '' }} {{ 'v' . $song_variant->version }}</span>
            </h3>
        </div>
        <div class="all-buttons-container">
            <div class="buttons-container">
                <div class="d-flex gap-1">
                    <button class="buttons-bottom px-2">{{ $score != null ? $score : 'n/a' }} <i class="fa fa-star"
                            aria-hidden="true"></i>
                    </button>

                    <button class="buttons-bottom px-2">{{ $song->view_count }} <i class="fa fa-eye"
                            aria-hidden="true"></i>
                    </button>
                    @guest
                        <a href="{{ route('login') }}" class="buttons-bottom px-2">{{ $song->likeCount }} <i
                                class="fa-regular fa-heart"></i>
                        </a>
                    @endguest
                    @auth
                        @if ($song->liked())
                            <form style="display: flex;width: 100%;height: 100%;"
                                action="{{ route('song.unlike', $song->id) }}" method="post">
                                @csrf
                                <button class="buttons-bottom px-2">{{ $song->likeCount }} <i class="fa-solid fa-heart"></i>
                                </button>
                            </form>
                        @else
                            <form style="display: flex;width: 100%;height: 100%;" action="{{ route('song.like', $song->id) }}"
                                method="post">
                                @csrf
                                <button class="buttons-bottom px-2">{{ $song->likeCount }} <i class="fa-regular fa-heart"></i>
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('song.create.report', $song->id) }}" class="buttons-bottom px-2" type="button"><i
                            class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        Report</a>

                    <button class="buttons-bottom px-2" type="button" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop"><i class="fa fa-info-circle" aria-hidden="true"></i>
                        Info</button>
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
                    <form action="{{ route('song.addrate', $song->id) }}" method="post" class="d-flex flex-column gap-2">
                        @csrf
                        <div class="score-form text-light">
                            <span>Rate this theme:</span>
                            @if (Auth::check())
                                @switch(Auth::user()->score_format)
                                    @case('POINT_100')
                                        <div class="">
                                            <input type="number" max="100" min="0" step="1" class="form-control"
                                                id="exampleFormControlInput1" name="score" placeholder="1 to 100" required>
                                        </div>
                                    @break

                                    @case('POINT_10_DECIMAL')
                                        <div class="">
                                            <input type="number" max="10" min="0" step=".1" class="form-control"
                                                id="exampleFormControlInput1" name="score" placeholder="1 to 10" required>
                                        </div>
                                    @break

                                    @case('POINT_10')
                                        <div class="">
                                            <input type="number" max="10" min="0" step="1" class="form-control"
                                                id="exampleFormControlInput1" name="score" placeholder="1 to 10" required>
                                        </div>
                                    @break

                                    @case('POINT_5')
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
                                    @break

                                    @default
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
                                @endswitch
                            @endif
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
                                <img class="user-profile-pic" src="{{ asset('/storage/profile/' . $comment->user->image) }}"
                                    alt="">
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
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                @break

                                                @case($comment->rating > 20 && $comment->rating <= 40)
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                @break

                                                @case($comment->rating > 40 && $comment->rating <= 60)
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                @break

                                                @case($comment->rating > 60 && $comment->rating <= 80)
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                @break

                                                @case($comment->rating >= 80)
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
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
                                <img class="user-profile-pic" src="{{ asset('/storage/profile/' . $comment->user->image) }}"
                                    alt="">
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
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                @break

                                                @case($comment->rating > 20 && $comment->rating <= 40)
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                @break

                                                @case($comment->rating > 40 && $comment->rating <= 60)
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                @break

                                                @case($comment->rating > 60 && $comment->rating <= 80)
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                @break

                                                @case($comment->rating >= 80)
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
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
                                <i class="fa-solid fa-star"></i>
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

    @section('script')
        @if (config('app.env') === 'local')
            @vite(['resources/js/api_get_video.js'])
        @else
            <script src="{{ asset('resources/js/api_get_video.js') }}"></script>
        @endif

        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
        <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    @endsection
@endsection
