<article class="tarjeta">
    <div class="textos">
        <div class="tarjeta-header text-light">
            <h3 class="text-shadow text-uppercase post-titles">{{ $song->post->title }}</h3>
        </div>
        <div class="{{ $song->type == 'OP' ? 'tag' : 'tag2' }}">
            <span
                class="tag-content ">{{ $song->suffix != null ? $song->suffix : $song->type }}</span>
        </div>
        <a class="no-deco"
            href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">
            <img class="thumb" loading="lazy"
                src="{{ asset('/storage/thumbnails/' . $song->post->thumbnail) }}"
                alt="{{ $song->post->title }}" title="{{ $song->post->title }}">
        </a>
        <div class="tarjeta-footer text-light">
            <span>{{ $song->likeCount }} <i class="fa fa-heart"></i></span>
            <span>{{ $song->view_count }} <i class="fa fa-eye"></i></span>
            @if (isset($song->rating))
                <span style="color: rgb(162, 240, 181)">
                    @if (isset($score_format))
                        @switch($score_format)
                            @case('POINT_100')
                                {{ round($song->rating) }}
                            @break

                            @case('POINT_10_DECIMAL')
                                {{ round($song->rating / 10, 1) }}
                            @break

                            @case('POINT_10')
                                {{ round($song->rating / 10) }}
                            @break

                            @case('POINT_5')
                                {{ round($song->rating / 20) }}
                            @break

                            @default
                                {{ round($song->rating) }}
                        @endswitch
                    @else
                        {{ round($song->rating / 10, 1) }}
                    @endif
                    <i class="fa fa-star" aria-hidden="true"></i>
                </span>
            @else
                <span>
                    @if (isset($score_format))
                        @switch($score_format)
                            @case('POINT_100')
                                {{ round($song->averageRating) }}
                            @break

                            @case('POINT_10_DECIMAL')
                                {{ round($song->averageRating / 10, 1) }}
                            @break

                            @case('POINT_10')
                                {{ round($song->averageRating / 10) }}
                            @break

                            @case('POINT_5')
                                {{ round($song->averageRating / 20) }}
                            @break

                            @default
                                {{ round($song->averageRating) }}
                        @endswitch
                    @else
                        {{ round($song->averageRating / 10, 1) }}
                    @endif
                    <i class="fa fa-star" aria-hidden="true"></i>
                </span>
            @endif
        </div>
    </div>
</article>