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
                        <div class="row">
                            <div class="col-md">
                                <label for="titleAnime" class="form-label">Post Title</label>
                                <input type="text" class="form-control" placeholder="Anime Title" id="titleAnime"
                                    name="title" required value="{{ old('title') ? old('title') : $post->title }}">
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="theme_num" class="form-label">OP/ED Number</label>
                                <input type="text" class="form-control" placeholder="Opening Number" id="theme_num"
                                    name="theme_num" value="{{old('theme_num')}}">
                            </div> --}}
                        </div>
                        <br>
                        <div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{ old('description') ? old('description') : $post->description }}</textarea>
                            </div>
                        </div>


                        {{-- <div>
                             <label for="songRomaji" class="form-label">Song name (romaji)</label>
                            <input type="text" class="form-control" placeholder="Song Name Romaji" id="songRomaji"
                                name="song_romaji" value="{{ old('song_romaji') }}">
                            <br>
                            <label for="songJp" class="form-label">Song name (JP)</label>
                            <input type="text" class="form-control" placeholder="Song Name JP" id="songJp"
                                name="song_jp" value="{{ old('song_jp') }}">
                            <br>
                            <label for="songEn" class="form-label">Song name (EN)</label>
                            <input type="text" class="form-control" placeholder="Song Name EN" id="songEn"
                                name="song_en" value="{{ old('song_en') }}"> 
                        </div> --}}

                        {{-- <div class="row">
                             <div class="col-md">
                                <label for="ArtistId" class="form-label">Artist</label>
                                <select class="chzn-select" name="artist_id" id="ArtistId" style="width:100%;">
                                    <option value="">Select a artist</option>
                                    @foreach ($artists as $artist)
                                        <option {{ old('artist_id') == $artist->id ? 'selected' : '' }}
                                            value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="type" class="form-label">Type:</label>
                                <select class="chzn-select" name="type" id="type" style="width:100%;">
                                    @foreach ($types as $item)
                                        <option {{ old('type') == $item['value'] ? 'selected' : '' }}
                                            value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div> --}}
                        <div>
                            @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                <div class="col-md">
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
                        </div>
                        <br>
                        {{-- THUMBNAIL --}}
                        <label for="thumbnail_src" class="form-label">Image Source Url</label>
                        <input type="text" class="form-control" placeholder="Image link" id="thumbnail_src"
                            name="thumbnail_src"
                            value="{{ old('thumbnail_src') ? old('thumbnail_src') : $post->thumbnail_src }}">
                        <br>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Upload Image Thumbnail</label>
                            <input class="form-control" type="file" id="formFile" name="file">
                        </div>

                        {{-- BANNER --}}
                        {{-- BANNER --}}
                        <br>
                        <label for="thumbnail_src" class="form-label">Banner Source Url</label>
                        <input type="text" class="form-control" placeholder="Image link" id="banner_src"
                            name="banner_src" value="{{ old('banner_src') ? old('banner_src') : $post->banner_src }}">
                        <br>
                        <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Banner Thumbnail</label>
                            <input class="form-control" type="file" id="formFileBanner" name="banner">
                        </div>

                        {{-- <label for="ytlink" class="form-label">Youtube Embed</label>
                        <input type="text" class="form-control" placeholder="Youtube Embed" id="ytlink" name="ytlink"
                            value="{{ old('ytlink') }}">
                        <br>
                        <label for="scndlink" class="form-label">Second Embed (optional)</label>
                        <input type="text" class="form-control" placeholder="Second Embed (optional)" id="scndlink"
                            name="scndlink" value="{{ old('scndlink') }}"> --}}

                        <label for="seasonsTags">Select season</label>
                        <select class="chzn-select" multiple="true" name="tags[]" id="seasonsTags"
                            style="width:100%;display:flex;flex-direction:column;">
                            @foreach ($tags as $tag)
                                @foreach ($post->tags as $item)
                                    <option {{ $item->name == $tag->name ? 'selected' : '' }} value="{{ $tag->name }}">
                                        {{ $tag->name }}</option>
                                @endforeach
                               {{--  <option value="{{ $tag->name }}">{{ $tag->name }}</option> --}}
                            @endforeach
                        </select>
                        <br>
                        <br>
                        <button class="btn btn-primary" type="submit">Submit</button>
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
