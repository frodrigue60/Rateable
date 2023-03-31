@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" href="" role="button">ADD SONG</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    <form class="d-flex" action="{{ route('search.tag') }}" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                {{-- <th scope="col">ID</th> --}}
                                <th scope="col">Song Name</th>
                                <th scope="col">Songs Artist</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Src1 | Src 2</th>
                                <th scope="col">Theme</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($post->songs as $song)
                                <tr>
                                    {{-- <td>{{ $song->id }}</td> --}}
                                    <td>{{ $song->song_romaji != null ? $song->song_romaji : $song->song_en }}</td>
                                    <td>
                                        @isset($song->artist)
                                            {{ $song->artist->name }}
                                        @endisset
                                    </td>
                                    <td>
                                        @foreach ($song->tags as $tag)
                                            [{{$tag->name}}]
                                        @endforeach
                                    </td>
                                    <td>
                                        @if (isset($song->ytlink))
                                            OK
                                        @else
                                            N/A
                                        @endif
                                        |
                                        @if (isset($song->scndlink))
                                            OK
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $song->suffix != null ? $song->suffix : $song->type }}</td>
                                    <td>
                                        @if (Auth::user()->isEditor() | Auth::user()->isAdmin())
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('song.post.edit', $song->id) }}">Edit</a>
                                            <a class="btn btn-sm btn-danger"
                                                href="{{ route('song.post.destroy', $song->id) }}">Delete</a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{-- {!! $tags->links() !!} --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
