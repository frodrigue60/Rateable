@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- IF AUTH USER --}}
        @auth
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="row justify-content-between">
                                <div class="col-9">
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                </div>
                                <div class="col-3">
                                    @if ($post->liked())
                                        <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                            @csrf
                                            <button class="btn btn-sm btn-danger" id="like">Favorite <i
                                                    class="fa fa-heart"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('like.post', $post->id) }}" method="post">
                                            @csrf
                                            <button class="btn btn-sm btn-success" id="like">Favorite <i
                                                    class="fa fa-heart"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body ratio ratio-16x9">
                            <iframe width="1424" height="620" src="{{$post->ytlink}}" frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                    {{-- RIGHT PANNEL --}}
                    <div class="card">
                        {{-- CARD HEADER --}}
                        <div class="card-header">
                            @foreach ($post->tags as $tag)
                                <span class="badge rounded-pill text-bg-dark">{{ $tag->name }}</span>
                            @endforeach
                        </div>

                        {{-- CARD BODY --}}
                        <div class="card-body">
                            <div class="row text-center">
                                <h2>Average Score</h2>
                                <h3>{{ $post->averageRating / 1 }}</h3>
                                <h4>Song title: <strong>a song title</strong></h4>
                                <h4>Song artist: <strong>a artist</strong></h4>
                            </div>
                            <form name="add-blog-post-form" id="add-blog-post-form" method="post"
                                action="{{ route('post.addrate', $post->id) }}" enctype="multipart/form-data">
                                @csrf
                                <label for="scoreInput" class="form-label">Score 0 - 100</label>
                                <input type="number" class="form-control" id="scoreInput" placeholder="Score" id="score"
                                    name="score" max="100" min="0" value="{{ $post->userAverageRating / 1 }}">
                                <br>
                                <div class="row">
                                    <button type="submit" class="btn btn-primary">Rate</button>
                                </div>
                            </form>
                            <hr>
                            <div class="row">
                                <a name="" id="" class="btn btn-success" href="#"
                                    role="button">Spotify</a>
                            </div>
                            <br>
                            <div class="row">
                                <a name="" id="" class="btn btn-success" href="#" role="button">Apple
                                    Music</a>
                            </div>
                        </div>
                        {{-- CARD FOOTER --}}
                        <div class="card-footer">
                        </div>
                    </div>
                </div>

            </div>

        @endauth

        {{-- IF GUEST --}}
        @guest
            <div class="col-9 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ $post->title }}</h5>
                    </div>
                    <div class="card-body ratio ratio-16x9">
                        <iframe id="id_iframe" src="{{ $post->ytlink }}" title="" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture muted;"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="card-footer">
                        <a name="" id="" class="btn btn-success" href="#" role="button">Spotify</a>
                        <a name="" id="" class="btn btn-success" href="#" role="button">Apple Music</a>
                    </div>
                </div>
            </div>
        @endguest

    </div>
@endsection
