@extends('layouts.app')

@php
    $song = $song_variant->song;
    $post = $song->post;

    $title = $song_variant->song->post->title;
    $suffix =
        $song_variant->song->slug != null
            ? $song_variant->song->slug
            : $song_variant->song->type . ' v' . $song_variant->version_number;
    $artist_names = [];
    $artists_string = null;

    if (isset($song_variant->song->artists) && $song_variant->song->artists->count() != 0) {
        foreach ($song_variant->song->artists as $artist) {
            $artist_names[] = $artist->name;
            $artists_string = implode(', ', $artist_names);
        }
    } else {
        $artists_string = 'N/A';
    }

    $currentUrl = url()->current();
    $thumbnailUrl = asset('/storage/thumbnails/' . $song_variant->song->post->thumbnail);
@endphp

@section('meta')
    <title>{{ $title }} {{ $suffix }}</title>
    <meta name="title" content="{{ $title }} {{ $suffix }}">
    <meta name="description" content="Song: {{ $song->name }}  - Artists: {{ $artists_string }}">
    <meta name="robots" content="index, follow, max-image-preview:standard">
    <link rel="canonical" href="{{ $currentUrl }}">
    <meta property="article:section" content="{{ $song_variant->song->type == 'OP' ? 'Opening' : 'Ending' }}">

    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $title }} {{ $suffix }}">
    <meta name="og:description" content="Song: {{ $song->name }} - Artist: {{ $artists_string }}">
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
        <div class="row mb-3">
            @if ($song_variant->video)
                @if ($song_variant->video->type == 'file')
                    @php
                        $video_url = '';
                        if ($song_variant->video->type == 'file') {
                            $video_url = Storage::url($song_variant->video->video_src);
                        }
                    @endphp
                    <div class="" id="video_container">
                        <video id="player"class="ratio-16x9" controls>
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

        <div class="text-light">
            <div class="mb-3">
                <h2>
                    <a href="{{ $post->url }}" class="text-decoration-none text-light">{{ $post->title }}
                        {{ $song->slug }} {{ $song_variant->slug }}</a>
                </h2>
                <div class="my-2">
                    <a href="#" class="text-decoration-none text-light">{{ $song->name }}</a> -
                    @foreach ($song_variant->song->artists as $index => $artist)
                        @php
                            /* $artistShowRoute = route('artists.show', [$item->id, $item->name_slug]); */
                            if ($artist->name_jp != null) {
                                $artistNameJp = '('.$artist->name_jp.')';
                            }else{
                                $artistNameJp = '';
                            }
                        @endphp
                        <a class="text-decoration-none text-light" href="{{ $artist->url }}">{{ $artist->name }} {{$artistNameJp}}</a>
                        @if ($index < count($song_variant->song->artists) - 1)
                            ,
                        @endif
                    @endforeach

                </div>
                <div class="d-flex mb-2">
                    <span>Views {{ $song_variant->viewsString }}</span>
                </div>
            </div>
            {{-- Actions buttons --}}
            <div class="d-flex justify-content-between">
                <div class="d-flex gap-3">
                    @guest
                        {{-- LIKES --}}
                        <div>
                            <button class="btn btn-primary rounded-pill"><i class="fa-regular fa-thumbs-up"></i>
                                <span id="like-counter">{{ $song_variant->likesCount }}</span>
                            </button>
                        </div>
                        {{-- DISLIKES --}}
                        <div>
                            <button class="btn btn-primary rounded-pill"><i class="fa-regular fa-thumbs-down"></i>
                                <span id="dislike-counter">{{ $song_variant->dislikesCount }}</span>
                            </button>
                        </div>
                    @endguest
                    @auth
                        {{-- LIKES --}}
                        <div>
                            <button id="like-button" data-variant="{{ $song_variant->id }}"
                                class="btn btn-primary rounded-pill"><i class="fa-regular fa-thumbs-up"></i>
                                <span id="like-counter">{{ $song_variant->likes()->count() }}</span>
                            </button>
                        </div>
                        {{-- DISLIKES --}}
                        <div>
                            <button id="dislike-button" class="btn btn-primary rounded-pill"
                                data-variant="{{ $song_variant->id }}">
                                <i class="fa-regular fa-thumbs-down"></i> <span
                                    id="dislike-counter">{{ $song_variant->dislikes()->count() }}</span>
                            </button>
                        </div>
                    @endauth

                    {{-- SCORE --}}
                    <div>
                        @php
                            if (Auth::check()) {
                                $class = $user_rate ? 'btn-warning' : 'btn-primary';
                            } else {
                                $class = 'btn-primary';
                            }

                        @endphp
                        <button class="btn {{ $class }} rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#rating-modal" id="rating-button">
                            <i class="fa fa-star" aria-hidden="true"></i> <span
                                id="score-span">{{ $song_variant->scoreString }}</span>
                        </button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit"
                        class="btn {{ $song_variant->isFavorited() ? 'btn-danger' : 'btn-primary' }} rounded-pill d-flex gap-2 align-items-center"
                        id="favorite-button" data-variant="{{ $song_variant->id }}"><i
                            class="fa-{{ $song_variant->isFavorited() ? 'solid' : 'regular' }} fa-heart"
                            id="i-favorite"></i><span class="d-none d-sm-flex">
                            Favorite</span></button>


                    <button type="button" class="btn btn-primary rounded-pill d-flex gap-2 align-items-center" data-bs-toggle="modal"
                        data-bs-target="#report-modal">
                        <i class="fa-solid fa-triangle-exclamation"></i> <span class="d-none d-sm-flex">Report</span>
                    </button>
                </div>
            </div>
        </div>
        <hr>

        {{-- Report Modal --}}
        <div class="modal fade" tabindex="-1" id="report-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-light">
                    @if (Auth::check())
                        <form action="{{ route('reports.store') }}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Report {{ $post->title }}
                                    {{ $song->slug }} {{ $song_variant->slug }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="song_variant_id" value="{{ $song_variant->id }}">
                                <input type="hidden" name="user_id" value="{{ Auth::User()->id }}">
                                <div class="mb-3">
                                    <label for="title-input" class="form-label">Report title</label>
                                    <input type="text" class="form-control" id="title-input"
                                        placeholder="Title report..." name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content-textarea" class="form-label">Report content</label>
                                    <textarea class="form-control" id="content-textarea" rows="3" placeholder="Report content..." name="content"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Send report</button>
                            </div>
                        </form>
                    @else
                        <div class="d-flex justify-content-center comment-form text-light">
                            <h3>Please <a class="text-light" href="{{ route('login') }}">login</a> or <a
                                    class="text-light" href="{{ route('register') }}">register</a> for report</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Rate Modal --}}
        <div class="modal fade" tabindex="-1" id="rating-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-light">
                    @if (Auth::check())
                        @csrf
                        @php
                            if ($user_rate != null) {
                                $user_score = $user_rate->format_rating;
                            } else {
                                $user_score = 0;
                            }
                        @endphp
                        <div class="text-light d-flex flex-column align-items-center">
                            @php
                                $format_rating = '';
                                if (isset($user_rate->format_rating)) {
                                    $format_rating = $user_rate->format_rating;
                                }
                            @endphp
                            @switch(Auth::user()->score_format)
                                @case('POINT_100')
                                    <form action="{{ route('variant.rate', $song_variant->id) }}" method="post"
                                        id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
                                        data-variant="{{ $song_variant->id }}">

                                        <div class="input-group">
                                            <div class="mb-3 w-100">
                                                <label for="scoreInput" class="form-label">You score</label>
                                                <input type="number" class="form-control" id="scoreInput"
                                                    placeholder="Max 100 without decimal" name="score" max="100"
                                                    min="0" step="1" value="{{ $format_rating }}">
                                            </div>
                                            <div class="w-100">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Button
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @break

                                @case('POINT_10_DECIMAL')
                                    <form action="{{ route('variant.rate', $song_variant->id) }}" method="post"
                                        id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
                                        data-variant="{{ $song_variant->id }}"
                                        data-scoreformat="{{ Auth::user()->score_format }}">

                                        <div class="input-group">
                                            <div class="mb-3 w-100">
                                                <label for="scoreInput" class="form-label">You score</label>
                                                <input type="number" class="form-control" id="scoreInput"
                                                    placeholder="Max 10 with decimal" name="score" max="10"
                                                    min="0" step=".1" value="{{ $format_rating }}">
                                            </div>
                                            <div class="w-100">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Button
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @break

                                @case('POINT_10')
                                    <form action="{{ route('variant.rate', $song_variant->id) }}" method="post"
                                        id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
                                        data-variant="{{ $song_variant->id }}"
                                        data-scoreformat="{{ Auth::user()->score_format }}">

                                        <div class="input-group">
                                            <div class="mb-3 w-100">
                                                <label for="scoreInput" class="form-label">You score</label>
                                                <input type="number" class="form-control" id="scoreInput"
                                                    placeholder="Max 10 without decimal" name="score" max="10"
                                                    min="0" step="1" value="{{ $format_rating }}">
                                            </div>
                                            <div class="w-100">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Button
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @break

                                @case('POINT_5')
                                    <form action="{{ route('variant.rate', $song_variant->id) }}" method="post"
                                        id="rating-form" class="d-flex flex-column py-4" data-variant="{{ $song_variant->id }}">
                                        <style>
                                            .rate {
                                                float: left;
                                                height: 46px;
                                                padding: 0 10px;
                                            }

                                            .rate:not(:checked)>input {
                                                position: absolute;
                                                top: -9999px;
                                            }

                                            .rate:not(:checked)>label {
                                                float: right;
                                                width: 1em;
                                                overflow: hidden;
                                                white-space: nowrap;
                                                cursor: pointer;
                                                font-size: 40px;
                                                color: #ccc;
                                            }

                                            .rate:not(:checked)>label:before {
                                                content: '★ ';
                                            }

                                            .rate>input:checked~label {
                                                color: #ffc700;
                                            }

                                            .rate:not(:checked)>label:hover,
                                            .rate:not(:checked)>label:hover~label {
                                                color: #deb217;
                                            }

                                            .rate>input:checked+label:hover,
                                            .rate>input:checked+label:hover~label,
                                            .rate>input:checked~label:hover,
                                            .rate>input:checked~label:hover~label,
                                            .rate>label:hover~input:checked~label {
                                                color: #c59b08;
                                            }
                                        </style>
                                        <div class="rate">
                                            <input type="radio" id="star5" name="score" value="100" />
                                            <label for="star5" title="text">5 stars</label>
                                            <input type="radio" id="star4" name="score" value="80" />
                                            <label for="star4" title="text">4 stars</label>
                                            <input type="radio" id="star3" name="score" value="60" />
                                            <label for="star3" title="text">3 stars</label>
                                            <input type="radio" id="star2" name="score" value="40" />
                                            <label for="star2" title="text">2 stars</label>
                                            <input type="radio" id="star1" name="score" value="20" />
                                            <label for="star1" title="text">1 star</label>
                                        </div>
                                    </form>
                                @break

                                @default
                                    <form action="{{ route('variant.rate', $song_variant->id) }}" method="post"
                                        id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
                                        data-variant="{{ $song_variant->id }}">

                                        <div class="input-group">
                                            <div class="mb-3 w-100">
                                                <label for="scoreInput" class="form-label">You score</label>
                                                <input type="number" class="form-control" id="scoreInput"
                                                    placeholder="Max 100 without decimal" name="score" max="100"
                                                    min="0" step="1" value="{{ $format_rating }}">
                                            </div>
                                            <div class="w-100">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Button
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                            @endswitch
                        </div>
                    @else
                        <div class="d-flex justify-content-center comment-form text-light text-center">
                            <h3>Please <a class="text-light" href="{{ route('login') }}">login</a>
                                <br>
                                or
                                <br>
                                <a class="text-light" href="{{ route('register') }}">register</a> for rate
                            </h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        {{-- Make Comment Section --}}
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
                <div>
                    <form action="{{ route('comments.store') }}" method="post" class="d-flex flex-column gap-2">
                        @csrf
                        <input type="hidden" name="song_variant_id" value="{{ $song_variant->id }}">
                        <textarea name="content" class="form-control" id="exampleFormControlTextarea1" rows="2"
                            placeholder="Comment ... (optional)" maxlength="255"></textarea>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        @endauth

        {{-- All Coments Section --}}
        {{-- @if ($comments_featured != null && count($comments_featured) > 0)
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
        @endif --}}

        @if (isset($comments))
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
                                                    href="{{ route('user.list', $comment->user->slug) }}">{{ $comment->user->name }}</a>
                                            </div>
                                            <div class="user-score">
                                                <i class="fa-solid fa-bookmark"></i>
                                            </div>
                                        </div>
                                        <div class="like-buttons">

                                            <form action="{{ route('comments.like', $comment->id) }}" method="post">
                                                @csrf
                                                <button class="no-deco text-light"
                                                    style="background-color: transparent;border:none;">{{ $comment->likesCount }}
                                                    <i class="fa-regular fa-thumbs-up"></i></button>
                                            </form>

                                            <form action="{{ route('comments.dislike', $comment->id) }}" method="post">
                                                @csrf
                                                <button class="no-deco text-light"
                                                    style="background-color: transparent;border:none;">{{ $comment->dislikesCount }}
                                                    <i class="fa-regular fa-thumbs-down"></i></button>
                                            </form>

                                        </div>
                                    </div>
                                    <div class="date">
                                        <span>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="comment-content">
                                        <span>{{ $comment->content }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <script>
        const player = new Plyr('#player');
    </script>

    @auth
        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const baseUrl = document.querySelector('meta[name="base-url"]').content;
            const token = localStorage.getItem('api_token');

            const rateForm = document.querySelector('#rating-form');
            const ratingBtn = document.querySelector('#rating-button');
            const scoreSpan = document.querySelector('#score-span');
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", (event) => {
                const likeBtn = document.querySelector('#like-button');
                const likesSpan = document.querySelector('#like-counter');

                const dislikeBtn = document.querySelector('#dislike-button');
                const dislikesSpan = document.querySelector('#dislike-counter');

                const favoriteBtn = document.querySelector('#favorite-button');

                likeBtn.addEventListener("click", likeVariant);
                dislikeBtn.addEventListener("click", dislikeVariant);
                favoriteBtn.addEventListener("click", toggleFavorite);

                function likeVariant() {
                    try {
                        fetch(baseUrl + "/api/variants/" + likeBtn.dataset.variant + "/like", {
                            headers: {
                                'X-Request-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Authorization': 'Bearer ' + token,
                            },
                            method: "POST",
                            body: JSON.stringify({
                                songVariant_id: likeBtn.dataset.variant,
                            }),
                        }).then(response => {
                            return response.json()
                        }).then((data) => {
                            likesSpan.textContent = data.likesCount;
                            dislikesSpan.textContent = data.dislikesCount;
                        });
                    } catch (error) {
                        console.log(error)
                    }
                }

                function dislikeVariant() {
                    try {
                        fetch(baseUrl + "/api/variants/" + dislikeBtn.dataset.variant + "/dislike", {
                            headers: {
                                'X-Request-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Authorization': 'Bearer ' + token,
                            },
                            method: "POST",
                            body: JSON.stringify({
                                songVariant_id: dislikeBtn.dataset.variant,
                            }),
                        }).then(response => {
                            return response.json()
                        }).then((data) => {
                            //console.log(data);
                            likesSpan.textContent = data.likesCount;
                            dislikesSpan.textContent = data.dislikesCount;
                        });
                    } catch (error) {
                        console.log(error)
                    }
                }

                function toggleFavorite() {
                    try {
                        fetch(baseUrl + "/api/variants/" + favoriteBtn.dataset.variant + "/favorite", {
                            headers: {
                                'X-Request-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Authorization': 'Bearer ' + token,
                            },
                            method: "POST",
                            body: JSON.stringify({
                                songVariant_id: favoriteBtn.dataset.variant,
                            }),
                        }).then(response => {
                            return response.json()
                        }).then((data) => {
                            //console.log(data);
                            //console.log(iFavorite.classList);
                            let iFavorite = document.querySelector('#i-favorite');
                            if (data.favorite == true) {
                                iFavorite.classList.remove('fa-regular');
                                iFavorite.classList.add('fa-solid');
                                favoriteBtn.classList.remove('btn-primary');
                                favoriteBtn.classList.add('btn-danger');
                            } else {
                                iFavorite.classList.remove('fa-solid');
                                iFavorite.classList.add('fa-regular');
                                favoriteBtn.classList.remove('btn-danger');
                                favoriteBtn.classList.add('btn-primary');
                            }
                        });
                    } catch (error) {
                        console.log(error)
                    }
                }

            });
        </script>

        @switch(Auth::user()->score_format)
            @case('POINT_100')
                <script>
                    rateForm.addEventListener("submit", function(event) {
                        event.preventDefault()

                        let userScore = document.querySelector('#scoreInput').value;

                        if (userScore != '' && userScore > 0 && userScore <= 100) {
                            rate(userScore)
                        }

                        function rate(userScore) {
                            try {
                                fetch(baseUrl + "/api/variants/" + rateForm.dataset.variant + "/rate", {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Authorization': 'Bearer ' + token,
                                    },
                                    method: "POST",
                                    body: JSON.stringify({
                                        songVariant_id: rateForm.dataset.variant,
                                        score: userScore,
                                    }),
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    //console.log(data);
                                    ratingBtn.classList.remove('btn-primary');
                                    ratingBtn.classList.add('btn-warning');
                                    scoreSpan.textContent = data.scoreString;

                                });
                            } catch (error) {
                                //console.log(error)
                            }
                        }

                    });
                </script>
            @break

            @case('POINT_10_DECIMAL')
                <script>
                    rateForm.addEventListener("submit", function(event) {
                        event.preventDefault()

                        let userScore = document.querySelector('#scoreInput').value;

                        if (userScore != '' && userScore > 0 && userScore <= 10) {
                            rate(userScore)
                        }

                        function rate(userScore) {
                            try {
                                fetch(baseUrl + "/api/variants/" + rateForm.dataset.variant + "/rate", {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Authorization': 'Bearer ' + token,
                                    },
                                    method: "POST",
                                    body: JSON.stringify({
                                        songVariant_id: rateForm.dataset.variant,
                                        score: userScore,
                                    }),
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    //console.log(data);
                                    ratingBtn.classList.remove('btn-primary');
                                    ratingBtn.classList.add('btn-warning');
                                    scoreSpan.textContent = data.scoreString;

                                });
                            } catch (error) {
                                //console.log(error)
                            }
                        }

                    });
                </script>
            @break

            @case('POINT_10')
                <script>
                    rateForm.addEventListener("submit", function(event) {
                        event.preventDefault()

                        let userScore = document.querySelector('#scoreInput').value;

                        if (userScore != '' && userScore > 0 && userScore <= 10) {
                            rate(userScore)
                            console.log('score: ' + userScore);
                        }

                        function rate(userScore) {
                            try {
                                fetch(baseUrl + "/api/variants/" + rateForm.dataset.variant + "/rate", {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Authorization': 'Bearer ' + token,
                                    },
                                    method: "POST",
                                    body: JSON.stringify({
                                        songVariant_id: rateForm.dataset.variant,
                                        score: userScore,
                                    }),
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    //console.log(data);
                                    ratingBtn.classList.remove('btn-primary');
                                    ratingBtn.classList.add('btn-warning');
                                    scoreSpan.textContent = data.scoreString;
                                });
                            } catch (error) {
                                //console.log(error)
                            }
                        }

                    });
                </script>
            @break

            @case('POINT_5')
                <script>
                    const checkboxes = document.querySelectorAll('#rating-form input[name="score"]');

                    function actualizarPuntuacionesSeleccionadas() {
                        const checkedValue = Array.from(checkboxes)
                            .filter(cb => cb.checked)
                            .map(cb => cb.value);

                        userScore = checkedValue.join();

                        if (checkedValue.join() != 0) {
                            rate(userScore)
                        }
                    }

                    checkboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', actualizarPuntuacionesSeleccionadas);
                    });

                    function rate(userScore) {
                        try {
                            fetch(baseUrl + "/api/variants/" + rateForm.dataset.variant + "/rate", {
                                headers: {
                                    'X-Request-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Authorization': 'Bearer ' + token,
                                },
                                method: "POST",
                                body: JSON.stringify({
                                    songVariant_id: rateForm.dataset.variant,
                                    score: userScore,
                                }),
                            }).then(response => {
                                return response.json()
                            }).then((data) => {
                                //console.log(data);
                                ratingBtn.classList.remove('btn-primary');
                                ratingBtn.classList.add('btn-warning');
                                scoreSpan.textContent = data.scoreString;
                            });
                        } catch (error) {
                            //console.log(error)
                        }
                    }
                </script>
            @break

            @default
                <script>
                    rateForm.addEventListener("submit", function(event) {
                        event.preventDefault()

                        let userScore = document.querySelector('#scoreInput').value;

                        if (userScore != '' && userScore > 0 && userScore <= 100) {
                            rate(userScore)
                        }

                        function rate(userScore) {
                            try {
                                fetch(baseUrl + "/api/variants/" + rateForm.dataset.variant + "/rate", {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Authorization': 'Bearer ' + token,
                                    },
                                    method: "POST",
                                    body: JSON.stringify({
                                        songVariant_id: rateForm.dataset.variant,
                                        score: userScore,
                                    }),
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    //console.log(data);
                                    ratingBtn.classList.remove('btn-primary');
                                    ratingBtn.classList.add('btn-warning');
                                    scoreSpan.textContent = data.scoreString;

                                });
                            } catch (error) {
                                //console.log(error)
                            }
                        }

                    });
                </script>
        @endswitch
    @endauth
@endsection
