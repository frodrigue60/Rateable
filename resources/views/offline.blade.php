@extends('layouts.app')

@section('content')

    <h1 class="text-light text-center">You are currently not connected to any networks.</h1>
    <div class="d-flex justify-content-center">
        <img src="{{asset('resources/images/offline.png')}}" alt="" style="size: 25% 25%;">
    </div>

@endsection