@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container ">
        <form action="{{ route('admin.seasons.store')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="season-name" class="form-label">Season name</label>
                <input
                    type="text"
                    class="form-control"
                    name="season_name"
                    id="season-name"
                    aria-describedby="helpId"
                    placeholder="SPRING, SUMMER, FALL, WINTER"
                    value="{{ old('season_name') }}"
                />
                {{-- <small id="helpId" class="form-text text-muted">Help text</small> --}}
            </div>
            <div>
                <button class="btn btn-sm btn-primary" type="submit">Submit</button>
            </div>

        </form>
    </div>
@endsection
