@extends('layouts.app')
@section('meta')
    @if (Request::routeIs('themes'))
        <title>Search Openings & Endings</title>
        <meta title="Search Openings & Endings">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="description" content="Search Openings & Endings by type, season, order as you want, and filter by letter">
        <meta name="robots" content="index, follow, max-image-preview:standard">
    @endif
    @if (Request::routeIs('user.list'))
        <title>{{ $user->name }} - list</title>
        <meta title="{{ $user->name }} - Themes list">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="description" content="Openings & Endings from {{ $user->name }}">
        <meta name="robots" content="index, follow, max-image-preview:standard">
    @endif
@endsection

@section('content')
    @if (Request::routeIs('user.list') || Request::routeIs('favorites'))
        @include('partials.user.banner')
    @endif
    <div class="container d-flex flex-column ">
        <!--HEADER-->
        <div class="">
            @if (Request::routeIs('animes'))
                <h2>Search Animes</h2>
            @endif
            @if (Request::routeIs('themes'))
                <h2>Search Themes</h2>
            @endif
            @if (Request::routeIs('favorites'))
                <h2>My Favorites</h2>
            @endif
            @if (Request::routeIs('user.list') && isset($user))
                <h2><strong>{{ $user->name }}</strong> favorites</h2>
            @endif
            @if (Request::routeIs('artists.show'))
                <h2>{{ $artist->name }}</h2>
            @endif
            @if (Request::routeIs('artists.index'))
                <h2>Artists</h2>
            @endif
        </div>

        <!--FILTER PANNEL-->
        <section class="mb-3">
            @if (Request::routeIs('themes'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.songs.filter'),
                    'method' => 'GET',
                    'fields' => ['name', 'type', 'year', 'season', 'sort'],
                ])
            @endif
            @if (Request::routeIs('user.list'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.users.list', $user->id),
                    'method' => 'GET',
                    'fields' => ['name', 'type', 'year', 'season', 'sort', 'user-id'],
                ])
            @endif
            @if (Request::routeIs('artists.show'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.artists.songs.filter', $artist->id),
                    'method' => 'GET',
                    'fields' => ['name', 'type', 'year', 'season', 'sort', 'artist-id'],
                ])
            @endif
            @if (Request::routeIs('animes'))
                @include('components.filter.container', [
                    'apiEndpoint' => '',
                    'method' => '',
                    'fields' => ['name', 'year', 'season'],
                ])
            @endif
            @if (Request::routeIs('favorites'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.users.favorites'),
                    'method' => 'post',
                    'fields' => ['name', 'type', 'year', 'season', 'sort'],
                ])
            @endif
            @if (Request::routeIs('artists.index'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.artists.filter'),
                    'method' => 'GET',
                    'fields' => ['name'],
                ])
            @endif

        </section>

        <!--DATA CONTAINER-->
        <section class=" mb-3">
            <div class="results" id="data">
                {{--  @include('layouts.variant.cards') --}}
            </div>
            <!--LOADER-->
            <div class="d-flex m-5 justify-content-center" id="loader">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </section>

    </div>
@endsection

@section('script')
    @if (Request::routeIs('favorites'))
        @vite(['resources/js/filter_favorites.js'])
    @endif
    @if (Request::routeIs('user.list'))
        @vite(['resources/js/filter_favorites.js'])
    @endif

    @if (Request::routeIs('themes'))
        @vite(['resources/js/filter_themes.js'])
    @endif

    @if (Request::routeIs('artists.show'))
        @vite(['resources/js/filter_artist_themes.js'])
    @endif
    @if (Request::routeIs('artists.index'))
        @vite(['resources/js/filter_artists.js'])
    @endif

    {{-- ANIMES --}}
    @if (Request::routeIs('animes'))
        @vite(['resources/js/filter_animes.js'])
    @endif
@endsection
