@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Index.blade.php <a class="btn btn-sm btn-primary"
                            href="{{ route('admin.post.create') }}">CREATE</a></div>

                    <div class="card-body">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Tags</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">AvgScore</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr>

                                        <th scope="row">{{ $post->id }}</th>
                                        <td><a href="{{ route('admin.post.show', $post->id) }}" class="no-deco text-dark">{{ $post->title }}</a></td>
                                        <td>
                                            @foreach ($post->tags as $tag)
                                                
                                            <span class="badge rounded-pill text-bg-dark">{{ $tag->name }}</span>
                                                
                                            @endforeach
                                            
                                        </td>
                                        <td>{{ $post->type }}</td>
                                        <td>{{ $post->averageRating/10 }}</td>
                                        <td>
                                            <a href="{{ route('admin.post.edit', $post->id) }}"><button type="button"
                                                    class="btn btn-success btn-sm">Edit {{ $post->id }}</button></a>
                                            <a href="{{ route('admin.post.destroy', $post->id) }}"><button type="button"
                                                    class="btn btn-danger btn-sm">Delete {{ $post->id }}</button></a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
