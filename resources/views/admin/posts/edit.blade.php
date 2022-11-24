@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit.blade.php</div>

                    <div class="card-body">
                        <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                            action="{{ route('admin.post.update', $post->id) }}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <label for="TitleInput" class="form-label">Title</label>
                            <input type="text" class="form-control" id="TitleInput" placeholder="Nuevo valor"
                                id="title" value="{{ $post->title }}" name="title">
                            <br>
                            <label for="TitleInput" class="form-label">Type</label>
                            <br>
                            <select class="chzn-select" name="type" id="type" style="width:200px;">
                                    @foreach ($types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                            </select>
                            <br>
                            <label for="TitleInput" class="form-label">Image Source</label>
                            <input type="text" class="form-control" placeholder="Image link" id="imagesrc"
                                name="imagesrc" value="{{ $post->imagesrc }}">
                            <br>
                            <label for="TitleInput" class="form-label">Youtube Embed</label>
                            <input type="text" class="form-control" placeholder="Youtube Embed" id="ytlink"
                                name="ytlink" value="{{ $post->ytlink }}">
                            <br>
                            <select class="chzn-select" multiple="true" name="tags[]" id="tags" style="width:50%;">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <br>
                            <br>
                            <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
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
