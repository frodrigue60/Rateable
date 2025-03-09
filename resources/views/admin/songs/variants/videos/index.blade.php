@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <div class="text-light">
                        @php
                            if (!isset($songVariant->song->slug)) {
                                $song_type = $songVariant->song->type;
                            } else {
                                $song_type = $songVariant->song->slug;
                            }

                        @endphp
                        <h3>{{ $songVariant->song->post->title }} {{ $song_type }} {{ 'v' . $songVariant->version_number }}</h3>
                    </div>
                    <a class="btn btn-primary btn-sm"
                        href="{{ route('variant.videos.create', [$songVariant->song->id, $songVariant->id]) }}"
                        role="button">ADD VIDEO</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Type</th>
                                <th scope="col">value</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($songVariant->video)
                                @php
                                $video = $songVariant->video;
                                    if ($video->type == 'embed') {
                                        $value = $video->embed_code;
                                    } else {
                                        $value = $video->video_src;
                                    }

                                @endphp
                                <tr>
                                    <td>{{ $video->id }}</td>
                                    <td>{{ $video->type }}</td>
                                    <td>{{ $value }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-success" href="{{ route('admin.videos.edit', $video->id) }}"><i
                                                class="fa-solid fa-pencil"></i></a>
                                        <a class="btn btn-sm btn-danger"
                                            href="{{ route('admin.videos.destroy', $video->id) }}"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endisset


                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                </div>
            </div>
        </div>

    </div>
@endsection
