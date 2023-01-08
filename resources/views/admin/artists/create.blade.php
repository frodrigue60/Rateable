@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    Create artist
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.artist.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="nameArtist">Artist Name</label>
                            <input type="text" id="nameArtist" name="name" class="form-control" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="nameArtistsJp">Artist Name (JP)</label>
                            <input type="text" id="nameArtistsJp" name="name_jp" class="form-control">
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
@endsection
