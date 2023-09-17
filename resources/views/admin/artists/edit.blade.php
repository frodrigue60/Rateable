@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    @php
                        if (isset($artist)) {
                            $name = $artist->name.'('.$artist->name_jp.')';
                        }
                    @endphp
                    <h5 class="card-title">Edit artist: {{isset($name) ? $name : ''}}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.artist.update', $artist->id) }}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="mb-3">
                            <label for="nameArtist">Artist Name</label>
                            <input type="text" id="nameArtist" name="name" class="form-control" required=""
                                value="{{ $artist->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="nameArtistJp">Artist Name (JP)</label>
                            <input type="text" id="nameArtistJp" name="name_jp" class="form-control"
                                value="{{ $artist->name_jp }}">
                        </div>
                        
                        <div class="d-flex">
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
