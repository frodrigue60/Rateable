@extends('layouts.app')
@section('meta')
    @if (Request::is('filter'))
        <title>Search Openings & Endings</title>
        <meta title="Search Openings & Endings">
    @endif
@endsection
@section('content')
    <div class="container">
        <div class="top-header color1 mb-1 mt-1">
            <h2 class="text-light mb-0">Filter Posts</h2>
        </div>
        <div class="contenedor-filtro">
            <aside>
                <div class="searchPanel">
                    <form action="{{ route('filter') }}" method="get">
                        {{-- TYPE --}}
                        <section class="searchItem">
                            <span class="text-light">Select Type</span>
                            <select id="chzn-type" name="type" class="form-select" aria-label="Default select example">
                                <option value="">Select the type</option>
                                {{-- <option value="OP" {{ $requested->type == 'OP' ? 'selected' : '' }}>Opening</option>
                                <option value="ED" {{ $requested->type == 'ED' ? 'selected' : '' }}>Ending</option> --}}
                                @foreach ($types as $item)
                                    <option value="{{$item['value']}}" {{ $requested->type == $item['value'] ? 'selected' : '' }}>{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </section>
                        {{-- TAGS --}}
                        <section class="searchItem">
                            <span class="text-light">Select Season</span>
                            <select id="chzn-tag" name='tag' class="form-select" aria-label="Default select example">
                                <option value="">Select the season</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->name }}" {{ $requested->tag == $tag->name ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </section>
                        {{-- SORT --}}
                        <section class="searchItem">
                            <span class="text-light">Sort By</span>
                            <select id="chzn-sort" name="sort" class="form-select" aria-label="Default select example">
                                <option value="">Select order method</option>
                                @foreach ($sortMethods as $item)
                                    <option value="{{$item['value']}}" {{ $requested->sort == $item['value'] ? 'selected' : '' }}>{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </section>
                        <section class="searchItem">
                            <span class="text-light">Filter By Character</span>
                            <select id="chzn-char" name="char" class="form-select" aria-label="Default select example">
                                <option value="">Select a character</option>
                                @foreach ($characters as $item)
                                    <option value="{{$item}}" class="text-uppercase" {{ $requested->char == $item ? 'selected' : '' }}>{{$item}}</option>
                                @endforeach
                            </select>
                        </section>
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
                                <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                            </div>
                                <div class="{{ $post->type === "op" ? "tag" : "tag2" }}">
                                    <span class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
                                </div>
                            <a class="no-deco" href="{{ route('showbyslug', [$post->id, $post->slug]) }}">
                                <img class="thumb" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}">
                            </a>
                            <div class="tarjeta-footer text-light">
                                    <span>{{ $post->likeCount }} <i class="fa fa-heart"></i></span>
                                    <span>{{ $post->viewCount }} <i class="fa fa-eye"></i></span>
                                <span>
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
                                </span>
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
            $("#chzn-char").chosen();
        });
    </script>
@endsection
