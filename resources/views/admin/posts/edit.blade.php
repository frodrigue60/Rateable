@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">Edit.blade.php</div>

                <div class="card-body">
                    <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                        action="{{ route('admin.post.update', $post->id) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="titleAnime" class="form-label">Title</label>
                                <input type="text" class="form-control" placeholder="Anime Title" id="titleAnime"
                                    name="title" required value="{{ $post->title }}">
                            </div>
                            <div class="col-md-6">
                                <label for="theme_num" class="form-label">Opening Number</label>
                                <input type="text" class="form-control" placeholder="OP/ED Number" id="theme_num"
                                    name="theme_num" value="{{ $post->theme_num }}">
                            </div>
                        </div>
                        <br>

                        <label for="songRomaji" class="form-label">Song name (romaji)</label>
                        @if (isset($post->song->song_romaji))
                            <input type="text" class="form-control" value="{{ $post->song->song_romaji }}"
                                id="songRomaji" name="song_romaji">
                        @else
                            <input type="text" class="form-control" value="" id="songRomaji" name="song_romaji">
                        @endif
                        <br>
                        <label for="songJp" class="form-label">Song name (JP)</label>
                        @if (isset($post->song->song_jp))
                            <input type="text" class="form-control" value="{{ $post->song->song_jp }}" id="songJp"
                                name="song_jp">
                        @else
                            <input type="text" class="form-control" value="" id="songJp" name="song_jp">
                        @endif

                        <br>
                        <label for="songEn" class="form-label">Song name (EN)</label>
                        @if (isset($post->song->song_en))
                            <input type="text" class="form-control" value="{{ $post->song->song_en }}" id="songEn"
                                name="song_en">
                        @else
                            <input type="text" class="form-control" value="" id="songEn" name="song_en">
                        @endif

                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="ArtistId" class="form-label">Artist</label>
                                <select class="chzn-select" name="artist_id" id="ArtistId" style="width:100%;">
                                    <option value="">Selecte an artist</option>
                                    @foreach ($artists as $artist)
                                        <option value="{{ $artist->id }}"
                                            {{ $artist->id == $post->artist_id ? 'selected' : '' }}>
                                            {{ $artist->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="type" class="form-label">Type:</label>
                                <select class="chzn-select" name="type" id="type" style="width:100%;">
                                    <option value="">Selecte a type</option>
                                    @foreach ($types as $item)
                                        <option value="{{ $item['value'] }}"
                                            {{ $post->type == $item['value'] ? 'selected' : '' }}>{{ $item['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="statusId" class="form-label">Status</label>
                                <select class="" name="postStatus" id="statusId">
                                    <option value="">Selecte a post status</option>
                                    @foreach ($postStatus as $item)
                                        <option value="{{ $item['value'] }}">{{ $item['name'] }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <label for="thumbnail_src" class="form-label">Image Source</label>
                        <input type="text" class="form-control" placeholder="Image link" id="thumbnail_src" name="thumbnail_src"
                            value="{{ $post->thumbnail_src }}">
                        <br>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Default file input example</label>
                            <input class="form-control" type="file" id="formFile" name="file">
                        </div>
                        <br>
                        <label for="ytlink" class="form-label">Youtube Embed</label>
                        <input type="text" class="form-control" value="{{ $post->ytlink }}" id="ytlink"
                            name="ytlink">
                        <br>
                        <label for="scndlink" class="form-label">Second Embed (optional)</label>
                        <input type="text" class="form-control" value="{{ $post->scndlink }}"
                            placeholder="Second Embed (optional)" id="scndlink" name="scndlink">
                        <br>
                        <label for="seasonsTags">Select season</label>
                        <select class="form-select chzn-select" multiple name="tags[]" id="seasonsTags"
                            style="width:100%;">
                            @foreach ($post->tags as $tag)
                                @if (isset($tag->name))
                                    <option selected value="{{ $tag->name }}">{{ $tag->name }}</option>
                                @endif
                            @endforeach
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <br>
                        <br>
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <br>
                </div>
            </div>

        </div>
    @section('script')
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">

        <script>
            $(function() {
                $(".chzn-select").chosen();
            });
        </script>
    @endsection
</div>
@endsection
