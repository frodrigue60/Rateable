@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">Create Post</div>
                <div class="card-body">
                    <form method="post" action="{{ route('song.post.store',$post->id) }}" enctype="multipart/form-data">
                        @csrf
                        {{-- <input id="post_id" name="post_id" type="hidden" value="{{ $post->id }}"> --}}
                        <div class="row">
                            <div class="col">
                                <label for="theme_num" class="form-label">OP/ED Number</label>
                                <input type="text" class="form-control" placeholder="Opening Number" id="theme_num"
                                    name="theme_num" value="{{ old('theme_num') }}">
                            </div>
                        </div>
                        <br>
                        <div>
                            <label for="songRomaji" class="form-label">Song name (romaji)</label>
                            <input type="text" class="form-control" placeholder="Song Name Romaji" id="songRomaji"
                                name="song_romaji" value="{{ old('song_romaji') }}">
                            <br>
                            <label for="songJp" class="form-label">Song name (JP)</label>
                            <input type="text" class="form-control" placeholder="Song Name JP" id="songJp"
                                name="song_jp" value="{{ old('song_jp') }}">
                            <br>
                            <label for="songEn" class="form-label">Song name (EN)</label>
                            <input type="text" class="form-control" placeholder="Song Name EN" id="songEn"
                                name="song_en" value="{{ old('song_en') }}">
                        </div>

                        <div class="row">
                            <div class="col-md">
                                <label for="ArtistId" class="form-label">Artist</label>
                                <select class="form-select" name="artist_id" id="ArtistId">
                                    <option value="">Select a artist</option>
                                    @foreach ($artists as $artist)
                                        <option {{ old('artist_id') == $artist->id ? 'selected' : '' }}
                                            value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="type" class="form-label">Type:</label>
                                <select class="form-select" name="type" id="type">
                                    @foreach ($types as $item)
                                        <option {{ old('type') == $item['value'] ? 'selected' : '' }}
                                            value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
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
