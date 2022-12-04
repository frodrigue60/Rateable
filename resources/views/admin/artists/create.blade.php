@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create artist
                    </div>
                    <div class="card-body">
                        <form name="add-blog-post-form" id="add-blog-post-form" method="post"
                            action="{{ route('admin.artist.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Artist Name</label>
                                <input type="text" id="name" name="name" class="form-control" required="">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Artist Name (JP)</label>
                                <input type="text" id="name_jp" name="name_jp" class="form-control">
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="card-footer">

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
