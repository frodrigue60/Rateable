@extends('layouts.app')
@section('meta')
    @if (Request::routeIs('filter'))
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
    @if (Request::routeIs('animes'))
        <title>Filter Animes</title>
        <meta title="Filter Animes">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="description" content="Filter Animes">
        <meta name="robots" content="index, follow, max-image-preview:standard">
    @endif
@endsection

@section('content')
    <div class="container">
        @if (Request::routeIs('animes'))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light">Filter Animes</h2>
            </div>
        @endif
        <div class="contenedor-filtro mt-2">
            {{-- SEARCH PANEL --}}
            <aside>
                <div class="searchPanel">
                    <form action="{{ route('animes') }}" method="get" id="form-filter">
                        <section class="searchItem">
                            <div class="w-100 mb-1">
                                <label class="text-light" for="select-year">Year:</label>
                                <select class="form-select" aria-label="Default select example" name="year"
                                    id="select-year">
                                    <option selected value="">Select a year</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->id }}" {{ $requested->year == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-100 mb-1">
                                <label class="text-light" for="select-season">Season:</label>
                                <select class="form-select" aria-label="Default select example" name="season"
                                    id="select-season">
                                    <option selected value="">Select a season</option>
                                    @foreach ($seasons as $season)
                                        <option value="{{ $season->id }}" {{ $requested->season == $season->id ? 'selected' : '' }}>{{ $season->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </section>
                        <section class="searchItem">
                            <div class="w-100 mb-1">
                                <label for="select-char" class="text-light">Character:</label>
                                <select class="form-select" aria-label="Default select example" id="select-char"
                                    name="char">
                                    <option value="">Select a character</option>
                                    @foreach ($characters as $item)
                                        <option value="{{ $item }}" class="text-uppercase"
                                            {{ $requested->char === $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </section>
                        {{-- <div class="d-flex mt-1">
                            <button class="btn btn-primary w-100" type="submit">Filter</button>
                        </div> --}}
                    </form>
                </div>
            </aside>
            {{-- POSTS --}}
            <section class="text-light">
                <div class="contenedor-tarjetas-filtro" id="data">
                    {{-- @include('layouts.post.cards') --}}
                </div>
            </section>
        </div>
    </div>
@endsection
@section('script')
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
