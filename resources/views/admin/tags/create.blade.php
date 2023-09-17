@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    <h5 class="card-title">Create Tag</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.tags.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="select-season" class="form-label">Select a season</label>
                                    <select class="form-select" name="season" id="select-season">
                                        <option selected value="">Select season</option>
                                        @foreach ($seasons as $item)
                                            <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="select-year" class="form-label">Select a year</label>
                                    <select class="form-select" name="year" id="select-year">
                                        <option selected value="">Select year</option>
                                        @foreach ($years as $item)
                                            <option value="{{ $item['value'] }}" {{$item['value'] == old('year') ? 'selected' : ''}}>{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
