<div class="modal fade" tabindex="-1" id="rating-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content  ">
            @if (Auth::check())
                @csrf
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
            @else
                <div class="d-flex justify-content-center comment-form  text-center">
                    <h3>Please <a class="" href="{{ route('login') }}">login</a>
                        <br>
                        or
                        <br>
                        <a class="" href="{{ route('register') }}">register</a> for rate
                    </h3>
                </div>
            @endif
        </div>
    </div>
</div>
