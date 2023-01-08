@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    Create Season
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.tags.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="seasonName">Season Name</label>
                            <input type="text" id="seasonName" name="name" class="form-control" required="">
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
