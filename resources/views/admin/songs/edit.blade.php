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
                                <select class="chzn-select" name="artist_id" id="ArtistId" style="width:100%;">
                                    <option value="">Select a artist</option>
                                    @foreach ($artists as $artist)
                                        <option {{ $song->artist_id == $artist->id ? 'selected' : '' }}
                                            value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="type" class="form-label">Type:</label>
                                <select class="chzn-select" name="type" id="type" style="width:100%;">
                                    @foreach ($types as $item)
                                        <option {{ $song->type == $item['value'] ? 'selected' : '' }}
                                            value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="seasonsTags">Select season</label>
                                <select class="chzn-select" multiple="true" name="tags[]" id="seasonsTags"
                                    style="width:100%;display:flex;flex-direction:column;">
                                    @foreach ($tags as $tag)
                                        @foreach ($song->tags as $item)
                                            <option {{ $item->name == $tag->name ? 'selected' : '' }}
                                                value="{{ $tag->name }}">
                                                {{ $tag->name }}</option>
                                        @endforeach
                                        <option value="{{ $tag->name }}">
                                            {{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Video File</label>
                            <input class="form-control" type="file" id="formFileBanner" name="video">
                        </div>
                        <br>


                        <label for="ytlink" class="form-label">Youtube Embed</label>
                        <input type="text" class="form-control" placeholder="Youtube Embed" id="ytlink" name="ytlink"
                            value="{{ $song->ytlink }}">
                        <br>
                        <label for="scndlink" class="form-label">Second Embed (optional)</label>
                        <input type="text" class="form-control" placeholder="Second Embed (optional)" id="scndlink"
                            name="scndlink" value="{{ $song->scndlink }}">


                        <br>

                        <button class="btn btn-primary" type="submit">Submit</button>
                </div>
                </form>
            </div>

        </div>

    @section('script')
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
        {{-- <script src="http://code.jquery.com/jquery-1.8.3.js"></script> --}}
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">

        <script type="text/javascript">
            $(function() {
                $(".chzn-select").chosen();
            });
        </script>
    @endsection
</div>
@endsection
