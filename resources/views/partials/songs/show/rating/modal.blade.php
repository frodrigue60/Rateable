<div class="modal fade" tabindex="-1" id="modal-rating">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                @guest
                    <div class="d-flex justify-content-center text-center">
                        <h3>Please <a class="" href="{{ route('login') }}">login</a> or <a class=""
                                href="{{ route('register') }}">register</a> to rate
                        </h3>
                    </div>
                @endguest
                @auth
                    @php
                        $format_rating = 0;

                        if (isset($userRating)) {
                            $format_rating = $userRating->formatRating;
                        }
                    @endphp
                    <div class=" d-flex flex-column align-items-center">
                        @switch(Auth::user()->score_format)
                            @case('POINT_100')
                                @include('partials.songs.show.rating.formats.point_100')
                            @break

                            @case('POINT_10_DECIMAL')
                                @include('partials.songs.show.rating.formats.point_10_decimal')
                            @break

                            @case('POINT_10')
                                @include('partials.songs.show.rating.formats.point_10')
                            @break

                            @case('POINT_5')
                                @include('partials.songs.show.rating.formats.point_5')
                            @break

                            @default
                                @include('partials.songs.show.rating.formats.point_100')
                        @endswitch
                    </div>

                @endauth
            </div>

        </div>
    </div>
</div>
