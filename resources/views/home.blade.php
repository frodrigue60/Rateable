@extends('layouts.app')
@section('meta')
    @if (Request::is('home'))
            <title>Profile {{ Auth::user()->name }}</title>
            <meta title="Profile">
    @endif
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card text-light">
                <div class="card-header"><strong>{{ Auth::user()->name }}</strong>'s Dashboard</div>
                <div class="card-body">
                    <div>
                        You are logged in!
                    </div>
                    <div class="">
                        @if (Auth::user()->image)
                            <img class="image rounded-circle" src="{{ asset('/storage/profile/' . Auth::user()->image) }}"
                                alt="profile_image" style="width: 95px;height: 95px; padding: 5px; margin: 0px; ">
                        @else
                            <div>
                                <h2>You dont have profile pic</h2>
                            </div>
                        @endif
                    </div>
                    <br>
                    <div class="">
                        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                            @method('post')
                            @csrf

                            <div class="input-group">
                                <input type="file" class="form-control" id="inputGroupFile04"
                                    aria-describedby="inputGroupFileAddon04" aria-label="Upload" name="image">
                                <button class="btn btn-primary" type="submit" id="inputGroupFileAddon04">Submit profile
                                    pic</button>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div>
                        <form action="{{ route('scoreformat') }}" method="POST" enctype="multipart/form-data">
                            @method('post')
                            @csrf
                            <div class="input-group">
                                <select name="score_format" class="form-select" id="inputGroupSelect04"
                                    aria-label="Example select with button addon">
                                    <option value="" selected>Select Scoring System</option>
                                    <option value="POINT_100">100 Point (55/100)</option>
                                    <option value="POINT_10_DECIMAL">10 Point Decimal (5.5/10)</option>
                                    <option value="POINT_10">10 Point (5/10)</option>
                                    <option value="POINT_5">5 Star (3/5)</option>
                                </select>
                                <button type="submit" class="btn btn-primary" type="button">Save
                                    setting</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
