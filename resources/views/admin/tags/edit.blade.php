@extends ('layouts.app')

@section('title', 'Tags Edit/Update')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Edit Tag
                    </div>
                    <div class="card-body">
                        <form name="update-form" id="update-form" method="POST"
                            action="{{ route('admin.tags.update', $tag->id) }}">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" id="name" name="name" class="form-control" required=""
                                    value="{{ $tag->name }}">
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
