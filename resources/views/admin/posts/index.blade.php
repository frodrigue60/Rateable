@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-dark">
                    {{-- CARD HEADER --}}
                    <div class="card-header">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.post.create') }}">CREATE</a>
                    </div>
                    {{-- CARD BODY --}}
                    <div class="card-body">
                        {{-- search form --}}
                        <form class="d-flex" action="{{ route('searchpost') }}" method="GET">
                            <input class="form-control me-2" type="text" name="search" placeholder="Search" required />
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
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
                                        <td>
                                            <a href="{{ route('admin.post.show', $post->id) }}"
                                                class="no-deco">{{ $post->title }}</a>
                                        </td>
                                        <td>
                                            @foreach ($post->tags as $tag)
                                                <span class="badge rounded-pill text-bg-dark">{{ $tag->name }}</span>
                                            @endforeach

                                        </td>
                                        <td>{{ $post->type }}</td>
                                        <td>{{ $post->averageRating / 10 }}</td>
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
                    {{-- CARD FOOTER --}}
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            {!! $posts->links() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
