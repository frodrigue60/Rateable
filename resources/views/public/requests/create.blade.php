@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card ">
                <div class="card-header ">
                    Create request
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('request.store') }}">
                        @csrf
                        <div class="form-floating">
                            <textarea name="content" class="form-control" placeholder="Write us your request" id="floatingTextarea" required></textarea>
                            <label for="floatingTextarea">Write us your request</label>
                          </div>
                        <br>
                        <div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
