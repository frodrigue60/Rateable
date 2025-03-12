@extends('layouts.app')

@section('meta')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/css/multi-select-tag.css">

    <style>
        .mult-select-tag ul li {
            color: black;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    <h5 class="card-title">
                        Create Post
                    </h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="titleAnime" class="form-label">Post Title</label>
                            <input type="text" class="form-control" placeholder="Anime Title" id="titleAnime"
                                name="title" required value="{{ old('title') }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                        @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                            <div class="mb-3">
                                <label for="statusId" class="form-label">Status</label>
                                <select class="form-select" name="postStatus" id="statusId" style="width:100%;">
                                    <option value="">Selecte a post status</option>
                                    @foreach ($postStatus as $item)
                                        <option {{ old('type') == $item['value'] ? 'selected' : '' }}
                                            value="{{ $item['value'] }}">{{ $item['name'] }} </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="thumbnail_src" class="form-label">Image Source Url</label>
                            <input type="text" class="form-control" placeholder="Image link" id="thumbnail_src"
                                name="thumbnail_src" value="{{ old('thumbnail_src') }}">
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Upload Image Thumbnail</label>
                            <input class="form-control" type="file" id="formFile" name="file">
                        </div>
                        <div class="mb-3">
                            <label for="thumbnail_src" class="form-label">Banner Source Url</label>
                            <input type="text" class="form-control" placeholder="Image link" id="banner_src"
                                name="banner_src" value="{{ old('banner_src') }}">
                        </div>
                        <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Banner Thumbnail</label>
                            <input class="form-control" type="file" id="formFileBanner" name="banner">
                        </div>
                        <div class="col-md mb-3">
                            <label for="tags-select">Select season</label>
                            <select class="form-select" name="tags[]" id="tags-select" multiple>
                                <option value="">Select tags</option>
                                @isset($tags)
                                    @php
                                        $plucked = $tags->pluck('name')->toArray();
                                    @endphp
                                    @foreach ($tags as $tag)
                                        <option {{ in_array(old('tags[]'), $plucked) ? 'selected' : '' }} value="{{ $tag->id }}">
                                            {{ $tag->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="d-flex">
                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script>

    <script>
        new MultiSelectTag('tags-select');
    </script>
@endsection
