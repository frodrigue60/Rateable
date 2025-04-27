@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card  ">
                <div class="card-header">
                    <h5 class="card-title">{{$song_variant->song->post->title}} {{$song_variant->song->slug}} {{$song_variant->slug}} - Video</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('admin.videos.store')}}" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <input type="hidden" name="song_variant_id" value="{{ $song_variant->id }}">
                        <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Video File</label>
                            <input class="form-control" type="file" id="formFileBanner" name="video" accept="video/mp4,video/webm">
                        </div>
                        <div class="mb-3">
                            <label for="embed" class="form-label">Embed Code</label>
                            <input type="text" class="form-control" placeholder="Embed Code" id="embed"
                                name="embed" value="{{ old('embed') }}">
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
