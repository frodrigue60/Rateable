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
        <div>
            <div class="mb-1 mt-1" style="background-color: #0E3D5F">
                <h2 class="text-light">Top rated</h2>
            </div>

            <div class="contenedor-tarjetas">
                @foreach ($posts->sortByDesc('averageRating')->take(6) as $post)
                    <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}">
                                <div class="tarjeta-footer text-light">
                                    <div>
                                        {{ $post->likeCount }} <i class="fa fa-heart"></i>
                                    </div>
                                    <div>
                                        {{ $post->view_count }} <i class="fa fa-eye"></i>
                                    </div>
                                    <div>
                                        @if (isset($score_format))
                                            @switch($score_format)
                                                @case('POINT_100')
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @case('POINT_10')
                                                    <strong>{{ round($post->averageRating / 10) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @case('POINT_5')
                                                    <strong>{{ round($post->averageRating / 20) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @default
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                            @endswitch
                                        @else
                                            <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                class="fa fa-star"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mb-1 mt-2" style="background-color: #0E3D5F">
                <h2 class="text-light">Top viewed</h2>
            </div>
            <div class="contenedor-tarjetas">
                @foreach ($posts->sortByDesc('view_count')->take(6) as $post)
                    <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}">
                                <div class="tarjeta-footer text-light">
                                    <div>
                                        {{ $post->likeCount }} <i class="fa fa-heart"></i>
                                    </div>
                                    <div>
                                        {{ $post->view_count }} <i class="fa fa-eye"></i>
                                    </div>
                                    <div>
                                        @if (isset($score_format))
                                            @switch($score_format)
                                                @case('POINT_100')
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @case('POINT_10')
                                                    <strong>{{ round($post->averageRating / 10) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @case('POINT_5')
                                                    <strong>{{ round($post->averageRating / 20) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @default
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                            @endswitch
                                        @else
                                            <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                class="fa fa-star"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mb-1 mt-2" style="background-color: #0E3D5F">
                <h2 class="text-light">Most popular</h2>
            </div>
            <div class="contenedor-tarjetas">
                @foreach ($posts->sortByDesc('likeCount')->take(6) as $post)
                    <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}">
                                <div class="tarjeta-footer text-light">
                                    <div>
                                        {{ $post->likeCount }} <i class="fa fa-heart"></i>
                                    </div>
                                    <div>
                                        {{ $post->view_count }} <i class="fa fa-eye"></i>
                                    </div>
                                    <div>
                                        @if (isset($score_format))
                                            @switch($score_format)
                                                @case('POINT_100')
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @case('POINT_10')
                                                    <strong>{{ round($post->averageRating / 10) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @case('POINT_5')
                                                    <strong>{{ round($post->averageRating / 20) }}</strong> <i
                                                        class="fa fa-star"></i>
                                                @break

                                                @default
                                                    <strong>{{ round($post->averageRating) }}</strong>
                                            @endswitch
                                        @else
                                            <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                class="fa fa-star"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <div>
            <div class="contenedor-banner">

                <div class="banner text-white" style="background-image: url('{{ asset('banner-background.webp') }}');">
                    @for ($i = 1; $i < 0; $i++)
                    @endfor
                    <table>
                        <tr>
                            <h3> TOP 10 OP </h3>
                        </tr>
                        <tr>
                            @foreach ($posts->sortByDesc('averageRating')->take(10) as $post)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td class="ellipsis">
                                <h5><a href="{{ route('show', $post->id) }}"
                                        class="badge text-bg-dark no-deco">{{ $post->title }}</a></h5>
                            </td>
                            <td>
                                <h5><span class="badge bg-primary">
                                        @if (isset($score_format))
                                            @switch($score_format)
                                                @case('POINT_100')
                                                    {{ round($post->averageRating) }}
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    {{ round($post->averageRating / 10, 1) }}
                                                @break

                                                @case('POINT_10')
                                                    {{ round($post->averageRating / 10) }}
                                                @break

                                                @case('POINT_5')
                                                    {{ round($post->averageRating / 20) }} <i class="fa fa-star"></i>
                                                @break

                                                @default
                                                    {{ round($post->averageRating) }}
                                            @endswitch
                                        @else
                                            <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                class="fa fa-star"></i>
                                        @endif

                                    </span></h5>
                            </td>
                        </tr>
                        @endforeach
                        </tr>
                    </table>
                </div>
                <div class="banner text-white" style="background-image: url('{{ asset('banner-background.webp') }}');">
                    @for ($i = 1; $i < 0; $i++)
                    @endfor
                    <table>
                        <tr>
                            <h3> TOP 10 ED</h3>
                        </tr>
                        <tr>
                            @foreach ($posts->sortByDesc('averageRating')->take(10) as $post)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td class="ellipsis">
                                <h5><a href="{{ route('show', $post->id) }}"
                                        class="badge text-bg-dark no-deco">{{ $post->title }}</a></h5>
                            </td>
                            <td>
                                <h5><span class="badge bg-primary">
                                        @if (isset($score_format))
                                            @switch($score_format)
                                                @case('POINT_100')
                                                    {{ round($post->averageRating) }}
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    {{ round($post->averageRating / 10, 1) }}
                                                @break

                                                @case('POINT_10')
                                                    {{ round($post->averageRating / 10) }}
                                                @break

                                                @case('POINT_5')
                                                    {{ round($post->averageRating / 20) }} <i class="fa fa-star"></i>
                                                @break

                                                @default
                                                    {{ round($post->averageRating) }}
                                            @endswitch
                                        @else
                                            <strong>{{ round($post->averageRating / 10, 1) }}</strong> <i
                                                class="fa fa-star"></i>
                                        @endif

                                    </span></h5>
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
