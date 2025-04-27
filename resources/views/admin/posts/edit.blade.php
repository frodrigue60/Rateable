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
            <div class="card  ">
                <div class="card-header">Create Post</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.posts.update', $post->id) }}" enctype="multipart/form-data">
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
                        {{--  <div class="col-md mb-3">
                            <label for="tags-select">Select tags</label>
                            <select class="form-select" name="tags[]" id="tags-select" multiple>
                                <option value="">Select tags</option>
                                @isset($tags)
                                    @php
                                        $plucked = $post->tags->pluck('name')->toArray();

                                    @endphp
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->name }}" {{ in_array($tag->name, $plucked) ? 'selected' : '' }}>
                                            {{ $tag->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div> --}}
                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <select class="form-select" name="year" id="year">
                                <option selected>Select one</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}"
                                        {{ old('year', $year->id) == $year->id ? 'selected' : '' }}>{{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="season" class="form-label">Season</label>
                            <select class="form-select" name="season" id="season">
                                <option selected>Select one</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->id }}"
                                        {{ old('season', $season->id) == $season->id ? 'selected' : '' }}>
                                        {{ $season->name }}</option>
                                @endforeach
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
@endsection


@section('script')
    {{--  <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script>

    <script>
        new MultiSelectTag('tags-select');
    </script> --}}
@endsection
