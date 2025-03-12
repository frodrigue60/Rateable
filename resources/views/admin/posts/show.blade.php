@extends('layouts.app')

@section('content')
<div class="container text-light">
    <p class="bold">{{ $post->title }}</p>
    <p>{!! $post->description !!}</p>
</div>
@endsection