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

@section('meta')
    <meta name="song-id" content="{{ $song->id }}">
    @auth
        <meta name="score-format" content="{{ Auth::User()->score_format }}">
    @endauth
@endsection

@section('content')
    <div class="container">
        <!-- OPTIONS BUTTONS -->
        <div class="d-flex mb-2 gap-3">
            @foreach ($song->songVariants as $variant)
                <button class="btn btn-sm btn-primary btnVersion" data-variant-id="{{ $variant->id }}">Version
                    {{ $variant->version_number }}</button>
            @endforeach
        </div>
        <!-- VIDEO CONTAINER -->
        <div class="mb-2" {{-- style="border: solid 1px red;" --}}>
            <div class="" id="video_container">
                <video id="player"class="ratio-16x9 w-100" controls autoplay>
                    <source id="video-source" src="" type="video/webm" />
                </video>
            </div>
        </div>

        <div class="">
            <div class="">
                <!--- TITLE -->
                <h1 class="fs-2">
                    <a href="{{ $post->url }}" class="">{{ $post->title }}
                        {{ $song->slug }}</a>
                </h1>
                {{-- <div class="row">
                    <div class="col-12 col-md-9 col-lg-10">
                        <h2>
                            <a href="{{ $post->url }}" class="text-decoration-none ">{{ $post->title }}
                                {{ $song->slug }}</a>
                        </h2>
                    </div>

                    <div class="col-12 col-md-3 col-lg-2">
                        <select class="form-select" aria-label="Default select example" id="select-variant">
                            @foreach ($song->songVariants as $variant)
                                <option value="{{ $variant->id }}">Version {{ $variant->version_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}

                <!-- SONG INFO -->
                <div class="mb-2">
                    <h5 class="">{{ $song->name }} -
                        @foreach ($song->artists as $index => $artist)
                            @php
                                if ($artist->name_jp != null) {
                                    $artistNameJp = '(' . $artist->name_jp . ')';
                                } else {
                                    $artistNameJp = '';
                                }
                            @endphp
                            <a class="text-decoration-none " href="{{ $artist->url }}">{{ $artist->name }}
                                {{ $artistNameJp }}</a>
                            @if ($index < count($song->artists) - 1)
                                ,
                            @endif
                        @endforeach
                    </h5>
                </div>
            </div>
            <!-- ACTIONS BUTTONS -->
            <div class="mb-3 d-flex justify-content-between">
                <div class="d-flex gap-3">
                    <div>
                        <button class="btn btn-primary rounded-pill">
                            <i class="fa-regular fa-eye"></i> <span> {{ $song->viewsString }}</span>
                        </button>
                    </div>
                    <!-- GUEST -->
                    @guest
                        <!-- LIKES -->
                        <div>
                            <button class="btn btn-primary rounded-pill"><i class="fa-regular fa-thumbs-up"></i>
                                <span id="like-counter">{{ $song->likesCount }}</span>
                            </button>
                        </div>
                        <!-- DISLIKES -->
                        <div>
                            <button class="btn btn-primary rounded-pill"><i class="fa-regular fa-thumbs-down"></i>
                                <span id="dislike-counter">{{ $song->dislikesCount }}</span>
                            </button>
                        </div>
                    @endguest
                    <!-- AUTH -->
                    @auth
                        <!-- LIKES -->
                        <div>
                            <button id="like-button" data-song="{{ $song->id }}" class="btn btn-primary rounded-pill"><i
                                    class="fa-regular fa-thumbs-up"></i>
                                <span id="like-counter">{{ $song->likes()->count() }}</span>
                            </button>
                        </div>
                        <!-- DISLIKES -->
                        <div>
                            <button id="dislike-button" class="btn btn-primary rounded-pill" data-song="{{ $song->id }}">
                                <i class="fa-regular fa-thumbs-down"></i> <span
                                    id="dislike-counter">{{ $song->dislikes()->count() }}</span>
                            </button>
                        </div>
                    @endauth

                    <!-- SCORE -->
                    <div>
                        @php
                            if (Auth::check() && isset($song->rawUserScore)) {
                                $class = $song->rawUserScore != null ? 'btn-warning' : 'btn-primary';
                            } else {
                                $class = 'btn-primary';
                            }

                        @endphp
                        <button class="btn {{ $class }} rounded-pill d-flex flex-row gap-2 align-items-center"
                            data-bs-toggle="modal" data-bs-target="#rating-modal" id="rating-button">
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <span id="score-span" class="">{{ $song->formattedScore }}</span>
                        </button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <!-- FAVORITE BUTTON -->
                    <button type="submit"
                        class="btn {{ $song->isFavorited() ? 'btn-danger' : 'btn-primary' }} rounded-pill d-flex flex-row gap-2 align-items-center"
                        id="favorite-button" data-song="{{ $song->id }}"><i
                            class="fa-{{ $song->isFavorited() ? 'solid' : 'regular' }} fa-heart" id="i-favorite"></i>
                        <span class="d-sm-block d-none ">
                            Favorite</span></button>

                    <!-- REPORT BUTTON -->
                    <button type="button" class="btn btn-primary rounded-pill d-flex flex-row gap-2 align-items-center"
                        data-bs-toggle="modal" data-bs-target="#report-modal">
                        <i class="fa-solid fa-triangle-exclamation"></i> <span class="d-sm-block d-none ">Report</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Report Modal --}}
        @include('partials.songs.show.report-modal')

        {{-- Rate Modal --}}
        @include('partials.songs.show.rating.modal')

        {{-- Make Comment Section --}}

        <div class="mb-3">
            <h3 class="">Comments</h3>

            @include('partials.songs.show.comment-form')
        </div>

        {{-- All Coments Section --}}
        @if (isset($comments))
            <div class="">
                <h4 class="">Recents comments</h4>

                <div class="" id="comments-container">
                    {{-- PARTIAL COMMENTS --}}
                    @include('partials.songs.show.comments.comments')
                </div>
            </div>
        @else
            <div>
                <h4 class="">No comments</h4>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>

    @vite([
        'resources/js/api_get_video.js',
        'resources/js/modules/comments/deleteComment.js'])

    @auth
        <script type="module">
            //const API = await import('{{ Vite::asset('resources/js/api/index.js') }}');

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const baseUrl = document.querySelector('meta[name="base-url"]').content;
            const songId = document.querySelector('meta[name="song-id"]').content;
            const token = localStorage.getItem('api_token');
            const scoreFormat = document.querySelector('meta[name="score-format"]').content;
            const ratingForm = document.querySelector('#rating-form');
            const ratingBtn = document.querySelector('#rating-button');
            const scoreSpan = document.querySelector('#score-span');

            const commentForm = document.querySelector('#commnent-form');
            const commentContainer = document.querySelector('#comments-container');

            const likeBtn = document.querySelector('#like-button');
            const likesSpan = document.querySelector('#like-counter');

            const dislikeBtn = document.querySelector('#dislike-button');
            const dislikesSpan = document.querySelector('#dislike-counter');

            const favoriteBtn = document.querySelector('#favorite-button');
            let headersData = {};

            likeBtn.addEventListener("click", likeSong);
            dislikeBtn.addEventListener("click", dislikeSong);
            favoriteBtn.addEventListener("click", toggleFavorite);

            //console.log(API);

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

            if (scoreFormat == 'POINT_5') {
                let checkboxes = document.querySelectorAll('#rating-form input[name="score"]');
                let userScore = 0;

                function actualizarPuntuacionesSeleccionadas() {
                    let checkedValue = Array.from(checkboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    userScore = checkedValue.join();

                    if ((checkedValue.join() > 0) && (checkedValue.join() <= 100)) {
                        rate(userScore)
                    }
                }

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', actualizarPuntuacionesSeleccionadas);
                });
            }

            ratingForm.addEventListener("submit", function(event) {
                event.preventDefault()
                let userScore = document.querySelector('#scoreInput').value;

                if ((userScore != '') && (userScore > 0) && (userScore <= 100)) {
                    rate(userScore)
                }

                console.log(userScore);
            });

            function rate(userScore) {
                try {
                    fetch(baseUrl + "/api/songs/" + ratingForm.dataset.song + "/rate", {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Authorization': 'Bearer ' + token,
                        },
                        method: "POST",
                        body: JSON.stringify({
                            song_id: ratingForm.dataset.song,
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

            commentForm.addEventListener("submit", function(event) {
                event.preventDefault()
                console.log('listen form submit');

                let commentTextarea = document.querySelector('#comment-content');

                if (commentTextarea.value != '') {
                    makeComment(commentTextarea.value)
                }

                function makeComment(commentContent) {
                    try {
                        fetch(baseUrl + "/api/songs/comments", {
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
                            console.log(data);
                            commentTextarea.value = '';
                            //commentContainer.innerHTML += data.comment;
                            commentContainer.insertAdjacentHTML('afterbegin', data.comment);
                        });
                    } catch (error) {
                        //console.log(error)
                    }
                }

            });

        </script>
    @endauth
@endsection
