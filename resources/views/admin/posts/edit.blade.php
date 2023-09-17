@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">Create Post</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.post.update', $post->id) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="mb-3">
                            <label for="titleAnime" class="form-label">Post Title</label>
                            <input type="text" class="form-control" placeholder="Anime Title" id="titleAnime"
                                name="title" required value="{{ old('title') ? old('title') : $post->title }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{ old('description') ? old('description') : $post->description }}</textarea>
                        </div>
                        @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                            <div class="mb-3">
                                <label for="statusId" class="form-label">Status</label>
                                <select class="form-select" name="postStatus" id="statusId" style="width:100%;">
                                    <option value="">Selecte a post status</option>
                                    @foreach ($postStatus as $item)
                                        <option {{ $post->status == $item['value'] ? 'selected' : '' }}
                                            value="{{ $item['value'] }}">{{ $item['name'] }} </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif


                        {{-- THUMBNAIL --}}
                        <div class="mb-3">
                            <label for="thumbnail_src" class="form-label">Image Source Url</label>
                            <input type="text" class="form-control" placeholder="Image link" id="thumbnail_src"
                                name="thumbnail_src"
                                value="{{ old('thumbnail_src') ? old('thumbnail_src') : $post->thumbnail_src }}">
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Upload Image Thumbnail</label>
                            <input class="form-control" type="file" id="formFile" name="file">
                        </div>
                        {{-- BANNER --}}
                        <div class="mb-3">
                            <label for="thumbnail_src" class="form-label">Banner Source Url</label>
                            <input type="text" class="form-control" placeholder="Image link" id="banner_src"
                                name="banner_src" value="{{ old('banner_src') ? old('banner_src') : $post->banner_src }}">
                        </div>
                        <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Banner Thumbnail</label>
                            <input class="form-control" type="file" id="formFileBanner" name="banner">
                        </div>
                        <div class="row">
                            @php
                                if (isset($post->tags[0])) {
                                    [$name, $year] = explode(' ', $post->tags[0]->name);
                                } else {
                                    $name = null;
                                    $year = null;
                                }
                            @endphp
                            <div class="col-md mb-3">
                                <label for="select-season">Select season</label>
                                <select class="form-select" name="season" id="select-season">
                                    <option value="">Select a season</option>
                                    @isset($seasons)
                                        @foreach ($seasons as $item)
                                            <option value="{{ $item['value'] }}"
                                                {{ $item['value'] == $name ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md mb-3">
                                <label for="select-year">Select year</label>
                                <select class="form-select" name="year" id="select-year">
                                    <option value="">Select a year</option>
                                    @isset($seasons)
                                        @foreach ($years as $item)
                                            <option value="{{ $item['value'] }}"
                                                {{ $item['value'] == $year ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>
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
