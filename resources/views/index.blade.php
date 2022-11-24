@extends('layouts.app')

@section('content')
    <div class="contenedor">
        <div class="contenedor-tarjetas">

            @foreach ($posts as $post)
                <div class="tarjeta" style="background-image: url('{{ $post->imagesrc }}')">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h5 class="text-shadow text-uppercase">{{ $post->title }}</h5>
                        </div>
                        <div class="tarjeta-footer">
                            <a href="{{ route('show', $post->id) }}" class="btn btn-primary"> Ver</a>

                            @auth
                                @if ($post->liked())
                                    <form action="{{ route('unlike.post', $post->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('like.post', $post->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                            <button class="btn btn-warning">{{ $post->averageRating / 10 }}</button>
                        </div>


                    </div>
                </div>
            @endforeach


        </div>

        <div>
            <div class="contenedor-banner">
                <div class="banner text-white"
                    style="background-image: url('http://papers.co/wallpaper/papers.co-sc38-sub-glow-blur-41-iphone-wallpaper.jpg')">
                    <table>
                        <tr>
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
                    style="background-image: url('http://papers.co/wallpaper/papers.co-sc38-sub-glow-blur-41-iphone-wallpaper.jpg')">
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
                </div>
                

            </div>
        </div>
    </div>
@endsection
