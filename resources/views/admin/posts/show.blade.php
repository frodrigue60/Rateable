@extends('layouts.app')

@section('content')
<div class="container ">
    <p class="bold">{{ $post->title }}</p>
    <p>{!! $post->description !!}</p>
</div>
@endsection
