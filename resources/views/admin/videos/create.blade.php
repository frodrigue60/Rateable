@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">Create Post</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.videos.store', $song->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Video File</label>
                            <input class="form-control" type="file" id="formFileBanner" name="video">
                        </div>
                        <div>
                            <label for="embed" class="form-label">Embed Code</label>
                            <input type="text" class="form-control" placeholder="Embed Code" id="embed"
                                name="embed" value="{{ old('embed') }}">
                        </div>
                        <br>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    @endsection
