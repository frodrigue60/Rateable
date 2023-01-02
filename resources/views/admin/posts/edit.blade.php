@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Holy guacamole!</strong> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card bg-dark text-light">
                    <div class="card-header">Edit.blade.php</div>

                    <div class="card-body">
                        <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                            action="{{ route('admin.post.update', $post->id) }}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <label for="titleAnime" class="form-label">Title</label>
                            <input type="text" class="form-control" value="{{ $post->title }}" id="titleAnime"
                                name="title" required>
                            <br>

                            <label for="songRomaji" class="form-label">Song name (romaji)</label>
                            <input type="text" class="form-control" value="{{ $post->song->song_romaji }}"
                                id="songRomaji" name="song_romaji">
                            <br>
                            <label for="songJp" class="form-label">Song name (JP)</label>
                            <input type="text" class="form-control" value="{{ $post->song->song_jp }}" id="songJp"
                                name="song_jp">
                            <br>
                            <label for="songEn" class="form-label">Song name (EN)</label>
                            <input type="text" class="form-control" value="{{ $post->song->song_en }}" id="songEn"
                                name="song_en">
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
                                        @foreach ($types as $type)
                                            <option value="{{ $type }}"
                                                {{ $type == $post->type ? 'selected' : '' }}>
                                                {{ $type }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <br>
                            <label for="imageSrc" class="form-label">Image Source</label>
                            <input type="text" class="form-control" placeholder="Image link" id="imageSrc"
                                name="imagesrc">
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
                            <label for="tags">Select season</label>
                            <select class="form-select chzn-select" multiple name="tags[]" id="tags" style="width:100%;">
                                @foreach ($post->tags as $tag)
                                    <option selected value="{{ $tag->name }}">{{ $tag->name }}</option>
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
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
        {{-- <script src="http://code.jquery.com/jquery-1.8.3.js"></script> --}}
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">

        <script>
            $(function() {
                $(".chzn-select").chosen();
            });
        </script>
    </div>
@endsection
