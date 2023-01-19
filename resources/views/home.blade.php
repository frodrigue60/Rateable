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
                    <div>
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
                    <div>
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
                                    <option value="">Select Scoring System</option>
                                    @foreach ($score_formats as $item)
                                        <option value="{{ $item['value'] }}"
                                            {{ Auth::user()->score_format == $item['value'] ? 'selected' : '' }}>
                                            {{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary" type="button">Save
                                    setting</button>
                            </div>
                        </form>
                    </div>
                    {{-- <br>
                    <div>
                        <div class="input-group">
                            <select name="colorApp" class="form-select" id="colorApp"
                                aria-label="Example select with button addon">
                                <option value="">Select Theme Color</option>
                                <option value="light">Light Mode</option>
                                <option value="dark">Dark Mode</option>
                            </select>
                            <button type="button" class="btn btn-primary" type="button" id="setColor">Set Color</button>
                        </div>
                    </div> --}}
                </div>
            </div>

        </div>
    </div>
@endsection
{{-- @section('script')
    <script>
        const colorSelect = document.getElementById("colorApp");
        const setColorBtn = document.getElementById('setColor');

        window.addEventListener('DOMContentLoaded', (event) => {
            const select = document.getElementById("colorApp");
            const storedValue = localStorage.getItem("appTheme");

            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === storedValue) {
                    select.options[i].selected = true;
                    break;
                }
            }
        });


        setColorBtn.addEventListener("click", function(event) {
            const select = document.getElementById("colorApp");
            const selectedOption = select.options[select.selectedIndex];
            //console.log(selectedOption.value);
            //console.log(selectedOption.text);

            localStorage.setItem("appTheme", selectedOption.value);
            const color = localStorage.getItem("appTheme");
            console.log('localStorage: ' + color);
        }, false);
    </script>
@endsection --}}
