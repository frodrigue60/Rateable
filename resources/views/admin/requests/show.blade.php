@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header text-light">
                    <span>Request by {{$userRequest->user->name}}</span>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body text-light">
                    <span>{{$userRequest->content}}</span>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    
                </div>
            </div>
        </div>
    </div>
@endsection
