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
        {{-- @include('layouts.userBanner') --}}
        <div class="container">
            <h1 class="text-light">User banners is disabled</h1>
        </div>
    @endif
    <div class="container text-light">
        @if (Request::routeIs('themes'))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light">Filter Themes</h2>
            </div>
        @endif
        @if (Request::routeIs('favorites'))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light">My Favorites</h2>
            </div>
        @endif
        @if (Request::routeIs('user.list') && isset($user))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light"><strong>{{$user->name}}</strong> favorites</h2>
            </div>
        @endif
        @if (Request::routeIs('artist.show'))
            <div class="top-header color1  mb-1 mt-1">
                <h2><a class="no-deco text-light"
                        href="{{ route('artist.show', [$artist->id, $artist->name_slug]) }}">{{ $artist->name }}
                        {{ $artist->name_jp != null ? '(' . $artist->name_jp . ')' : '' }}</a></h2>
            </div>
        @endif
        <div class="contenedor-filtro mt-2">
            {{-- SEARCH PANEL --}}
            <aside>
                <section class="searchPanel">
                    @if (Request::routeIs('favorites'))
                        <form id="form-filter" action="{{ route('favorites') }}" method="get">
                    @endif
                    @if (Request::routeIs('themes'))
                        <form id="form-filter" action="{{ route('themes') }}" method="get">
                    @endif
                    @if (Request::routeIs('user.list'))
                        <form id="form-filter" action="{{ route('user.list', $user->id) }}" method="get">
                    @endif
                    @if (Request::routeIs('artist.show'))
                        <form id="form-filter" action="{{ route('artist.show', [$artist->id, $artist->name_slug]) }}"
                            method="get">
                    @endif
                    @if (Request::routeIs('favorites') || Request::routeIs('user.list'))
                        {{-- FILTER BY --}}
                        <section class="searchItem">
                            <div class="w-100 mb-1">
                                <label for="select-filterBy" class="text-light">Filter By</label>
                                <select class="form-select" aria-label="Default select example" id="select-filterBy"
                                    name="filterBy">
                                    <option value="">Select a filter method</option>
                                    @foreach ($filters as $item)
                                        <option value="{{ $item['value'] }}"
                                            {{ $requested->filterBy == $item['value'] ? 'selected' : '' }}>
                                            {{ $item['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </section>
                    @endif
                    {{-- TYPE --}}
                    <section class="searchItem">
                        <div class="w-100 mb-1">
                            <label for="select-type" class="text-light">Select type</label>
                            <select class="form-select" aria-label="Default select example" id="select-type" name="type">
                                <option value="">Select a theme type</option>
                                @foreach ($types as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $requested->type == $item['value'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </section>
                    {{-- TAGS --}}
                    <section class="searchItem">
                        <div class="w-100 mb-1">
                            <label class="text-light" for="select-year">Year:</label>
                            <select class="form-select" aria-label="Default select example" name="year" id="select-year">
                                <option selected value="">Select a year</option>
                                @foreach ($years as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $requested->year == $item['value'] ? 'selected' : '' }}>{{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100 mb-1">
                            <label class="text-light" for="select-season">Season:</label>
                            <select class="form-select" aria-label="Default select example" name="season"
                                id="select-season">
                                <option selected value="">Select a season</option>
                                @foreach ($seasons as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $requested->season == $item['value'] ? 'selected' : '' }}>{{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </section>
                    {{-- SORT --}}
                    <section class="searchItem">
                        <div class="w-100 mb-1">
                            <label for="select-sort" class="text-light">Select order method</label>
                            <select class="form-select" aria-label="Default select example" id="select-sort" name="sort">
                                <option value="">Select a sort method</option>
                                @foreach ($sortMethods as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $requested->sort == $item['value'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </section>
                    {{-- LETTER --}}
                    <section class="searchItem">
                        <div class="w-100 mb-1">
                            <label for="select-char" class="text-light">Select a character</label>
                            <select class="form-select" aria-label="Default select example" id="select-char" name="char">
                                <option value="">Select a character</option>
                                @foreach ($characters as $item)
                                    <option value="{{ $item }}" class="text-uppercase"
                                        {{ $requested->char == $item ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </section>
                    {{-- <div class="d-flex mt-1">
                        <button class="btn btn-primary w-100" type="submit">Do it</button>
                    </div> --}}
                    </form>
                </section>
            </aside>
            {{-- POSTS --}}
            <section class="text-light">
                <div class="contenedor-tarjetas-filtro" id="data">
                    {{-- @include('public.songs.songs-cards') --}}
                </div>
            </section>
        </div>
    </div>


@endsection
@section('script')
    @if (Request::routeIs('favorites'))
        @if (config('app.env') === 'local')
            @vite(['resources/js/favorites_infinite_scroll.js'])
        @else
            <script src="{{ asset('resources/js/favorites_infinite_scroll.js') }}"></script>
        @endif
    @endif
    @if (Request::routeIs('user.list'))
        @if (config('app.env') === 'local')
            @vite(['resources/js/favorites_infinite_scroll.js'])
        @else
            <script src="{{ asset('resources/js/favorites_infinite_scroll.js') }}"></script>
        @endif
    @endif

    @if (Request::routeIs('themes'))
        @if (config('app.env') === 'local')
            @vite(['resources/js/themes_infinite_scroll.js'])
        @else
            <script src="{{ asset('resources/js/themes_infinite_scroll.js') }}"></script>
        @endif
    @endif

    @if (Request::routeIs('artist.show'))
        @if (config('app.env') === 'local')
            @vite(['resources/js/artists_infinite_scroll.js'])
        @else
            <script src="{{ asset('resources/js/artists_infinite_scroll.js') }}"></script>
        @endif
    @endif
@endsection
