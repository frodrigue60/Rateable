@extends('layouts.app')
@section('meta')
    @if (Request::is('home'))
        <title>Profile {{ Auth::user()->name }}</title>
        <meta title="Profile">
    @endif
@endsection

@section('content')
    @if (Request::routeIs('profile'))
        @if (config('app.env') === 'local')
            @include('layouts.userBanner')
        @else
            @if (Request::routeIs('profile'))
                <div class="container">
                    <h1 class="text-light">User banners is disabled</h1>
                </div>
            @endif
        @endif
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="card text-light">
                <div class="card-header"><strong>{{ $user->name }}</strong>'s Dashboard</div>
                <div class="card-body">
                    <div>
                        <label for="profilePic" class="form-label">Upload a profile pic</label>
                        <form action="{{ route('upload.profile.pic') }}" method="POST" enctype="multipart/form-data">
                            @method('post')
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" id="profilePic"
                                    aria-describedby="inputGroupFileAddon04" aria-label="Upload" name="image">
                                <button class="btn btn-primary" type="submit" id="inputGroupFileAddon04">Submit profile
                                    pic</button>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div>
                        <label for="bannerPic" class="form-label">Upload a banner pic</label>
                        <form action="{{ route('upload.banner.pic') }}" method="POST" enctype="multipart/form-data">
                            @method('post')
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" id="bannerPic"
                                    aria-describedby="inputGroupFileAddon04" aria-label="Upload" name="banner">
                                <button class="btn btn-primary" type="submit" id="inputGroupFileAddon04">Submit banner
                                    pic</button>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div>
                        <label for="selectScoreFormat" class="form-label">Change score format</label>
                        <form action="{{ route('change.score.format') }}" method="POST" enctype="multipart/form-data">
                            @method('post')
                            @csrf
                            <div class="input-group">
                                <select name="score_format" class="form-select" id="selectScoreFormat"
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
