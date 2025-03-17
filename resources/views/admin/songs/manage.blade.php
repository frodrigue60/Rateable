@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.posts.songs.add', $post->id) }}" role="button">ADD
                        SONG</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    {{-- <form class="d-flex text-light gap-3" action="" method="GET">
                        <div class="mb-3 d-flex w-100 flex-column">
                            <label for="" class="form-label">Season</label>
                            <select class="form-select form-select-lg" name="" id="">
                                <option value="">Option 1</option>
                            </select>
                        </div>
                        <div class="mb-3 d-flex w-100 flex-column">
                            <label for="" class="form-label">Year</label>
                            <select class="form-select form-select-lg" name="" id="">
                                <option value="">Option 1</option>
                            </select>
                        </div>

                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form> --}}
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Song Name</th>
                                <th scope="col">Songs Artist</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Variants</th>
                                <th scope="col">Theme</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($post->songs as $song)
                                @php
                                    if ($song->song_romaji != null) {
                                        $song_name = $song->song_romaji;
                                    } else {
                                        if ($song->song_en != null) {
                                            $song_name = $song->song_en;
                                        } else {
                                            $song_name = $song->song_jp;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $song->id }}</td>
                                    <td>
                                        <a class="text-light" href="{{ $song->post->url }}">{{ $song_name }}</a>
                                    </td>
                                    <td>
                                        @isset($song->artist)
                                            <a class="text-light"
                                                href="{{ route('artists.show', [$song->artist->id, $song->artist->name_slug]) }}">{{ $song->artist->name }}</a>
                                        @endisset
                                        @isset($song->artists)
                                            {{ count($song->artists) }}
                                        @endisset
                                    </td>
                                    <td>
                                        {{ $song->season->name }} {{ $song->year->name }}
                                    </td>
                                    <td>{{ count($song->songVariants) }}</td>
                                    <td>{{ $song->slug != null ? $song->slug : $song->type }}</td>
                                    <td>
                                        @if (Auth::user()->isEditor() | Auth::user()->isAdmin())
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('admin.songs.edit', $song->id) }}"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                            <a class="btn btn-sm btn-danger"
                                                href="{{ route('admin.songs.destroy', $song->id) }}"><i
                                                    class="fa-solid fa-trash"></i></a>
                                            {{-- <a class="btn btn-sm btn-primary"
                                                href="{{ route('admin.variants.show', $song->id) }}"><i
                                                    class="fa-solid fa-eye"></i></a> --}}
                                            <a class="btn btn-sm btn-primary"
                                                href="{{ route('admin.songs.variants', $song->id) }}"><i
                                                    class="fa-solid fa-list"></i></a>
                                            {{-- <a class="btn btn-sm btn-primary"
                                                href="{{ route('admin.songs.variants.add', $song->id) }}"><i
                                                    class="fa-solid fa-plus"></i> --}}
                                            </a>
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
