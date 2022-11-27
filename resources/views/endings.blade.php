@extends('layouts.app')

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
                <div class="tarjeta" style="background-image: url('{{ $post->imagesrc }}')">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h5 class="text-shadow text-uppercase">{{ $post->title }}</h5>
                        </div>
                        <div class="tarjeta-footer">
                            <a href="{{ route('show', $post->id) }}" class="btn btn-primary">Show</a>
                            @auth
                                @if ($post->liked())
                                    <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-danger"><i class="fa fa-heart" aria-hidden="true"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('like.post', $post->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-success"><i class="fa fa-heart" aria-hidden="true"></i></button>
                                    </form>
                                @endif
                            @endauth
                            <button class="btn btn-warning">{{ $post->averageRating / 10 }} <span
                                    class="fa fa-star"></span></button>
                        </div>


                    </div>
                </div>
            @endforeach


        </div>

        <div>
            <div class="contenedor-banner">
                <div class="banner text-white"
                    style="background-image: url('{{ asset('banner-background.webp') }}');">
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
                                    <h5><a href="/tag/{{ $tag->name }}"
                                            class="badge text-bg-dark no-deco">{{ $tag->name }}</a></h5>
                                </td>
                            </tr>
                        @endforeach
                        </tr>

                    </table>
                    <br>
                    <a href="{{ route('tags') }}" class="btn btn-primary">All Tags</a>
                </div>
                <div class="banner text-white"
                    style="background-image: url('{{ asset('banner-background.webp') }}');">
                    <table>
                        <tr>
                            <h3>TOP 10</h3>
                        </tr>
                        <tr>
                            @for ($i = 1; $i < 0; $i++)
                            @endfor

                            @foreach ($posts->sortByDesc('averageRating')->take(10) as $post)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td class="ellipsis">
                                <h5><a href="{{ route('show', $post->id) }}"
                                        class="badge text-bg-dark no-deco">{{ $post->title }}</a></h5>
                            </td>
                            <td>
                                <h5><span class="badge bg-primary">{{ $post->averageRating / 10 }}</span></h5>
                            </td>
                        </tr>
                        @endforeach
                        </tr>
                        
                    </table>
                    <button class="btn btn-primary">All places</button>
                </div>
            </div>
        </div>
    </div>
@endsection
