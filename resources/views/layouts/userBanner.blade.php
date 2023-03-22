<div class="header">
    
    @if (isset($user->banner))
        <div class="banner-user" style="background-image: url({{ asset('/storage/banner/' . $user->banner) }})">
            <div class="data-container">
                <div class="shadow-banner"></div>
                <div class="banner-content container">
                    @if (isset($user->image))
                        <img class="avatar" src="{{ asset('/storage/profile/' . $user->image) }}" alt="">
                    @else
                        <img class="avatar" src="{{ asset('/storage/profile/' . 'default.jpg') }}" alt="">
                    @endif
                    <div class="name-wrapper">
                        <h1 class="name text-light">{{ $user->name }}</h1>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="banner-user" style="background-image: url({{ asset('/storage/banner/' . 'default.jpg') }})">
            <div class="data-container">
                <div class="banner-content container">
                    @if (isset($user->image))
                        <img class="avatar" src="{{ asset('/storage/profile/' . $user->image) }}" alt="">
                    @else
                        <img class="avatar" src="{{ asset('/storage/profile/' . 'default.jpg') }}" alt="">
                    @endif
                    <div class="name-wrapper">
                        <h1 class="name text-light">{{ $user->name }}</h1>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
