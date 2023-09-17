@extends ('layouts.app')

@section('title', 'Tags Edit/Update')

@section('content')

    <div class="container">
        <div class="row justify-content-center">

            <div class="card bg-dark text-light">
                <div class="card-header">
                    <h5 class="card-title">Edit Tag: {{$tag->name}}</h5>
                </div>
                <div class="card-body">
                    <form name="update-form" id="update-form" method="POST"
                        action="{{ route('admin.tags.update', $tag->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            @php
                                if (isset($tag)) {
                                    [$name, $year] = explode(' ', $tag->name);
                                } else {
                                    $name = null;
                                    $year = null;
                                }
                            @endphp
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="select-season" class="form-label">Select a season</label>
                                    <select class="form-select" name="season" id="select-season">
                                        <option selected value="">Select one</option>
                                        @isset($name)
                                            @foreach ($seasons as $item)
                                                <option value="{{ $item['value'] }}"
                                                    {{ $item['value'] == $name ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="select-year" class="form-label">Select a year</label>
                                    <select class="form-select" name="year" id="select-year">
                                        <option selected value="">Select one</option>
                                        @isset($year)
                                            @foreach ($years as $item)
                                                <option value="{{ $item['value'] }}"
                                                    {{ $item['value'] == $year ? 'selected' : '' }}>{{ $item['name'] }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
