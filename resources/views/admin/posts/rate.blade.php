@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Calificar: {{ $post->title }}</div>

                    <div class="card-body">
                        <h4>
                            {{ $post->title }}
                        </h4>
                        <div class="card-body">
                            <form name="add-blog-post-form" id="add-blog-post-form" method="post"
                                action="{{ route('post.addrate', $post->id) }}" enctype="multipart/form-data">
                                @csrf

                                <label for="scoreInput" class="form-label">Score</label>
                                <input type="number" class="form-control" id="scoreInput" placeholder="Score"
                                    id="score" name="score" max="10">
                                <br>
                                <br>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
