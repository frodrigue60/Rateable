@php
    $user_profile_pic = asset('resources/images/default-avatar.jpg');
    $user_banner_pic = asset('resources/images/default-banner.jpg');

    if ($user->image != null && Storage::disk('public')->exists($user->image)) {
        $user_profile_pic = Storage::url($user->image);;
    }

    if ($user->banner != null && Storage::disk('public')->exists($user->banner)) {
        $user_banner_pic = Storage::url($user->banner);;
    }

@endphp
<div class="header">
    <div class="banner-user" style="background-image: url({{ $user_banner_pic }})">
        <div class="data-container">
            <div class="shadow-banner"></div>
            <div class="banner-content container">
                <img class="avatar" src="{{ $user_profile_pic }}" alt="">
                <div class="name-wrapper">
                    <h1 class="name text-light">{{ $user->name }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
