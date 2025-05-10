@extends('layouts.app')
@section('meta')
    @if (Request::is('home'))
        <title>Profile {{ Auth::user()->name }}</title>
        <meta title="Profile">
    @endif
@endsection

@section('content')
    @if (Request::routeIs('profile'))
        @include('partials.user.banner')
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="card  ">
                <div class="card-header"><strong>{{ $user->name }}</strong>'s Dashboard</div>
                <div class="card-body">
                    {{-- PROFILE PICTURE --}}
                    <div class="mb-3">
                        <form action="{{ route('upload.profile.pic') }}" method="POST" enctype="multipart/form-data"
                            id="upload-avatar-form">
                            @method('post')
                            @csrf
                            <label for="profile-file" class="form-label">Upload profile pic</label>
                            <div class="input-group">

                                <input type="file" class="form-control" id="profile-file"
                                    aria-describedby="inputGroupFileAddon04" aria-label="Upload" name="image"
                                    accept="image/jpg, image/jpeg, image/png, image/webp" required>
                                <button class="btn btn-primary" type="submit" id="submit-avatar-form-btn">Save</button>
                            </div>
                        </form>
                    </div>
                    {{-- BANNER PICTURE --}}
                    <div class="mb-3">
                        <form action="{{ route('upload.banner.pic') }}" method="POST" enctype="multipart/form-data"
                            id="upload-banner-form">
                            @method('post')
                            @csrf
                            <label for="banner-file" class="form-label">Upload profile pic</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="banner-file"
                                    aria-describedby="inputGroupFileAddon04" aria-label="Upload" name="banner"
                                    accept="image/jpg, image/jpeg, image/png, image/webp" required>
                                <button class="btn btn-primary" type="submit" id="submit-banner-form-btn">Save</button>
                            </div>
                        </form>
                    </div>

                    {{-- SCORE FORMAT --}}
                    <div class="mb-3">
                        <form action="{{ route('change.score.format') }}" method="POST" enctype="multipart/form-data"
                            id="score-format-form">
                            @method('post')
                            @csrf
                            <label for="select-score-format" class="form-label">Change score format</label>
                            <div class="input-group">
                                <select class="form-select" id="select-score-format" aria-label="Change score format" name="score_format" required>
                                    <option value="">Select Scoring System</option>
                                    @foreach ($score_formats as $item)
                                        <option value="{{ $item['value'] }}"
                                            {{ Auth::user()->score_format == $item['value'] ? 'selected' : '' }}>
                                            {{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" type="submit" id="score-format-form-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    @vite(['resources/js/modules/users/upload_avatar.js', 'resources/js/modules/users/upload_banner.js', 'resources/js/modules/users/set_score_format.js'])
@endsection
