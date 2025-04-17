@extends('layouts.app')

{{-- @php

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
@endphp --}}

{{-- @section('meta')
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
@endsection --}}
@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="" id="video_container">
                <video id="player"class="ratio-16x9" controls autoplay>
                    <source src="" type="video/webm" />
                </video>
            </div>

            <div class="d-flex ratio-16x9 justify-content-center">
                {{--  --}}
            </div>
        </div>

        <div class="text-light">
            <div class="mb-3">
                <h2>
                    <a href="{{ $post->url }}" class="text-decoration-none text-light">{{ $post->title }}
                        {{ $song->slug }}</a>
                </h2>
                <div class="my-2">
                    <span class="text-light">{{ $song->name }}</span> -
                    @foreach ($song->artists as $index => $artist)
                        @php
                            if ($artist->name_jp != null) {
                                $artistNameJp = '(' . $artist->name_jp . ')';
                            } else {
                                $artistNameJp = '';
                            }
                        @endphp
                        <a class="text-decoration-none text-light" href="{{ $artist->url }}">{{ $artist->name }}
                            {{ $artistNameJp }}</a>
                        @if ($index < count($song->artists) - 1)
                            ,
                        @endif
                    @endforeach

                </div>
                <div class="d-flex mb-2">
                    <span>Views {{ $song->viewsString }}</span>
                </div>
            </div>
            {{-- Actions buttons --}}
            <div class="d-flex justify-content-between">
                <div class="d-flex gap-3">
                    @guest
                        {{-- LIKES --}}
                        <div>
                            <button class="btn btn-primary rounded-pill"><i class="fa-regular fa-thumbs-up"></i>
                                <span id="like-counter">{{ $song->likesCount }}</span>
                            </button>
                        </div>
                        {{-- DISLIKES --}}
                        <div>
                            <button class="btn btn-primary rounded-pill"><i class="fa-regular fa-thumbs-down"></i>
                                <span id="dislike-counter">{{ $song->dislikesCount }}</span>
                            </button>
                        </div>
                    @endguest
                    @auth
                        {{-- LIKES --}}
                        <div>
                            <button id="like-button" data-song="{{ $song->id }}"
                                class="btn btn-primary rounded-pill"><i class="fa-regular fa-thumbs-up"></i>
                                <span id="like-counter">{{ $song->likes()->count() }}</span>
                            </button>
                        </div>
                        {{-- DISLIKES --}}
                        <div>
                            <button id="dislike-button" class="btn btn-primary rounded-pill"
                                data-song="{{ $song->id }}">
                                <i class="fa-regular fa-thumbs-down"></i> <span
                                    id="dislike-counter">{{ $song->dislikes()->count() }}</span>
                            </button>
                        </div>
                    @endauth

                    {{-- SCORE --}}
                    <div>
                        @php
                            if (Auth::check()) {
                                $class = $song->userRating != null ? 'btn-warning' : 'btn-primary';
                            } else {
                                $class = 'btn-primary';
                            }

                        @endphp
                        <button class="btn {{ $class }} rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#rating-modal" id="rating-button">
                            <i class="fa fa-star" aria-hidden="true"></i> <span
                                id="score-span">{{ $song->score }}</span>
                        </button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit"
                        class="btn {{ $song->isFavorited() ? 'btn-danger' : 'btn-primary' }} rounded-pill d-flex gap-2 align-items-center"
                        id="favorite-button" data-song="{{ $song->id }}"><i
                            class="fa-{{ $song->isFavorited() ? 'solid' : 'regular' }} fa-heart"
                            id="i-favorite"></i><span class="d-none d-sm-flex">
                            Favorite</span></button>


                    <button type="button" class="btn btn-primary rounded-pill d-flex gap-2 align-items-center"
                        data-bs-toggle="modal" data-bs-target="#report-modal">
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
                        <form action="" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Report {{ $post->title }}
                                    {{ $song->slug }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="song_id" value="{{ $song->id }}">
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
                            if ($song->userRating != null) {
                                $user_score = $song->userRating->formatRating;
                            } else {
                                $user_score = 0;
                            }
                        @endphp
                        <div class="text-light d-flex flex-column align-items-center">
                            @php
                                $format_rating = '';
                                if (isset($userRating->format_rating)) {
                                    $format_rating = $userRating->format_rating;
                                }
                            @endphp
                            @switch(Auth::user()->score_format)
                                @case('POINT_100')
                                    <form action="" method="post"
                                        id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
                                        data-song="{{ $song->id }}">

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
                                    <form action="" method="post"
                                        id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
                                        data-song="{{ $song->id }}"
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
                                    <form action="" method="post"
                                        id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
                                        data-song="{{ $song->id }}"
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
                                    <form action="" method="post"
                                        id="rating-form" class="d-flex flex-column py-4" data-song="{{ $song->id }}">
                                        @php
                                            $formatRating = 0;
                                            if (isset($userRating)) {
                                                $formatRating = $userRating->formatRating;
                                            }
                                        @endphp
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
                                            <input type="radio" id="star5" name="score" value="100"
                                                {{ $formatRating == 100 ? 'checked' : '' }} />
                                            <label for="star5" title="text">5 stars</label>
                                            <input type="radio" id="star4" name="score" value="80"
                                                {{ $formatRating == 80 ? 'checked' : '' }} />
                                            <label for="star4" title="text">4 stars</label>
                                            <input type="radio" id="star3" name="score" value="60"
                                                {{ $formatRating == 60 ? 'checked' : '' }} />
                                            <label for="star3" title="text">3 stars</label>
                                            <input type="radio" id="star2" name="score" value="40"
                                                {{ $formatRating == 40 ? 'checked' : '' }} />
                                            <label for="star2" title="text">2 stars</label>
                                            <input type="radio" id="star1" name="score" value="20"
                                                {{ $formatRating == 20 ? 'checked' : '' }} />
                                            <label for="star1" title="text">1 star</label>
                                        </div>
                                    </form>
                                @break

                                @default
                                    <form action="" method="post"
                                        id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
                                        data-song="{{ $song->id }}">

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
                    <form id="commnent-form" action="" method="post"
                        class="d-flex flex-column gap-2">
                        @csrf
                        <input type="hidden" id="song_id" name="song_id" value="{{ $song->id }}">
                        <textarea id="comment-content" name="content" class="form-control" id="exampleFormControlTextarea1" rows="2"
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
            <div>
                <h4 class="text-light my-2">Recents comments</h4>
            </div>
            <div class="my-2" id="comments-container">
                {{-- PARTIAL COMMENTS --}}
                @include('partials.comments.comments')
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

            const commentForm = document.querySelector('#commnent-form');
            const commentContainer = document.querySelector('#comments-container');
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", (event) => {
                const likeBtn = document.querySelector('#like-button');
                const likesSpan = document.querySelector('#like-counter');

                const dislikeBtn = document.querySelector('#dislike-button');
                const dislikesSpan = document.querySelector('#dislike-counter');

                const favoriteBtn = document.querySelector('#favorite-button');

                likeBtn.addEventListener("click", likeSong);
                dislikeBtn.addEventListener("click", dislikeSong);
                favoriteBtn.addEventListener("click", toggleFavorite);

                function likeSong() {
                    try {
                        fetch(baseUrl + "/api/songs/" + likeBtn.dataset.song + "/like", {
                            headers: {
                                'X-Request-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Authorization': 'Bearer ' + token,
                            },
                            method: "POST",
                            body: JSON.stringify({
                                song_id: likeBtn.dataset.song,
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

                function dislikeSong() {
                    try {
                        fetch(baseUrl + "/api/songs/" + dislikeBtn.dataset.song + "/dislike", {
                            headers: {
                                'X-Request-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Authorization': 'Bearer ' + token,
                            },
                            method: "POST",
                            body: JSON.stringify({
                                song_id: dislikeBtn.dataset.song,
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
                        fetch(baseUrl + "/api/songs/" + favoriteBtn.dataset.song + "/favorite", {
                            headers: {
                                'X-Request-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Authorization': 'Bearer ' + token,
                            },
                            method: "POST",
                            body: JSON.stringify({
                                song_id: favoriteBtn.dataset.song,
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

                commentForm.addEventListener("submit", function(event) {
                    event.preventDefault()
                    console.log('listen form submit');

                    let commentTextarea = document.querySelector('#comment-content');
                    let songId = document.querySelector('#song_id').value;

                    if (commentTextarea.value != '') {
                        makeComment(commentTextarea.value)
                    }

                    function makeComment(commentContent) {
                        try {
                            fetch(baseUrl + "/api/comments", {
                                headers: {
                                    'X-Request-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Authorization': 'Bearer ' + token,
                                },
                                method: "POST",
                                body: JSON.stringify({
                                    content: commentContent,
                                    song_id: songId,
                                }),
                            }).then(response => {
                                return response.json()
                            }).then((data) => {
                                //console.log(data);
                                commentTextarea.value = '';
                                //commentContainer.innerHTML += data.comment;
                                commentContainer.insertAdjacentHTML('afterbegin', data.comment);
                            });
                        } catch (error) {
                            //console.log(error)
                        }
                    }

                });


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
                                fetch(baseUrl + "/api/songs/" + rateForm.dataset.song + "/rate", {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Authorization': 'Bearer ' + token,
                                    },
                                    method: "POST",
                                    body: JSON.stringify({
                                        song_id: rateForm.dataset.song,
                                        score: userScore,
                                    }),
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    //console.log(data);
                                    ratingBtn.classList.remove('btn-primary');
                                    ratingBtn.classList.add('btn-warning');
                                    scoreSpan.textContent = data.average;

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
                                fetch(baseUrl + "/api/songs/" + rateForm.dataset.song + "/rate", {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Authorization': 'Bearer ' + token,
                                    },
                                    method: "POST",
                                    body: JSON.stringify({
                                        song_id: rateForm.dataset.song,
                                        score: userScore,
                                    }),
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    //console.log(data);
                                    ratingBtn.classList.remove('btn-primary');
                                    ratingBtn.classList.add('btn-warning');
                                    scoreSpan.textContent = data.average;

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
                                fetch(baseUrl + "/api/songs/" + rateForm.dataset.song + "/rate", {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Authorization': 'Bearer ' + token,
                                    },
                                    method: "POST",
                                    body: JSON.stringify({
                                        song_id: rateForm.dataset.song,
                                        score: userScore,
                                    }),
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    //console.log(data);
                                    ratingBtn.classList.remove('btn-primary');
                                    ratingBtn.classList.add('btn-warning');
                                    scoreSpan.textContent = data.average;
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
                            fetch(baseUrl + "/api/songs/" + rateForm.dataset.song + "/rate", {
                                headers: {
                                    'X-Request-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Authorization': 'Bearer ' + token,
                                },
                                method: "POST",
                                body: JSON.stringify({
                                    song_id: rateForm.dataset.song,
                                    score: userScore,
                                }),
                            }).then(response => {
                                return response.json()
                            }).then((data) => {
                                console.log(data);
                                ratingBtn.classList.remove('btn-primary');
                                ratingBtn.classList.add('btn-warning');
                                scoreSpan.textContent = data.average;
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
                                fetch(baseUrl + "/api/songs/" + rateForm.dataset.song + "/rate", {
                                    headers: {
                                        'X-Request-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Authorization': 'Bearer ' + token,
                                    },
                                    method: "POST",
                                    body: JSON.stringify({
                                        song_id: rateForm.dataset.song,
                                        score: userScore,
                                    }),
                                }).then(response => {
                                    return response.json()
                                }).then((data) => {
                                    console.log(data);
                                    ratingBtn.classList.remove('btn-primary');
                                    ratingBtn.classList.add('btn-warning');
                                    scoreSpan.textContent = data.average;

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
