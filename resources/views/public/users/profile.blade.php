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
                            <div class="mb-3">
                                <label for="profile-file" class="form-label">Upload profile pic</label>
                                <input class="form-control" type="file" id="profile-file" name="image"
                                    accept="image/jpg, image/jpeg, image/png, image/webp" required>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-primary w-100" type="submit"
                                    id="submit-avatar-form-btn">Save</button>
                            </div>
                        </form>
                    </div>
                    {{-- BANNER PICTURE --}}
                    <div class="mb-3">
                        <form action="{{ route('upload.banner.pic') }}" method="POST" enctype="multipart/form-data"
                            id="upload-banner-form">
                            @method('post')
                            @csrf
                            <div class="mb-3">
                                <label for="banner-file" class="form-label">Default file input example</label>
                                <input class="form-control" type="file" id="banner-file" name="banner"
                                    accept="image/jpg, image/jpeg, image/png, image/webp" required>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-primary w-100" type="submit"
                                    id="submit-banner-form-btn">Save</button>
                            </div>
                        </form>
                    </div>

                    {{-- SCORE FORMAT --}}
                    <div class="mb-3">
                        <form action="{{ route('change.score.format') }}" method="POST" enctype="multipart/form-data"
                            id="score-format-form">
                            @method('post')
                            @csrf
                            <div class="mb-3">
                                <label for="selectScoreFormat" class="form-label">Change score format</label>
                                <select name="score_format" class="form-select" id="selectScoreFormat"
                                    aria-label="Example select with button addon" required>
                                    <option value="">Select Scoring System</option>
                                    @foreach ($score_formats as $item)
                                        <option value="{{ $item['value'] }}"
                                            {{ Auth::user()->score_format == $item['value'] ? 'selected' : '' }}>
                                            {{ $item['name'] }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary w-100"
                                    id="score-format-form-btn">Save</button>
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
