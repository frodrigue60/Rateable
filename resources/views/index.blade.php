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
            <div id="carousel1" class="main-carousel">
                @foreach ($posts->sortByDesc('averageRating')->take(10) as $post)
                    <div class="carousel-cell">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                    <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                        alt="{{ $post->title }}">
                                </a>
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
                                                    {{ round($post->averageRating) }}
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    {{ round($post->averageRating / 10, 1) }}
                                                @break

                                                @case('POINT_10')
                                                    {{ round($post->averageRating / 10) }}
                                                @break

                                                @case('POINT_5')
                                                    {{ round($post->averageRating / 20) }}
                                                @break

                                                @default
                                                    {{ round($post->averageRating) }}
                                            @endswitch
                                        @else
                                            {{ round($post->averageRating / 10, 1) }}
                                        @endif
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-1 mt-2" style="background-color: #0E3D5F">
                <h2 class="text-light">Top viewed</h2>
            </div>
            <div id="carousel2" class="main-carousel">
                @foreach ($posts->sortByDesc('view_count')->take(10) as $post)
                    <div class="carousel-cell">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                    <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                        alt="{{ $post->title }}">
                                </a>
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
                                                    {{ round($post->averageRating) }}
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    {{ round($post->averageRating / 10, 1) }}
                                                @break

                                                @case('POINT_10')
                                                    {{ round($post->averageRating / 10) }}
                                                @break

                                                @case('POINT_5')
                                                    {{ round($post->averageRating / 20) }}
                                                @break

                                                @default
                                                    {{ round($post->averageRating) }}
                                            @endswitch
                                        @else
                                            {{ round($post->averageRating / 10, 1) }}
                                        @endif
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mb-1 mt-2" style="background-color: #0E3D5F">
                <h2 class="text-light">Most popular</h2>
            </div>
            <div id="carousel3" class="main-carousel">
                @foreach ($posts->sortByDesc('likeCount')->take(10) as $post)
                    <div class="carousel-cell">
                        <div class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <h6 class="text-shadow text-uppercase">{{ $post->title }}</h6>
                                </div>
                                <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                    <img id="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                        alt="{{ $post->title }}">
                                </a>
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
                                                    {{ round($post->averageRating) }}
                                                @break

                                                @case('POINT_10_DECIMAL')
                                                    {{ round($post->averageRating / 10, 1) }}
                                                @break

                                                @case('POINT_10')
                                                    {{ round($post->averageRating / 10) }}
                                                @break

                                                @case('POINT_5')
                                                    {{ round($post->averageRating / 20) }}
                                                @break

                                                @default
                                                    {{ round($post->averageRating) }}
                                            @endswitch
                                        @else
                                            {{ round($post->averageRating / 10, 1) }}
                                        @endif
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            @foreach ($posts->sortByDesc('averageRating')->take(5) as $post)
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
                                                    {{ round($post->averageRating / 20) }}
                                                @break

                                                @default
                                                    {{ round($post->averageRating) }}
                                            @endswitch
                                        @else
                                            {{ round($post->averageRating / 10, 1) }}
                                        @endif
                                        <i class="fa fa-star" aria-hidden="true"></i>
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
                            @foreach ($posts->sortByDesc('averageRating')->take(5) as $post)
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
                                                    {{ round($post->averageRating / 20) }}
                                                @break

                                                @default
                                                    {{ round($post->averageRating) }}
                                            @endswitch
                                        @else
                                            {{ round($post->averageRating / 10, 1) }}
                                        @endif
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </span></h5>
                            </td>
                        </tr>
                        @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <script>
            var elem = document.querySelector('#carousel1');
            var elem2 = document.querySelector('#carousel2');
            var elem3 = document.querySelector('#carousel3');

            var flkty = new Flickity(elem, {
                // options
                cellAlign: 'left',
                contain: true,
                wrapAround: false,
                prevNextButtons: true,
                pageDots: false,
                initialIndex: 0,
                freeScroll: false,
            });
            var flkty2 = new Flickity(elem2, {
                // options
                cellAlign: 'left',
                contain: true,
                wrapAround: false,
                prevNextButtons: true,
                pageDots: false,
                initialIndex: 0,
                freeScroll: false,
            });
            var flkty3 = new Flickity(elem3, {
                // options
                cellAlign: 'left',
                contain: true,
                wrapAround: false,
                prevNextButtons: true,
                pageDots: false,
                initialIndex: 0,
                freeScroll: false,
            });
        </script>
    </div>
@endsection
