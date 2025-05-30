@php
    $profileUrl = asset('resources/images/default-avatar.jpg');
    $bannerUrl = asset('resources/images/default-banner.jpg');

    if ($user->image != null && Storage::disk('public')->exists($user->image)) {
        $profileUrl = Storage::url($user->image);
    }

    if ($user->banner != null && Storage::disk('public')->exists($user->banner)) {
        $bannerUrl = Storage::url($user->banner);
    }

@endphp
<div class="header">
    <div class="banner-user" style="background-image: url({{ $bannerUrl }})" id="banner-image">
        <div class="data-container">
            <div class="shadow-banner"></div>
            <div class="banner-content container">
                <img class="avatar" src="{{ $profileUrl }}" alt="" id="avatar-image">
                <div class="name-wrapper">
                    <h1 class="name ">{{ $user->name }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
