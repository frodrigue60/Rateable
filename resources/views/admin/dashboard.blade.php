@extends('layouts.app')

@section('content')
    <div class="container ">
        <h1>dashboard</h1>

        @if (Auth::check() && Auth::user()->isStaff())
            <div class="row row-gap-4 row-cols-2 row-cols-sm-3 row-cols-lg-4">
                <div class="col">
                    <a class="" href="{{ route('admin.posts.index') }}">Post index</a>
                </div>
                <div class="col">
                    <a class="" href="{{ route('admin.artists.index') }}">Artist index</a>
                </div>
                <div class="col">
                    <a class="" href="{{ route('admin.years.index') }}">Years index</a>
                </div>
                <div class="col">
                    <a class="" href="{{ route('admin.seasons.index') }}">Seasons index</a>
                </div>
                <div class="col">
                    <a class="" href="{{ route('admin.users.index') }}">Users index</a>
                </div>
                <div class="col">
                    <a class="" href="{{ route('admin.reports.index') }}">Reports index</a>
                </div>
                <div class="col">
                    <a class="" href="{{ route('admin.requests.index') }}">Requests index</a>
                </div>
            </div>
        @endif
    </div>
@endsection
