@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.videos.breadcumb')
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    <h5 class="card-title">Edit video</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.videos.update', [$video->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Video File</label>
                            <input class="form-control" type="file" id="formFileBanner" name="video">
                            @isset($video->video_src)
                               <p class="py-2">{{$video->video_src}}</p> 
                            @endisset
                        </div>
                        <div class="mb-3">
                            <label for="embed" class="form-label">Embed Code</label>
                            <input type="text" class="form-control" placeholder="Embed Code" id="embed"
                                name="embed" value="{{ $video->embed_code }}">
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    @endsection
