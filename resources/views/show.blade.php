@extends('layouts.app')
<!doctype html>
<head>
    @vite(['resources/css/fivestars.css'])
</head>

@section('content')
    <div class="container">
        
        @if (session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- IF GUEST --}}
        @guest
            <div class="col-10 mx-auto">
                <div class="card">
                    <div class="card-header row justify-content-between">
                        <div class="col-12">
                            <h5 class="card-title">{{ $post->title }}</h5>
                        </div>
                    </div>
                    <div class="card-body ratio ratio-16x9">
                        <iframe id="id_iframe" src="{{ $post->ytlink }}" title="" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture muted;"
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
        @endguest

        {{-- IF AUTH USER --}}
        @auth
            <div class="col-10 mx-auto">
                <div class="card">
                    <div class="card-header row justify-content-between">
                        <div class="col-9">
                            <h5 class="card-title">{{ $post->title }} - {{ $post->averageRating/20 }} <i class="fa fa-star"></i></h5>
                        </div>
                        <div class="col-md-auto">
                            @if ($post->liked())
                                <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-danger" id="like">Favorite <i
                                            class="fa fa-heart"></i></button>
                                </form>
                            @else
                                <form action="{{ route('like.post', $post->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-success" id="like">Favorite <i
                                            class="fa fa-heart"></i></button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body ratio ratio-16x9">
                        <iframe id="id_iframe" src="{{ $post->ytlink }}" title="" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; muted;"
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
        @endauth

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
                                <h2>Average Score: <strong> {{ $post->averageRating/20 }}/5 <i class="fa fa-star"></i> </strong></h2>
                                <h4>Song title: <strong>a song title</strong></h4>
                                <h4>Song artist: <strong>a artist</strong></h4>
                            </div>

                            @auth
                            <div class="d-flex justify-content-center">
                                
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
                                                <p class="text-center">click the stars</p>
                                                <button type="submit" class="btn btn-primary">Send</button>
                                            </div>
                                        </form>
                                    </div>
                                
                            </div>
                            @endauth
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            {{-- comment <button type="button" class="btn btn-primary">Save</button>--}}
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
