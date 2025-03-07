@extends('layouts.app')
@section('meta')
    @php
        $title = $song_variant->song->post->title;
        $suffix =
            $song_variant->song->suffix != null
                ? $song_variant->song->suffix
                : $song_variant->song->type . ' v' . $song_variant->version;
        $artist_names = [];
        $artist_names = [];
        $artists_string = null;
        $song_name = null;
        if (isset($song_variant->song->artists) && $song_variant->song->artists->count() != 0) {
            foreach ($song_variant->song->artists as $artist) {
                $artist_names[] = $artist->name;
                $artists_string = implode(', ', $artist_names);
            }
        } else {
            $artists_string = 'N/A';
        }

        if (isset($song_variant->song->song_romaji)) {
            $song_name = $song_variant->song->song_romaji;
        } else {
            if ($song_variant->song->song_en) {
                $song_name = $song_variant->song->song_en;
            } else {
                if ($song_variant->song->song_jp) {
                    $song_name = $song_variant->song->song_jp;
                } else {
                    $song_name = 'N/A';
                }
            }
        }
        $currentUrl = url()->current();
        $thumbnailUrl = asset('/storage/thumbnails/' . $song_variant->song->post->thumbnail);

        if ($song_variant->views >= 1000000) {
            $views = number_format(intval($song_variant->views / 1000000), 0) . 'M';
        } elseif ($song_variant->views >= 1000) {
            $views = number_format(intval($song_variant->views / 1000), 0) . 'K';
        } else {
            $views = $song_variant->views;
        }
        $showPostRoute = route('post.show', [$song_variant->song->post->id, $song_variant->song->post->slug]);
        $forward_text =
            ($song_variant->song->suffix ? $song_variant->song->suffix : $song_variant->song->type) .
            ' v' .
            $song_variant->version;
        $score_string = '';
        if (Auth::User()) {
            switch (Auth::User()->score_format) {
                case 'POINT_5':
                    $score_string = $score != null ? $score . '/5' : 'N/A';
                    break;
                case 'POINT_10':
                    $score_string = $score != null ? $score . '/10' : 'N/A';
                    break;
                case 'POINT_10_DECIMAL':
                    $score_string = $score != null ? $score . '/10' : 'N/A';
                    break;
                case 'POINT_100':
                    $score_string = $score != null ? $score . '%' : 'N/A';
                    break;
                default:
                    $score_string = $score != null ? $score . '/10' : 'N/A';
                    break;
            }
        } else {
            $score_string = $score != null ? $score . '/10' : 'N/A';
        }
    @endphp

    <title>{{ $title }} {{ $suffix }}</title>
    <meta name="title" content="{{ $title }} {{ $suffix }}">
    <meta name="description" content="Song: {{ $song_name }}  - Artists: {{ $artists_string }}">
    <meta name="robots" content="index, follow, max-image-preview:standard">
    <link rel="canonical" href="{{ $currentUrl }}">
    <meta property="article:section" content="{{ $song_variant->song->type == 'OP' ? 'Opening' : 'Ending' }}">

    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $title }} {{ $suffix }}">
    <meta name="og:description" content="Song: {{ $song_name }} - Artist: {{ $artists_string }}">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:image" content="{{ $thumbnailUrl }}" alt="{{ $title . ' thumbnail' }}">
    <meta property="og:image:secure_url" content="{{ $thumbnailUrl }}" alt="{{ $title . ' thumbnail' }}">
    <meta property="og:image:width" content="460">
    <meta property="og:image:height" content="650">
    <meta property="og:image:alt" content="{{ $title . ' thumbnail' }}">
    <meta property="og:image:type" content="image/webp">
