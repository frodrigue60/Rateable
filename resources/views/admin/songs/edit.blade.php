@extends('layouts.app')

@section('meta')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/css/multi-select-tag.css">

    <style>
        .mult-select-tag ul li {
            color: black;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    <h5 class="cart-title">Edit Song</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('posts.songs.update', $song->id) }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="mb-3">
                            <label for="theme_num" class="form-label">OP/ED Number</label>
                            <input type="number" class="form-control" placeholder="Opening Number" id="theme_num"
                                name="theme_num" value="{{ $song->theme_num }}">
                        </div>
                        <div class="mb-3">
                            <label for="songRomaji" class="form-label">Song name (romaji)</label>
                            <input type="text" class="form-control" placeholder="Song Name Romaji" id="songRomaji"
                                name="song_romaji" value="{{ $song->song_romaji }}">
                        </div>
                        <div class="mb-3">
                            <label for="songJp" class="form-label">Song name (JP)</label>
                            <input type="text" class="form-control" placeholder="Song Name JP" id="songJp"
                                name="song_jp" value="{{ $song->song_jp }}">
                        </div>
                        <div class="mb-3">
                            <label for="songEn" class="form-label">Song name (EN)</label>
                            <input type="text" class="form-control" placeholder="Song Name EN" id="songEn"
                                name="song_en" value="{{ $song->song_en }}">
                        </div>
                        <div class="row">
                            <div class="col-md mb-3">
                                <label for="artists-select" class="form-label">Artist</label>
                                <select class="form-select" multiple name="artists[]" id="artists-select">
                                    <option value="">Select a artist</option>
                                    @foreach ($artists as $artist)
                                        @php
                                            $selectedArtistIds = $song->artists->pluck('id')->toArray();

                                        @endphp
                                        <option
                                            {{ old('artist_id') == $artist->id || in_array($artist->id, $selectedArtistIds) ? 'selected' : '' }}
                                            value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" aria-label="Default select example" name="type"
                                    id="type">
                                    <option selected value="">Select a type</option>
                                    @foreach ($types as $item)
                                        <option {{ $song->type == $item['value'] ? 'selected' : '' }}
                                            value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md mb-3">
                            <label for="tags-select">Year</label>
                            <select class="form-select" aria-label="Default select example" name="tags[]" id="tags-select" multiple>
                                <option selected value="">Select tags</option>
                                @isset($tags)
                                    @php
                                        $plucked = $song->tags->pluck('name')->toArray();
                                    @endphp
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->name }}"
                                            {{ in_array($tag->name, $plucked) ? 'selected' : '' }}>{{ $tag->name }}
                                        </option>
                                    @endforeach
                                @endisset

                            </select>
                        </div>

                        <div class="d-flex">
                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
                {{-- <div class="card-footer">
                </div> --}}
            </div>
        </div>
    @section('script')
        <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script>

        <script>
            new MultiSelectTag('artists-select');
            new MultiSelectTag('tags-select');
        </script>
    @endsection
</div>
@endsection
