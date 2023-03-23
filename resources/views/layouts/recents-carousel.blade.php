<section class="contenedor-main">
    {{-- RECENTS --}}
    <section class="carouselContainermain">
        <div class="top-header mb-2 mt-2">
            <div>
                <h2 class="text-light mb-0">Recently added</h2>
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
                        <h3 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h3>
                    </div>
                    <div class="{{ $post->type == 'OP' ? 'tag' : 'tag2' }}">
                        <span class="tag-content ">{{ $post->suffix != null ? $post->suffix : $post->type }}</span>
                    </div>
                    <a class="no-deco" href="{{ route('post.show', [$post->id, $post->slug,$post->suffix != null ? $post->suffix : $post->type]) }}">
                        <img class="thumb" loading="lazy" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                            alt="{{ $post->title }}" title="{{ $post->title }}">
                    </a>
                    {{-- <div class="tarjeta-footer text-light">
                        <span>{{ $post->likeCount }} <i class="fa fa-heart"></i></span>
                        <span>{{ $post->view_count }} <i class="fa fa-eye"></i></span>
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
                    </div> --}}
                </div>
            </article>
            @endforeach
        </div>
    </section>
</section>