@extends('layouts.app')

@section('content')
    <div class="container ">
        <form action="{{ route('admin.years.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="year-name" class="form-label">Year</label>
                <input type="number" step="1" class="form-control" id="year-name" placeholder="Year" min="1945"
                    name="year" max="2030" value="{{ old('year') }}">
            </div>
            <div>
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </div>
        </form>

    </div>
@endsection
