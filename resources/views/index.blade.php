@extends('layouts.app')
@section('meta')
    @if (Request::is('/'))
        <title>Ranking Anime Openings & Endings | {{ env('APP_NAME') }}</title>
        <meta name="title" content="Search, play, and rate anime openings and endings">
        <meta name="description"
            content="The site you were looking for to rate openings and endings of your favorite animes. Discover which are the most popular opening and endings.">
        <meta name="keywords"
            content="top anime openings, top anime endings, ranking openings anime, ranking endings anime, Best Anime Openings Of All Time, openings anime, endings anime">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="robots" content="index, follow, max-image-preview:standard">
        <meta property="og:type" content="website" />
        <meta property="og:image" content="{{ asset('resources/images/og-image-wide.png') }}">
        <meta property="og:image:secure_url" content="{{ asset('resources/images/og-image-wide.png') }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="828">
        <meta property="og:image:height" content="450">
        <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:image:alt" content="Anirank banner" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@frodrigue60" />
        <meta name="twitter:creator" content="@frodrigue60" />
        <meta property="og:title" content="Search, play, and rate anime openings and endings" />
        <meta property="og:description"
            content="The site you were looking for to rate openings and endings of your favorite animes. Discover which are the most popular opening and endings." />
        {{-- <meta property="og:image" content="{{ asset('resources/images/og-twitter-image.png') }}" /> --}}
    @endif
@endsection
@section('content')
    <div class="container">
        <!-- TOP -->
        <div class="d-flex flex-column flex-md-row gap-2 mb-3">
            <section class="col col-md-6">
                <div class="d-flex">
                    <h5 class="section-header  me-auto">Top Openings</h5>
                </div>

                <div class="d-flex flex-column gap-2 w-100">
                    @include('partials.top.cards-v2', ['items' => $openings])
                </div>

            </section>

            <section class="col col-md-6">
                <div class="d-flex">
                    <h5 class="section-header  ms-auto">Top Endings</h5>
                </div>

                <div class="d-flex flex-column gap-2 w-100">
                    @include('partials.top.cards-v2', ['items' => $endings])
                </div>
            </section>
        </div>
        <hr class="">
        <!-- RECENTS ADDED SONGS -->
        <section class="mb-3">
            <section class="">
                <h2 class=" section-header">Recently Added</h2>
                <div class="owl-carousel gap-3">
                    @include('partials.songs.cards-v2', ['songs' => $recently])
                </div>
            </section>
        </section>
        <hr class="">

        <!-- MOST POPULAR SONGS -->
        <section class="mb-3">
            <h2 class=" section-header">Most Popular</h2>
            <div class="owl-carousel gap-3">
                @include('partials.songs.cards-v2', ['songs' => $viewed])
            </div>
        </section>
        <hr class="">
        <!-- MOST VIEWED SONGS -->
        {{-- <secttion class="mb-3">
            <h2 class=" section-header">Most Viewed</h2>
            <div class="owl-carousel gap-3">
                @include('partials.songs.cards-v2', ['songs' => $viewed])
            </div>
        </secttion> --}}

        <!-- MOST VIEWED SONGS -->
        <secttion class="mb-3">
            <h2 class=" section-header">Recents Artists</h2>
            <div class="owl-carousel gap-3">
                @include('partials.artists.cards-v2', ['artists' => $artists])
            </div>
        </secttion>
    </div>


@endsection

{{-- @section('script')

@endsection --}}
