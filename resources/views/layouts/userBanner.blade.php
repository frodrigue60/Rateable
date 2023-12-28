@php
    $user_profile_pic = '';
    $user_banner_pic = '';

    if (isset($user->image)) {
        $user_profile_pic = $user->image;
    } else {
        $user_profile_pic = asset('/storage/profile/' . 'default.jpg');
    }
    if (isset($user->banner)) {
        $user_banner_pic = $user->banner;
    } else {
        $user_banner_pic = asset('/storage/profile/' . 'default.jpg');
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
