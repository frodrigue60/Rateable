@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <h1 class="text-light">Create tag</h1>

        <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{ route('admin.tags.store') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Tag Name</label>
                <input type="text" id="name" name="name" class="form-control" required="">
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>


@endsection
