@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    <h5 class="card-title">Create artist</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.artist.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nameArtist">Artist Name</label>
                            <input type="text" id="nameArtist" name="name" class="form-control" required="true"
                                value="{{ old('name') }}">
                        </div> <div class="mb-3">
                            <label for="nameArtistsJp">Artist Name (JP)</label>
                            <input type="text" id="nameArtistsJp" name="name_jp" class="form-control"
                                value="{{ old('name_jp') }}">
                        </div><div class="d-flex">
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
                {{-- <div class="card-footer">
                </div> --}}
            </div>
        </div>
    </div>
@endsection
