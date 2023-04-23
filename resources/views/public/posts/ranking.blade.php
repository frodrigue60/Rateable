@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    @if (Request::routeIs('seasonal.ranking'))
        @isset($currentSeason)
            <title>Ranking Best Openings & Endings {{ $currentSeason->name }}</title>
            <meta title="Ranking Openings & Endings {{ $currentSeason->name }}">
            <meta name="description" content="Ranking Best Openings & Endings {{ $currentSeason->name }}">
            <meta name="keywords"
                content="ranking, top, anime openings {{ $currentSeason->name }}, openings anime {{ $currentSeason->name }}, anime endings {{ $currentSeason->name }}, endings anime {{ $currentSeason->name }}, of {{ $currentSeason->name }}">

            <meta property="og:type" content="website" />
            {{-- <meta property="og:image" content="{{ asset('resources/images/og-image-wide.png') }}">
            <meta property="og:image:secure_url" content="{{ asset('resources/images/og-image-wide.png') }}">
            <meta property="og:image:type" content="image/png">
            <meta property="og:image:width" content="828">
            <meta property="og:image:height" content="450">
            <meta property="og:image:alt" content="Anirank banner" /> --}}
            <meta property="og:url" content="{{ url()->current() }}" />

            <meta name="twitter:card" content="summary" />
            <meta name="twitter:site" content="@frodrigue60" />
            <meta name="twitter:creator" content="@frodrigue60" />
            <meta property="og:title" content="Ranking Openings & Endings {{ $currentSeason->name }}" />
            <meta property="og:description" content="Ranking Best Openings & Endings {{ $currentSeason->name }}" />
        @endisset
    @else
        @if (Request::routeIs('global.ranking'))
            <title>Ranking Openings & Endings of All Time</title>
            <meta title="Ranking Openings & Endings of All Time">
            <meta name="description" content="Ranking Best Openings & Endings of All Time">
            <meta name="keywords"
                content="ranking, top, anime openings, openings anime, anime endings, endings anime, of all time">

            <meta property="og:type" content="website" />
            {{-- <meta property="og:image" content="{{ asset('resources/images/og-image-wide.png') }}">
                <meta property="og:image:secure_url" content="{{ asset('resources/images/og-image-wide.png') }}">
                <meta property="og:image:type" content="image/png">
                <meta property="og:image:width" content="828">
                <meta property="og:image:height" content="450">
                <meta property="og:image:alt" content="Anirank banner" /> --}}
            <meta property="og:url" content="{{ url()->current() }}" />

            <meta name="twitter:card" content="summary" />
            <meta name="twitter:site" content="@frodrigue60" />
            <meta name="twitter:creator" content="@frodrigue60" />
            <meta property="og:title" content="Ranking Openings & Endings of All Time" />
            <meta property="og:description" content="Ranking Best Openings & Endings of All Time" />
        @endif
    @endif
@endsection
@section('content')
    <div class="container">
        @if (Request::routeIs('global.ranking'))
            <h1 class="text-center text-light">Ranking Openings & Endings Of All Time</h1>
        @else
            @if (Request::routeIs('seasonal.ranking') && isset($currentSeason))
                <h1 class="text-center text-light">Ranking Openings & Endings {{ $currentSeason->name }}</h1>
            @endif
        @endif
        @include('layouts.top')
    </div>
@endsection
