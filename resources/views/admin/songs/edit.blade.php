@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">Create Post</div>
                <div class="card-body">
                    <form method="post" action="{{ route('song.post.update', $song->id) }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $song->post->id }}">
                        <div class="row">
                            <div class="col">
                                <label for="theme_num" class="form-label">OP/ED Number</label>
                                <input type="text" class="form-control" placeholder="Opening Number" id="theme_num"
                                    name="theme_num" value="{{ $song->theme_num }}">
                            </div>
                        </div>
                        <br>
                        <div>
                            <label for="songRomaji" class="form-label">Song name (romaji)</label>
                            <input type="text" class="form-control" placeholder="Song Name Romaji" id="songRomaji"
                                name="song_romaji" value="{{ $song->song_romaji }}">
                            <br>
                            <label for="songJp" class="form-label">Song name (JP)</label>
                            <input type="text" class="form-control" placeholder="Song Name JP" id="songJp"
                                name="song_jp" value="{{ $song->song_jp }}">
                            <br>
                            <label for="songEn" class="form-label">Song name (EN)</label>
                            <input type="text" class="form-control" placeholder="Song Name EN" id="songEn"
                                name="song_en" value="{{ $song->song_en }}">
                        </div>

                        <div class="row">
                            <div class="col-md">
                                <label for="ArtistId" class="form-label">Artist</label>
                                <select class="form-select" aria-label="Default select example" name="artist_id"
                                    id="ArtistId">
                                    <option selected value="">Select an artist</option>
                                    @foreach ($artists as $artist)
                                        <option {{ $song->artist_id == $artist->id ? 'selected' : '' }}
                                            value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="type" class="form-label">Type:</label>
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
                        <div class="row">
                            <div class="col-md">
                                <label for="select-year">Year:</label>
                                <select class="form-select" aria-label="Default select example" name="year"
                                    id="select-year">
                                    <option selected value="">Select a year</option>
                                    @foreach ($years as $item)
                                        <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="select-season">Season:</label>
                                <select class="form-select" aria-label="Default select example" name="season"
                                    id="select-season">
                                    <option selected value="">Select a season</option>
                                    @foreach ($seasons as $item)
                                        <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-primary" type="submit">Submit</button>
                </div>
                </form>
            </div>

        </div>

    @section('script')
    @endsection
</div>
@endsection
