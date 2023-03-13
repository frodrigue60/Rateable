<div class="header">
    <div class="shadow-banner"></div>
    <div class="banner-user"
        style="background-image: url(https://s4.anilist.co/file/anilistcdn/user/banner/b934167-puetoiAEaRX0.jpg)">
        <div class="data-container">
            <div class="banner-content container">
                <img class="avatar" src="{{ asset('/storage/profile/' . $user->image) }}" alt="">
                <div class="name-wrapper">
                    <h1 class="name text-light">{{ $user->name }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>