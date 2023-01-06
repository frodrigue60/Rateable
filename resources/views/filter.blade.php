@if (Request::is('filter'))

    <head>
        <title>Search Openings & Endings</title>
        <meta title="Search Openings & Endings">
    </head>
@endif
@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="top-header" class="color1 mb-1 mt-1">
            <h2 class="text-light mb-0">Filter Posts</h2>
        </div>
        <div class="contenedor-filtro">
            <aside>
                <div id="searchPanel">
                    <form action="{{ route('filter') }}" method="get">
                        <div class="searchItem">
                            <span class="text-light">Select Type</span>
                            <select id="chzn-type" name="type" class="form-select" aria-label="Default select example">
                                <option value="" selected>Select the type</option>
                                <option value="op">Opening</option>
                                <option value="ed">Ending</option>
                            </select>
                        </div>
                        <div class="searchItem">
                            <span class="text-light">Select Season</span>
                            <select id="chzn-tag" name='tag' class="form-select" aria-label="Default select example">
                                <option value="" selected>Select the season</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="searchItem">
                            <span class="text-light">Sort By</span>
                            <select id="chzn-sort" name="sort" class="form-select" aria-label="Default select example">
                                <option value="" selected>Select order method</option>
                                <option value="title">Title</option>
                                <option value="averageRating">Score</option>
                                <option value="view_count">Views</option>
                                <option value="likeCount">Favorites</option>
                            </select>
                        </div>
                        <br>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary w-100" type="submit">Do it</button>
                        </div>
                    </form>

                </div>
            </aside>
            <section>
                <div class="contenedor-tarjetas-filtro">
                    @foreach ($posts as $post)
                        <article class="tarjeta">
                            <div class="textos">
                                <div class="tarjeta-header text-light">
                                    <span class="text-shadow text-uppercase post-titles">{{ $post->title }}</span>
                                </div>
                                @if ($post->type == 'op')
                                    <div class="tag">
                                        <span class="tag-content ">{{ $post->type }}{{ $post->opNum }}</span>
                                    </div>
                                @else
                                    <div class="tag2">
                                        <span class="tag-content ">{{ $post->type }}{{ $post->opNum }}</span>
                                    </div>
                                @endif
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
                        </article>
                    @endforeach
                </div>
                <div style="display: flex;justify-content: center;
                margin-top: 10px;">
                    {{ $posts->links() }}
                </div>
            </section>
        </div>
    </div>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">
    <script type="text/javascript">
        $(document).ready(function() {
            $("#chzn-type").chosen();
            $("#chzn-tag").chosen();
            $("#chzn-sort").chosen();
        });
    </script>
@endsection
