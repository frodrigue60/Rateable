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
    @if (Request::routeIs('user.list') || Request::routeIs('favorites'))
        @include('layouts.userBanner')
    @endif
    <div class="container">

        @if (Request::routeIs('animes'))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light mb-0">Filter Animes</h2>
            </div>
        @endif
        @if (Request::routeIs('favorites'))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light mb-0">My Favorites</h2>
            </div>
        @endif
        <div class="contenedor-filtro">
            {{-- SEARCH PANEL --}}
            <aside>
                <div class="searchPanel">
                    <form action="{{ route('animes') }}" method="get">
                        <section class="searchItem">
                            <div class="mb-3 w-100">
                                <label for="select-season" class="form-label text-light">Select season</label>
                                <select class="form-select" aria-label="Default select example" id="select-season"
                                    name="tag">
                                    <option value="">Select a tag</option>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->name }}"
                                            {{ $requested->tag == $tag->name ? 'selected' : '' }}>
                                            {{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </section>
                        <section class="searchItem">
                            <div class="mb-3 w-100">
                                <label for="select-char" class="form-label text-light">Select character</label>
                                <select class="form-select" aria-label="Default select example" id="select-char"
                                    name="char">
                                    <option value="">Select a character</option>
                                    @foreach ($characters as $item)
                                        <option value="{{ $item }}" class="text-uppercase"
                                            {{ $requested->char == $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </section>
                        <br>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary w-100" type="submit">Do it</button>
                        </div>
                    </form>
                </div>
            </aside>
            {{-- POSTS --}}
            <section class="text-light">
                <div class="contenedor-tarjetas-filtro" id="data">
                    {{-- @include('public.posts.posts-cards') --}}
                </div>
                {{-- <div style="display: flex;justify-content: center;
                margin-top: 10px;">
                    {{ $posts->links() }}
                </div> --}}
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
            <script src="{{ asset('resources/js/animes_infinite_scroll.js') }}"></script>
        @endif
    @endif
@endsection
