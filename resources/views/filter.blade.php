@extends('layouts.app')
@section('meta')
    @if (Request::routeIs('filter'))
        <title>Search Openings & Endings</title>
        <meta title="Search Openings & Endings">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="description" content="Search Openings & Endings by type, season, order as you want, and filter by letter">
        <meta name="robots" content="index, follow, max-snippet:20, max-image-preview:standard">
    @endif
    @if (Request::routeIs('userlist'))
        <title>{{ $user->name }} - list</title>
        <meta title="{{ $user->name }} - Themes list">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="description" content="Openings & Endings from {{ $user->name }}">
        <meta name="robots" content="index, follow, max-image-preview:standard">
    @endif
@endsection

@section('content')
    @if (Request::routeIs('userlist') || Request::routeIs('favorites'))
        @include('layouts.userBanner')
    @endif
    <div class="container">

        @if (Request::routeIs('filter'))
            <div class="top-header color1 mb-1 mt-1">
                <h2 class="text-light mb-0">Filter Posts</h2>
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
                    @if (Request::routeIs('filter'))
                        @include('filter.search')
                    @endif
                    @if (Request::routeIs('userlist'))
                        @include('filter.user')
                    @endif
                    @if (Request::routeIs('favorites'))
                        @include('filter.favoritesForm')
                    @endif
                </div>
            </aside>
            {{-- POSTS --}}
            <section>
                @if (Request::routeIs('filter'))
                    @include('filter.searchTarjetas')
                @endif
                @if (Request::routeIs('userlist'))
                    @include('filter.userTarjetas')
                @endif
                @if (Request::routeIs('favorites'))
                    @include('filter.favoritesTarjetas')
                @endif
                <div style="display: flex;justify-content: center;
                margin-top: 10px;">
                    {{ $posts->links() }}
                </div>
            </section>
        </div>
    </div>
@section('script')
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">
    <script type="text/javascript">
        $(document).ready(function() {
            $("#chzn-filterBy").chosen();
            $("#chzn-type").chosen();
            $("#chzn-tag").chosen();
            $("#chzn-sort").chosen();
            $("#chzn-char").chosen();
        });
    </script>
@endsection
@endsection
