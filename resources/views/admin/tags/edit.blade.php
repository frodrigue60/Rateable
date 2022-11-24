@extends ('layouts.app')

@section('title', 'Tags Edit/Update')

@section('content')

    <div class="container">
        <h1>Edit tag with ID {{ $tag -> id }}</h1>
        
            <form name="update-form" id="update-form" method="POST" action="{{ route('admin.tags.update', $tag->id) }}">
                @method('POST')
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" id="name" name="name" class="form-control" required="" value="{{ $tag -> name }}">
                </div>
                
                <br>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        
    </div>
@endsection
