@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
        @endif
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <div class="col-10">
                                <h6>{{ $post->title }}</h6>
                            </div>
                            <div class="col-2">
                                @auth
                                    @if ($post->liked())
                                        <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                            @csrf
                                            <button class="btn btn-danger">Favorite
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                    fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('like.post', $post->id) }}" method="post">
                                            @csrf
                                            <button class="btn btn-success">Favorite
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                    fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        <div class="card-body ratio ratio-16x9">
                            <iframe id="id_iframe" src="{{ $post->ytlink }}" title="" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture muted;"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>


            @auth
                
            
            <div class="col-4">
                <div class="card">
                    <div class="card-header text-dark bg-light">
                        @foreach ($post->tags as $tag)
                            <span class="badge rounded-pill text-bg-dark">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <form name="add-blog-post-form" id="add-blog-post-form" method="post"
                        action="{{ route('post.addrate', $post->id) }}" enctype="multipart/form-data">
                        <div class="card-body">
                            @csrf
                            <label for="scoreInput" class="form-label">Score 0 - 100</label>
                            <input type="number" class="form-control" id="scoreInput" placeholder="Score" id="score"
                                name="score" max="100" min="0">
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary">Rate</button>
                            </div>
                        </div>
                </div>
                </form>
                <div class="card-footer">
                </div>
            </div>
            @endauth



        </div>


    </div>
@endsection
