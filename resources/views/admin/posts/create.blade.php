@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Holy guacamole!</strong> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">Create Post</div>

                    <div class="card-body">
                        <form name="add-blog-post-form" id="add-blog-post-form" method="post"
                            action="{{ route('admin.post.store') }}" enctype="multipart/form-data">
                            @csrf
                            <label for="TitleInput" class="form-label">Title</label>
                            <input type="text" class="form-control" placeholder="Title" id="title" name="title"
                                required>
                            <br>

                            <label for="TitleInput" class="form-label">Song name (romaji)</label>
                            <input type="text" class="form-control" placeholder="Title" id="song_romaji"
                                name="song_romaji">
                            <br>
                            <label for="TitleInput" class="form-label">Song name (JP)</label>
                            <input type="text" class="form-control" placeholder="Title" id="song_jp" name="song_jp">
                            <br>
                            <label for="TitleInput" class="form-label">Song name (EN)</label>
                            <input type="text" class="form-control" placeholder="Title" id="song_en" name="song_en">
                            <br>
                            <div class="row">
                                <div class="col">
                                    <label for="TitleInput" class="form-label">Artist</label>
                                    <select class="chzn-select" name="artist_id" id="artist_id" style="width:100%;">
                                        <option value="null">Select a artist</option>
                                        @foreach ($artists as $artist)
                                            <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="TitleInput" class="form-label">Type:</label>
                                    <select class="chzn-select" name="type" id="type" style="width:100%;">
                                        @foreach ($types as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <label for="TitleInput" class="form-label">Image Source</label>
                            <input type="text" class="form-control" placeholder="Image link" id="imagesrc"
                                name="imagesrc">
                            <br>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Default file input example</label>
                                <input class="form-control" type="file" id="formFile" name="file">
                            </div>
                            <br>
                            <label for="TitleInput" class="form-label">Youtube Embed</label>
                            <input type="text" class="form-control" placeholder="Youtube Embed" id="ytlink"
                                name="ytlink">
                            <br>
                            <label for="TitleInput" class="form-label">Second Embed (optional)</label>
                            <input type="text" class="form-control" placeholder="Second Embed (optional)" id="scndlink"
                                name="scndlink">
                            <br>
                            <label for="tags">Select season</label>
                            <select class="chzn-select" multiple="true" name="tags[]" id="tags" style="width:100%;">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <br>
                            <br>
                            <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">

        <script type="text/javascript">
            $(function() {
                $(".chzn-select").chosen();
            });
        </script>
    </div>
@endsection
