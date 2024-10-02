@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        @include('admin.songs.breadcumb')
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" href="{{ route('song.post.create', $post->id) }}" role="button">ADD
                        SONG</a>
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
                                <th scope="col">ID</th>
                                <th scope="col">Song Name</th>
                                <th scope="col">Songs Artist</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Videos</th>
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
                                        <a class="text-light"
                                            href="{{ route('post.show', [$song->post->id, $song->post->slug]) }}">{{ $song_name }}</a>
                                    </td>
                                    <td>
                                        @isset($song->artist)
                                            <a class="text-light"
                                                href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}">{{ $song->artist->name }}</a>
                                        @endisset
                                        @isset($song->artists)
                                            {{ count($song->artists) }}
                                        @endisset
                                    </td>
                                    <td>
                                        @foreach ($song->tags as $tag)
                                            [{{ $tag->name }}]
                                        @endforeach
                                    </td>
                                    <td>
                                        @if (isset($song->videos))
                                            {{ count($song->videos) }}
                                        @else
                                            "N/A"
                                        @endif
                                    </td>
                                    <td>{{ $song->suffix != null ? $song->suffix : $song->type }}</td>
                                    <td>
                                        @if (Auth::user()->isEditor() | Auth::user()->isAdmin())
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('song.post.edit', $song->id) }}"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                            <a class="btn btn-sm btn-danger"
                                                href="{{ route('song.post.destroy', $song->id) }}"><i
                                                    class="fa-solid fa-trash"></i></a>
                                            {{-- <a class="btn btn-sm btn-primary"
                                                href="{{ route('admin.videos.index', $song->id) }}"><i class="fa-solid fa-list"></i></a> --}}
                                            <a class="btn btn-sm btn-primary"
                                                href="{{ route('song.variant.store', $song->id) }}">+</a>
                                        @endif
                                    </td>

                                </tr>
                                @isset($song->songVariants)
                                    @foreach ($song->songVariants as $variant)
                                        <tr>
                                            <td></td>
                                            <td><a
                                                    href="{{ route('p.song.variant.show', [$variant->song->id, $variant->song->post->slug, $variant->song->suffix, $variant->version]) }}">{{ $song_name }}
                                                    {{ 'v' . $variant->version }}</a>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $variant->videos->count() }}</td>
                                            <td>{{ $variant->song->suffix != '' ? $variant->song->suffix : $variant->song->type }}
                                                {{ 'v' . $variant->version }}</td>
                                            <td>
                                                <a class="btn-sm btn btn-success"
                                                    href="{{ route('song.variant.edit', $variant->id) }}"><i
                                                        class="fa-solid fa-pencil"></i></a>
                                                <a class="btn-sm btn btn-danger"
                                                    href="{{ route('song.variant.destroy', $variant->id) }}"><i
                                                        class="fa-solid fa-trash"></i></a>
                                                <a class="btn-sm btn btn-primary"
                                                    href="{{ route('song.variant.index', $variant->id) }}"><i
                                                        class="fa-solid fa-list"></i></a>
                                                <a class="btn-sm btn btn-primary"
                                                    href="{{ route('song.variant.show', $variant->id) }}"><i
                                                        class="fa-solid fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
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
