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
                    <div class="card h-100">
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
                            <iframe width="1424" height="620" src="{{ $post->ytlink }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>
                <div class="col">
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
                            <div class="row text-center text-dark">
                                <h2>Average Score: <strong> {{ $post->averageRating / 1 }} </strong></h2>
                                <h4>Song title: <strong>a song title</strong></h4>
                                <h4>Song artist: <strong>a artist</strong></h4>
                            </div>
                            {{--  
                            <form name="add-blog-post-form" id="add-blog-post-form" method="post"
                                action="{{ route('post.addrate', $post->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-floating">
                                    <input name="score" max="100" min="0" type="number" class="form-control"
                                        id="floatingScore" placeholder="{{ $post->userAverageRating / 1 }}">
                                    <label for="floatingScore">Score</label>
                                </div>
                                <br>
                                <div class="row">
                                    <button type="submit" class="btn btn-primary">Rate</button>
                                </div>
                            </form> --}}


                            <div class="d-flex justify-content-center">
                                <div class="cont">
                                    <div class="stars">
                                        <form action="{{ route('post.addrate', $post->id) }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
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
                                                <p>click the stars</p>
                                                <button type="submit" class="btn btn-primary">Send</button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </div>

                            </div>

                            <hr>
                            <div class="row">
                                <a name="" id="" class="btn btn-success" href="#"
                                    role="button">Spotify</a>
                            </div>
                            <br>
                            <div class="row">
                                <a name="" id="" class="btn btn-success" href="#"
                                    role="button">Apple
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
                        <a name="" id="" class="btn btn-success" href="#" role="button">Apple
                            Music</a>
                    </div>
                </div>
            </div>
        @endguest
    </div>
@endsection
