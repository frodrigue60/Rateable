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
    <div class="container">
        <div class="contenedor">
            {{-- DIV POSTS --}}
            <div>
                <div id="top-header" class="color1">
                    @if (Request::is('openings'))
                    <div>
                        <h2 class="text-light mb-0">Openings @isset($currentSeason)
                            {{$currentSeason->name}}
                        @endisset</h2>
                    </div>
                    @endif
                    @if (Request::is('endings'))
                    <div>
                        <h2 class="text-light mb-0">Endings @isset($currentSeason)
                            {{$currentSeason->name}}
                        @endisset</h2>
                    </div>
                    @endif
                    
                    <div>
                        {{-- <a href="{{route('globalranking')}}" class="btn btn-sm btn-primary">More</a> --}}
                    </div>
                </div>

                <div class="contenedor-tarjetas">
                    @foreach ($posts as $post)
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
                    @endforeach
                </div>
            </div>
            <div>
                {{-- DIV BANNER --}}
                <div class="contenedor-banner">
                    <div class="banner text-white" style="background-image: url('{{ asset('banner-background.webp') }}');">
                        <table>
                            <tr>
                                <th class>
                                    <h3>Seasons</h3>
                                </th>
                            </tr>
                            @foreach ($tags as $tag)
                                <tr>
                                    <td>
                                        <h5><a href="{{ route('fromtag', $tag->slug) }}"
                                                class="badge text-bg-dark no-deco">{{ $tag->name }}</a></h5>
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
                                <h3> TOP 10 </h3>
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
                        <a href="{{ route('seasonalranking') }}" class="btn btn-primary">All Places</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
