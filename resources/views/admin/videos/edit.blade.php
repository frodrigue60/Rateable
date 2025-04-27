@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card  ">
                <div class="card-header">
                    <h5 class="card-title">Edit Video {{$video->id}}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('admin.videos.update', $video->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Video File</label>
                            <input class="form-control" type="file" id="formFileBanner" name="video">
                        </div>
                        <div class="mb-3">
                            <label for="embed" class="form-label">Embed Code</label>
                            <input type="text" class="form-control" placeholder="Embed Code" id="embed"
                                name="embed" value="{{ old('embed', $video->embed_code ?? '') }}">
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-primary w-100" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