@endsection
@section('content')

    <div class="container">
        {{-- <div class="social-share-panel text-light">
            <div class="share-fb">
                <button><i class="fa-brands fa-facebook"></i></button>
            </div>
            <div class="share-ig">
                <button><i class="fa-brands fa-instagram"></i></button>
            </div>
            <div class="share-x-tw">
                <button><i class="fa-brands fa-x-twitter"></i></button>
            </div>
        </div> --}}
        <div class="row justify-content-center">
            <div class="card-video card">
                @if ($song_variant->video)
                    @if ($song_variant->video->type == 'file')
                        @php
                            $video_url = '';
                            if ($song_variant->video->type == 'file') {
                                $video_url = env('APP_URL') . Storage::url($song_variant->video->video_src);
                            }
                        @endphp
                        <div class="card-body p-0" id="video_container">
                            <video id="player" controls class="ratio-16x9" autoplay>
                                <source src="{{ $video_url }}" type="video/webm" />
                            </video>
                        </div>
                    @else
                        <div class="d-flex ratio-16x9 justify-content-center">
                            {!! $song_variant->video->embed_code !!}
                        </div>
                    @endif
                @else
                    <h3 class="text-light d-flex align-items-center justify-content-center">Videos not found</h3>
                @endif
            </div>

        </div>
    </div>

    <div class="father-container ">
        <div class="text-light my-2">
            <h1 style="font-size:2rem;"><a href="{{ $showPostRoute }}"
                    class="text-decoration-none text-light">{{ $song_variant->song->post->title }}
                    {{ $forward_text }}</a></h1>
        </div>
        <div class="all-buttons-container">
            <div class="buttons-container">
                <div class="d-flex gap-1">
                    <span class="px-2">{{ $score_string }} <i class="fa fa-star" aria-hidden="true"></i>
                        <span class="px-2">{{ $views }} <i class="fa fa-eye" aria-hidden="true"></i>
                        </span>
                        <span class="px-2">{{ $song_variant->likeCount }} <i class="fa-solid fa-heart"></i>
                        </span>

                </div>
                <div class="d-flex gap-1">
                    @guest
                        <a href="{{ route('login') }}" class="buttons-bottom px-2">{{ $song_variant->likeCount }} <i
                                class="fa-regular fa-heart"></i>
                        </a>
                    @endguest
                    @auth
                        @if ($song_variant->liked())
                            <form style="display: flex;width: 100%;height: 100%;"
                                action="{{ route('song.variant.unlike', [$song_variant->song->id, $song_variant->id]) }}"
                                method="post">
                                @csrf
                                <button class="buttons-bottom px-2"><i class="fa-solid fa-bookmark"></i></i>
                                </button>
                            </form>
                        @else
                            <form style="display: flex;width: 100%;height: 100%;"
                                action="{{ route('song.variant.like', [$song_variant->song->id, $song_variant->id]) }}"
                                method="post">
                                @csrf
                                <button class="buttons-bottom px-2"><i class="fa-regular fa-bookmark"></i>
                                </button>
                            </form>
                        @endif
                    @endauth
                    <a href="{{ route('song.create.report', [$song_variant->song->id, $song_variant->id]) }}"
                        class="buttons-bottom px-2" type="button"><i class="fa-solid fa-flag" aria-hidden="true"></i>
                        Report</a>

                    {{-- <button class="buttons-bottom px-2" type="button" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop"><i class="fa fa-info-circle" aria-hidden="true"></i>
                        Info</button> --}}
                </div>
            </div>
        </div>
        {{-- <div>
            <h4 class="text-light">Anime: {{$song_variant->song->post->title}}</h4>
        </div> --}}
        <div class="text-light">
            <p class="my-2"><span>Song: </span><strong>{{ $song_name }}</strong></p>
            <p class="my-2"><span>Artists: </span>
                @foreach ($song_variant->song->artists as $index => $item)
                    @php
                        $artistShowRoute = route('artist.show', [$item->id, $item->name_slug]);
                        if ($item->name_jp != null) {
                            $artistName = $item->name . ' (' . $item->name_jp . ')';
                        } else {
                            $artistName = $item->name;
                        }
                    @endphp
                    <a class="text-light text-decoration-none"
                        href="{{ $artistShowRoute }}"><strong>{{ $artistName }}</strong></a>
                    @if ($index < count($song_variant->song->artists) - 1)
                        ,
                    @endif
                @endforeach
            </p>
        </div>
        <hr class="text-light">
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
                    <form action="{{ route('song.variant.rate', [$song_variant->song->id, $song_variant->id]) }}"
                        method="post" class="d-flex flex-column gap-2">
                        @csrf
                        <div class="score-form text-light d-flex flex-column">
                            {{-- <span>Rate this theme:</span> --}}
                            @if (Auth::check())
                                @php
                                    if ($user_rate != null) {
                                        $user_score = $user_rate->format_rating;
                                    } else {
                                        $user_score = 0;
                                    }

                                @endphp
                                @switch(Auth::user()->score_format)
                                    @case('POINT_100')
                                        <div class="w-100">
                                            {{-- <input type="number" max="100" min="0" step="1" class="form-control"
                                                id="exampleFormControlInput1" name="score" placeholder="1 to 100" required> --}}
                                            <label for="score-input" class="form-label">Rate: <span
                                                    id="rangeValue">{{ $user_score }}</span>/100</label>
                                            <input type="range" class="form-range" min="0" max="100" step="1"
                                                id="score-input" name="score" value="{{ $user_score }}" required>
                                        </div>
                                    @break

                                    @case('POINT_10_DECIMAL')
                                        <div class="w-100">
                                            {{-- <input type="number" max="10" min="0" step=".1" class="form-control"
                                                id="exampleFormControlInput1" name="score" placeholder="1.0 to 10.0" required> --}}
                                            <label for="score-input" class="form-label">Rate: <span
                                                    id="rangeValue">{{ $user_score }}</span>/10</label>
                                            <input type="range" class="form-range" min="0" max="10" step="0.1"
                                                id="score-input" name="score" value="{{ $user_score }}" required>
                                        </div>
                                    @break

                                    @case('POINT_10')
                                        <div class="w-100">
                                            {{-- <input type="number" max="10" min="0" step="1" class="form-control"
                                                id="exampleFormControlInput1" name="score" placeholder="1 to 10" required> --}}
                                            <label for="score-input" class="form-label">Rate: <span
                                                    id="rangeValue">{{ $user_score }}</span>/10</label>
                                            <input type="range" class="form-range" min="0" max="10" step="1"
                                                id="score-input" name="score" value="{{ $user_score }}" required>
                                        </div>
                                    @break

                                    @case('POINT_5')
                                        <span class="align-self-start">Rate</span>
                                        <div class="stars align-self-start">
                                            <input class="star star-5" id="star-5" type="radio" name="score" value="100"
                                                {{ $user_score == 100 ? 'checked' : '' }} />
                                            <label class="star star-5" for="star-5"></label>

                                            <input class="star star-4" id="star-4" type="radio" name="score" value="80"
                                                {{ $user_score == 80 ? 'checked' : '' }} />
                                            <label class="star star-4" for="star-4"></label>

                                            <input class="star star-3" id="star-3" type="radio" name="score" value="60"
                                                {{ $user_score == 60 ? 'checked' : '' }} />
                                            <label class="star star-3" for="star-3"></label>

                                            <input class="star star-2" id="star-2" type="radio" name="score" value="40"
                                                {{ $user_score == 40 ? 'checked' : '' }} />
                                            <label class="star star-2" for="star-2"></label>

                                            <input class="star star-1" id="star-1" type="radio" name="score" value="20"
                                                {{ $user_score == 20 ? 'checked' : '' }} />
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
                            placeholder="Comment ... (optional)" maxlength="255">{{ $user_rate != '' ? $user_rate->comment : '' }}</textarea>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        @endauth

        @if ($comments_featured != null && count($comments_featured) > 0)
            <div class="my-2">
                <h4 class="text-light my-2">Featured comments</h4>
                @foreach ($comments_featured as $comment)
                    @php
                        $user_pp_url = '';

                        if (isset($comment->user->image)) {
                            $user_pp_url = $comment->user->image;
                        } else {
                            $user_pp_url = asset('/storage/profile/' . 'default.jpg');
                        }

                    @endphp
                    <div class="py-2">
                        <div class="comment-container">
                            <div class="profile-pic-container">
                                {{-- @if (isset($comment->user->image))
                                <img class="user-profile-pic" src="{{ asset('/storage/profile/' . $comment->user->image) }}"
                                    alt="">
                            @else
                                <img class="user-profile-pic" src="{{ asset('/storage/profile/' . 'default.jpg') }}"
                                    alt="">
                            @endif --}}
                                <img class="user-profile-pic" src="{{ $user_pp_url }}" alt="User profile pic">
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
                                                <form action="{{ route('comment.unlike', $comment->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <button class="no-deco text-light"
                                                        style="background-color: transparent;border:none;">{{ $comment->likeCount }}
                                                        <i class="fa-solid fa-thumbs-up"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('comment.like', $comment->id) }}" method="post">
                                                    @csrf
                                                    <button class="no-deco text-light"
                                                        style="background-color: transparent;border:none;">{{ $comment->likeCount }}
                                                        <i class="fa-regular fa-thumbs-up"></i></button>
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
            </div>
        @endif

        @if ($comments != null && count($comments) > 0)
            <div class="my-2">
                <h4 class="text-light my-2">Recents comments</h4>
                @foreach ($comments as $comment)
                    @php
                        $user_pp_url = '';

                        if (isset($comment->user->image)) {
                            $user_pp_url = $comment->user->image;
                        } else {
                            $user_pp_url = asset('/storage/profile/' . 'default.jpg');
                        }

                    @endphp
                    <div class="py-2">
                        <div class="comment-container">
                            <div class="profile-pic-container">
                                <img class="user-profile-pic" src="{{ $user_pp_url }}" alt="User profile pic">
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
                                                <form action="{{ route('comment.unlike', $comment->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <button class="no-deco text-light"
                                                        style="background-color: transparent;border:none;">{{ $comment->likeCount }}
                                                        <i class="fa-solid fa-thumbs-up"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('comment.like', $comment->id) }}" method="post">
                                                    @csrf
                                                    <button class="no-deco text-light"
                                                        style="background-color: transparent;border:none;">{{ $comment->likeCount }}
                                                        <i class="fa-regular fa-thumbs-up"></i></button>
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
            </div>
        @endif
    </div>

    @section('script')
        {{-- @if (config('app.env') === 'local')
            @vite(['resources/js/api_get_video.js'])
        @else
            <script src="{{ asset('build/api_get_video.js') }}"></script>
        @endif --}}

        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
        <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
        <script>
            const player = new Plyr('#player');
        </script>
    @endsection
@endsection
