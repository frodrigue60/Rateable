@extends('layouts.app')

@section('content')
    <div class="container ">
        <h1>dashboard</h1>
    </div>
    <div>
        @if (Auth::check() && Auth::user()->isStaff())
            <ul class="">
                <li><a class="" href="{{ route('admin.posts.index') }}">Post index</a></li>
                <li><a class="" href="{{ route('admin.artists.index') }}">Artist index</a>
                </li>
                <li><a class="" href="{{ route('admin.years.index') }}">Years index</a></li>
                <li><a class="" href="{{ route('admin.seasons.index') }}">Seasons index</a>
                </li>
                @if (Auth::User()->isAdmin())
                    <li> <a class="" href="{{ route('admin.users.index') }}">Users index</a>
                    </li>
                @endif
                <li><a class="" href="{{ route('admin.reports.index') }}">Reports index</a>
                </li>
                <li><a class="" href="{{ route('admin.requests.index') }}">Requests index</a>
                </li>
            </ul>
        @endif
    </div>
@endsection
