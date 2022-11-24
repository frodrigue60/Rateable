@extends('layouts.app')

@section('content')
    <div class="container" style="width: 85%">
        <div class="card">
            <div class="card-header">{{ $post->title }} -
                @foreach ($post->tags as $tag)
                    <span class="badge rounded-pill text-bg-dark">{{ $tag->name }}</span>
                @endforeach
            </div>
            <div class="card-body ratio ratio-21x9">
                <iframe id="id_iframe" src="{{ $post->ytlink }}" title="" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture muted;"
                    allowfullscreen>
                </iframe>
            </div>
            <div class="card-footer">
                <form name="add-blog-post-form" id="add-blog-post-form" method="post"
                    action="{{ route('post.addrate', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    <label for="scoreInput" class="form-label">Score</label>
                    <input type="number" class="form-control" id="scoreInput" placeholder="Score" id="score"
                        name="score" max="10">
                    <br>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
