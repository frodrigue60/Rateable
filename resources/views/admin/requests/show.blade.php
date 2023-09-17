@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header text-light">
                    <h5 class="card-title">Request by: {{$userRequest->user->name}}</h5>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body text-light">
                    <p class="card-text">{{$userRequest->content}}</p>
                </div>
                {{-- CARD FOOTER --}}
                {{-- <div class="card-footer">
                    
                </div> --}}
            </div>
        </div>
    </div>
@endsection
