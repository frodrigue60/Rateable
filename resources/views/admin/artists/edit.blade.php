@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <h1 class="text-light">Create artist</h1>

        <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{ route('admin.artist.update',$artist->id) }}"
            enctype="multipart/form-data">
            @method('PUT')
            @csrf
            
            <div class="form-group">
                <label for="exampleInputEmail1" class="text-light">Artist Name</label>
                <input type="text" id="name" name="name" class="form-control" required="" value="{{$artist->name}}">
            </div>
            <br>
            <div class="form-group">
                <label for="exampleInputEmail1" class="text-light">Artist Name (JP)</label>
                <input type="text" id="name_jp" name="name_jp" class="form-control" value="{{$artist->name_jp}}">
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>


@endsection
