@extends('layouts.app')
@section('meta')
    @if (Request::is('home'))
        <title>Profile {{ Auth::user()->name }}</title>
        <meta title="Profile">
    @endif
@endsection

@section('content')
    @if (Request::routeIs('profile'))
        @include('layouts.user.banner')
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
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
    <script>
        //const baseUrl = document.querySelector('meta[name="base-url"]').content;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const baseUrl = document.querySelector('meta[name="base-url"]').content;
        const apiToken = localStorage.getItem('api_token');

        const uploadAvatarForm = document.querySelector('#upload-avatar-form');
        const submitAvatarFormBtn = document.querySelector('#submit-avatar-form-btn');

        const uploadBannerForm = document.querySelector('#upload-banner-form');
        const submitBannerFormBtn = document.querySelector('#submit-banner-form-btn');

        const scoreFormatForm = document.querySelector('#score-format-form');
        const submitScoreFormatFormBtn = document.querySelector('#score-format-form-btn');

        const bannerDiv = document.querySelector('#banner-image');
        const avatarImg = document.querySelector('#avatar-image');


        uploadAvatarForm.addEventListener('submit', function(event) {
            event.preventDefault();
            uploadAvatar();
        });

        uploadBannerForm.addEventListener('submit', function(event) {
            event.preventDefault();
            uploadBanner();
        });

        scoreFormatForm.addEventListener('submit', function(event) {
            event.preventDefault();
            setScoreFormat();
        });

        async function uploadAvatar() {
            let form = document.getElementById('upload-avatar-form');
            let formData = new FormData(form);
            submitAvatarFormBtn.disabled = true;
            try {
                const response = await fetch(baseUrl + '/api/users/profile', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Authorization': 'Bearer ' + apiToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                    body: formData
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                console.log(data);
                avatarImg.src = data.avatar_url;
                document.getElementById('profile-file').value = '';

            } catch (error) {
                console.error('Error:', error);
            } finally {
                submitAvatarFormBtn.disabled = false;
            }
        }

        async function uploadBanner() {
            let form = document.getElementById('upload-banner-form');
            let formData = new FormData(form);
            submitBannerFormBtn.disabled = true;
            try {
                const response = await fetch(baseUrl + '/api/users/banner', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Authorization': 'Bearer ' + apiToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                    body: formData
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                console.log(data);
                bannerDiv.style.backgroundImage = "url(" + data.banner_url + ")";
                document.getElementById('banner-file').value = '';

            } catch (error) {
                console.error('Error:', error);
            } finally {
                submitBannerFormBtn.disabled = false;
            }
        }

        async function setScoreFormat() {
            let form = document.getElementById('score-format-form');
            let formData = new FormData(form);
            submitScoreFormatFormBtn.disabled = true;
            try {
                const response = await fetch(baseUrl + '/api/users/rating-system', {
                    method: 'POST',
                    headers: {
                        /* 'X-Requested-With': 'XMLHttpRequest', */
                        /* 'Content-Type': 'application/json', */
                        'X-CSRF-TOKEN': csrfToken,
                        'Authorization': 'Bearer ' + apiToken,
                    },
                    body: formData
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                console.log(data);

            } catch (error) {
                console.error('Error:', error);
            } finally {
                submitScoreFormatFormBtn.disabled = false;
            }
        }
    </script>
@endsection
