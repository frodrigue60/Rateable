@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.post.create') }}">CREATE POST</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    <form class="d-flex" action="{{ route('admin.post.search') }}" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Type-themeNum</th>
                                <th scope="col">Song</th>
                                <th scope="col">Artist</th>
                                <th scope="col">Status</th>
                                @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                    <th scope="col">Actions</th>
                                @endif
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
                                            <span
                                                class="badge rounded-pill text-bg-dark">{{ isset($tag) ? $tag->name : 'N/A' }}</span>
                                        @endforeach

                                    </td>
                                    <td>{{ $post->type }}-{{ $post->themeNum }}</td>
                                    <td>
                                        @if (isset($post->song_id))
                                            @if (isset($post->song->song_romaji))
                                                {{ $post->song->song_romaji }}
                                            @else
                                                @if (isset($post->song->song_en))
                                                    {{ $post->song->song_en }}
                                                @else
                                                    N/A
                                                @endif
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($post->artist_id))
                                            @isset($post->artist->name)
                                                <a
                                                    href="{{ route('from.artist', $post->artist->name_slug) }}">{{ $post->artist->name }}</a>
                                            @endisset
                                        @else
                                        N/A
                                        @endif
                                    </td>

                                    @if (Auth::User()->isCreator())
                                        <td>
                                            @if ($post->status == null)
                                                <button disabled class="btn btn-sm btn-secondary">N/A</button>
                                            @endif
                                            @if ($post->status == 'stagged')
                                                <button disabled class="btn btn-warning btn-sm"> <i class="fa fa-clock-o"
                                                        aria-hidden="true"></i></button>
                                            @endif
                                            @if ($post->status == 'published')
                                                <button disabled class="btn btn-primary btn-sm"> <i class="fa fa-check"
                                                        aria-hidden="true"></i></button>
                                            @endif
                                        </td>
                                    @endif
                                    @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                        <td>
                                            @if ($post->status == null)
                                                <button disabled="disabled" class="btn btn-sm btn-secondary">N/A</button>
                                            @endif
                                            @if ($post->status == 'stagged')
                                                <form action="{{ route('admin.post.approve', $post->id) }}" method="post">
                                                    @csrf
                                                    <button class="btn btn-warning btn-sm"> <i class="fa fa-clock-o"
                                                            aria-hidden="true"> {{ $post->id }}</i></button>
                                                </form>
                                            @endif
                                            @if ($post->status == 'published')
                                                <form action="{{ route('admin.post.unapprove', $post->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <button class="btn btn-primary btn-sm"> <i class="fa fa-check"
                                                            aria-hidden="true"> {{ $post->id }}</i></button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.post.edit', $post->id) }}"
                                                class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o"
                                                    aria-hidden="true"></i> {{ $post->id }}</a>
                                            <a href="{{ route('admin.post.destroy', $post->id) }}"
                                                class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i>
                                                {{ $post->id }}</a>

                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
