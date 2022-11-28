@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="container">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Holy guacamole!</strong> {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        <div class="row justify-content-center text-dark">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><strong>{{ Auth::user()->name }}</strong>'s Dashboard</div>
                    <div class="card-body">
                        You are logged in!
                    </div>
                    <div class="card-body">
                        @if (Auth::user()->image)
                            <img class="image rounded-circle" src="{{ asset('/storage/profile/' . Auth::user()->image) }}"
                                alt="profile_image" style="width: 95px;height: 95px; padding: 5px; margin: 0px; ">
                        @endif
                    </div>

                    <div class="card-body">
                        <div col>
                            <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                                @method('post')
                                @csrf
                                <input type="file" name="image">
                                <input type="submit" value="Upload">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
