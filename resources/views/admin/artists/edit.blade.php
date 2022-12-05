@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Edit Artist
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.artist.update', $artist->id) }}"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label for="nameArtist">Artist Name</label>
                                <input type="text" id="nameArtist" name="name" class="form-control" required=""
                                    value="{{ $artist->name }}">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="nameArtistJp">Artist Name (JP)</label>
                                <input type="text" id="nameArtistJp" name="name_jp" class="form-control"
                                    value="{{ $artist->name_jp }}">
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
