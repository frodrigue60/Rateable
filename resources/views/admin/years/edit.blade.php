@extends('layouts.app')

@section('content')
    <div class="container text-light">
        <form action="{{ route('admin.years.update', $year->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="year-name" class="form-label">Year</label>
                <input type="number" step="1" class="form-control" id="year-name" placeholder="Year" min="1945" name="year" value="{{ $year->name }}">
            </div>
            <div>
                <button type="submit" class="btn btn-sm btn-primary">Update</button>
            </div>
        </form>

    </div>
@endsection
