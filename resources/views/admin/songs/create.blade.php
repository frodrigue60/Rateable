@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.songs.breadcumb')
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    <h5 class="card-title">Add song</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('song.post.store', $post->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="theme_num" class="form-label">OP/ED Number</label>
                            <input type="text" class="form-control" placeholder="Opening Number" id="theme_num"
                                name="theme_num" value="{{ old('theme_num') }}">
                        </div>
                        <div class="mb-3">
                            <label for="songRomaji" class="form-label">Song name (romaji)</label>
                            <input type="text" class="form-control" placeholder="Song Name Romaji" id="songRomaji"
                                name="song_romaji" value="{{ old('song_romaji') }}">
                        </div>
                        <div class="mb-3">
                            <label for="songJp" class="form-label">Song name (JP)</label>
                            <input type="text" class="form-control" placeholder="Song Name JP" id="songJp"
                                name="song_jp" value="{{ old('song_jp') }}">
                        </div>
                        <div class="mb-3">
                            <label for="songEn" class="form-label">Song name (EN)</label>
                            <input type="text" class="form-control" placeholder="Song Name EN" id="songEn"
                                name="song_en" value="{{ old('song_en') }}">
                        </div>

                        <div class="row">
                            <div class="col-md mb-3">
                                <label for="ArtistId" class="form-label">Artist</label>
                                <select class="form-select" name="artist_id" id="ArtistId">
                                    <option value="">Select a artist</option>
                                    @foreach ($artists as $artist)
                                        <option {{ old('artist_id') == $artist->id ? 'selected' : '' }}
                                            value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" name="type" id="type">
                                    @foreach ($types as $item)
                                        <option {{ old('type') == $item['value'] ? 'selected' : '' }}
                                            value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            @php
                                [$name, $year] = explode(' ', $post->tags[0]->name);
                                
                                if (isset($post->tags[0])) {
                                    [$name, $year] = explode(' ', $post->tags[0]->name);
                                } else {
                                    $name = null;
                                    $year = null;
                                }
                            @endphp
                            <div class="col-md mb-3">
                                <label for="select-year">Year</label>
                                <select class="form-select" aria-label="Default select example" name="year"
                                    id="select-year">
                                    <option selected value="">Select a year</option>
                                    @foreach ($years as $item)
                                        <option value="{{ $item['value'] }}"
                                            {{ $item['value'] == $year ? 'selected' : '' }}>{{ $item['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md mb-3">
                                <label for="select-season">Season</label>
                                <select class="form-select" aria-label="Default select example" name="season"
                                    id="select-season">
                                    <option selected value="">Select a season</option>
                                    @foreach ($seasons as $item)
                                        <option value="{{ $item['value'] }}"
                                            {{ $item['value'] == $name ? 'selected' : '' }}>{{ $item['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- @section('script')
    @endsection --}}
    </div>
@endsection
