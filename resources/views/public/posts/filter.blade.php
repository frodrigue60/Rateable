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
                <h2 class=" p-0 m-0">Search Animes</h2>
            </div>
        @endif
        <div class="{{-- contenedor-filtro --}} d-flex flex-column mt-2">
            {{-- SEARCH PANEL --}}
            <section class="mb-3">
                <div class="searchPanel">
                    <form action="{{ route('animes') }}" method="get" id="form-filter">
                        <section class="d-flex gap-3">
                            <div class="">
                                <label for="input-name" class="">Name</label>
                                <input type="text" class="form-control" name="char" id="input-name">
                            </div>
                            <div class="">
                                <label class="" for="select-year">Year</label>
                                <select class="form-select" aria-label="Default select example" name="year_id"
                                    id="select-year">
                                    <option selected value="">Select a year</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->id }}">
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="">
                                <label class="" for="select-season">Season</label>
                                <select class="form-select" aria-label="Default select example" name="season_id"
                                    id="select-season">
                                    <option selected value="">Select a season</option>
                                    @foreach ($seasons as $season)
                                        <option value="{{ $season->id }}">
                                            {{ $season->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </section>

                        {{-- <div class="d-flex mt-1">
                            <button class="btn btn-primary w-100" type="submit">Filter</button>
                        </div> --}}
                    </form>
                </div>
            </section>
            {{-- POSTS --}}
            <section class=" mb-3">
                <div class="contenedor-tarjetas-filtro" id="data">
                    {{-- @include('layouts.post.cards') --}}
                </div>
                <div class="d-flex m-5 justify-content-center" id="loader">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
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
