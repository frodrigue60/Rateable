@extends('layouts.app')

<!doctype html>
<head>
    <title>{{ config('app.name', 'Laravel') }}: Ranking anime openings & endings.</title>
</head>


@section('content')
    @if (session('status'))
        <div class="container">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="contenedor">
        <div class="contenedor-tarjetas">
            @foreach ($posts as $post)
                <div class="tarjeta" style="background-image: url('{{ asset('/storage/thumbnails/' . $post->thumbnail) }}')">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h5 class="text-shadow text-uppercase">{{ $post->title }}</h5>
                        </div>
                        <div class="tarjeta-footer">
                            <a href="{{ route('show', $post->id) }}" class="btn btn-sm btn-primary">Show</a>
                            @auth
                                @if ($post->liked())
                                    <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="fa fa-heart"
                                                aria-hidden="true"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('like.post', $post->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-sm btn-success"><i class="fa fa-heart"
                                                aria-hidden="true"></i></button>
                                    </form>
                                @endif
                            @endauth
                            <button class="btn btn-sm btn-warning">{{ $post->averageRating / 20 }} <i
                                    class="fa fa-star"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div>
            <div class="contenedor-banner">
                <div class="banner text-white" style="background-image: url('{{ asset('banner-background.webp') }}');">
                    <table>
                        <tr>
                            <div class="row">
                                {{-- KOFI WIDGET --}}
                                <script type='text/javascript' src='https://storage.ko-fi.com/cdn/widget/Widget_2.js'></script>
                                <script type='text/javascript'>
                                    kofiwidget2.init('Support Me on Ko-fi', '#FD8798', 'F1F4GMOPH');
                                    kofiwidget2.draw();
                                </script>
                            </div>
                            <th class>
                                <h3>Seasons</h3>
                            </th>
                            <br>
                        </tr>
                        @foreach ($tags as $tag)
                            <tr>
                                <td>
                                    <h5><a href="{{route('fromtag',$tag->slug)}}" class="badge text-bg-dark no-deco">{{ $tag->name }}</a></h5>
                                </td>
                            </tr>
                        @endforeach
                        </tr>

                    </table>
                    <br>
                    <a href="{{ route('tags') }}" class="btn btn-primary">All Seasons</a>
                </div>
                <div class="banner text-white" style="background-image: url('{{ asset('banner-background.webp') }}');">
                    @for ($i = 1; $i < 0; $i++)
                    @endfor
                    <table>
                        <tr>
                            <h3>TOP 10</h3>
                        </tr>
                        <tr>
                        @foreach ($posts->sortByDesc('averageRating')->take(10) as $post)  
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td class="ellipsis"><h5><a href="{{ route('show', $post->id) }}" class="badge text-bg-dark no-deco">{{ $post->title }}</a></h5></td>
                            <td><h5><span class="badge bg-primary">{{ $post->averageRating / 20 }} <i class="fa fa-star"></i></span></h5></td>
                        </tr>
                        @endforeach
                        </tr>
                    </table>
                    <a href="{{ route('ranking') }}" class="btn btn-primary">All Places</a>
                </div>
            </div>
        </div>
    </div>
@endsection
