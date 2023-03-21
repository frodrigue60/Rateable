<section class="contenedor-main">
    <h2 hidden>ANIME OPENINGS & ENDINGS</h2>
    {{-- RECENTS --}}
    <section class="carouselContainermain">
        <div class="top-header mb-1 mt-1">
            <div>
                <h3 class="text-light mb-0">Recently added</h3>
            </div>
            <div>
                <a href="{{ route('filter', 'sort=recent') }}" class="btn btn-sm color4">All Recently Posts</a>
            </div>
        </div>
        <div class="owl-carousel carousel-recents-main">
            @foreach ($recently as $post)
                <article class="tarjeta">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                        </div>
                        <div class="{{ $post->type === 'OP' ? 'tag' : 'tag2' }}">
                            <span
                                class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
                        </div>
                        <a class="no-deco" href="{{ route('post.show', [$post->id, $post->slug]) }}">
                            <img class="thumb" loading="lazy"
                                src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                alt="{{ $post->title }}" title="{{ $post->title }}">
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
    </section>
    {{-- POPULAR --}}
    <section class="carouselContainermain">
        <div class="top-header mb-1 mt-1">
            <div>
                <h3 class="text-light mb-0">Most popular</h3>
            </div>
            <div>
                <a href="{{ route('filter', 'sort=likeCount') }}" class="btn btn-sm color4">All Most Populars</a>
            </div>
        </div>
        <div class="owl-carousel carousel-recents-main">
            @foreach ($popular as $post)
                <article class="tarjeta">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                        </div>
                        <div class="{{ $post->type === 'OP' ? 'tag' : 'tag2' }}">
                            <span
                                class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
                        </div>
                        <a class="no-deco" href="{{ route('post.show', [$post->id, $post->slug]) }}">
                            <img class="thumb" loading="lazy"
                                src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                alt="{{ $post->title }}" title="{{ $post->title }}">
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
    </section>
    {{-- MOST VIEWED --}}
    <section class="carouselContainermain">
        <div class="top-header mb-1 mt-1">
            <div>
                <h3 class="text-light mb-0">Most viewed</h3>
            </div>
            <div>
                <a href="{{ route('filter', 'sort=viewCount') }}" class="btn btn-sm color4">All Most Viewed</a>
            </div>
        </div>
        <div class="owl-carousel carousel-recents-main">
            @foreach ($viewed as $post)
                <article class="tarjeta">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h6 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h6>
                        </div>
                        <div class="{{ $post->type === 'OP' ? 'tag' : 'tag2' }}">
                            <span
                                class="tag-content ">{{ $post->themeNum >= 1 ? $post->suffix : $post->type }}</span>
                        </div>
                        <a class="no-deco" href="{{ route('post.show', [$post->id, $post->slug]) }}">
                            <img class="thumb" loading="lazy"
                                src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                alt="{{ $post->title }}" title="{{ $post->title }}">
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
    </section>
</section>