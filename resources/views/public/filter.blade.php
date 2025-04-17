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
    <div class="container d-flex flex-column text-light">
        @if (Request::routeIs('themes'))
            <div class="top-header color1 py-1">
                <h2 class="text-light m-0">Search Themes</h2>
            </div>
        @endif
        @if (Request::routeIs('favorites'))
            <div class="top-header color1 py-1">
                <h2 class="text-light m-0">My Favorites</h2>
            </div>
        @endif
        @if (Request::routeIs('user.list') && isset($user))
            <div class="top-header color1 py-1">
                <h2 class="text-light m-0"><strong>{{ $user->name }}</strong> favorites</h2>
            </div>
        @endif
        @if (Request::routeIs('artists.show'))
            <div class="top-header color1 py-1">
                <h2 class="text-light m-0">{{ $artist->name }}</h2>
            </div>
        @endif
        {{-- SEARCH PANEL --}}

        <section class="my-2">
            @if (Request::routeIs('themes'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.variants.filter'),
                    'method' => 'GET',
                    'fields' => ['name', 'type', 'year', 'season', 'sort'], 
                ])
            @endif
            @if (Request::routeIs('user.list'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.users.list',$user->id),
                    'method' => 'GET',
                    'fields' => ['name', 'type', 'year', 'season', 'sort', 'user-id'],
                ])
            @endif
            @if (Request::routeIs('artists.show'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.artists.filter', $artist->id),
                    'method' => 'GET',
                    'fields' => ['name', 'type', 'year', 'season', 'sort'], 
                ])
            @endif
            @if (Request::routeIs('animes'))
                @include('components.filter.container', [
                    'apiEndpoint' => route('api.posts.animes'),
                    'method' => 'GET',
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


        </section>

        {{-- POSTS --}}
        <section class="text-light mb-3">
            <div class="contenedor-tarjetas-filtro" id="data">
                {{--  @include('layouts.variant.cards') --}}
            </div>
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
        @if (config('app.env') === 'local')
            @vite(['resources/js/favorites_infinite_scroll.js'])
        @else
            <script src="{{ asset('build/favorites_infinite_scroll.js') }}"></script>
        @endif
    @endif
    @if (Request::routeIs('user.list'))
        @if (config('app.env') === 'local')
            @vite(['resources/js/favorites_infinite_scroll.js'])
        @else
            <script src="{{ asset('build/favorites_infinite_scroll.js') }}"></script>
        @endif
    @endif

    @if (Request::routeIs('themes'))
        @if (config('app.env') === 'local')
            @vite(['resources/js/themes_infinite_scroll.js'])
        @else
            <script src="{{ asset('build/themes_infinite_scroll.js') }}"></script>
        @endif
    @endif

    @if (Request::routeIs('artists.show'))
        @if (config('app.env') === 'local')
            @vite(['resources/js/artists_infinite_scroll.js'])
        @else
            <script src="{{ asset('build/artists_infinite_scroll.js') }}"></script>
        @endif
    @endif

    {{-- ANIMES --}}
    @if (Request::routeIs('animes'))
        {{-- INFINITE SCROLL --}}
        @if (config('app.env') === 'local')
            @vite(['resources/js/animes_infinite_scroll.js'])
        @else
            <script src="{{ asset('build/animes_infinite_scroll.js') }}"></script>
        @endif
    @endif
@endsection
