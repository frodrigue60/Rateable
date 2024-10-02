@foreach ($posts as $post)
    @php
    
        $thumb_path = public_path('storage/thumbnails/'.$post->thumbnail);

        if (file_exists($thumb_path)) {
            $thumb_url =asset('storage/thumbnails/' . $post->thumbnail);
        } else {
            $thumb_url = $post->thumbnail_src;
        }
        

    @endphp
    <article class="tarjeta">
        <div class="textos">
            <div class="tarjeta-header text-light">
                <h3 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h3>
            </div>
            {{-- <div class="{{ $song->type == 'OP' ? 'tag' : 'tag2' }}">
            <span
                class="tag-content ">{{ $song->suffix != null ? $song->suffix : $song->type }}</span>
        </div> --}}
            <a class="no-deco" target="blank_" href="{{ route('post.show', [$post->id, $post->slug]) }}">
                <img class="thumb" loading="lazy" src="{{ $thumb_url }}" alt="{{ $post->title }}"
                    title="{{ $post->title }}">
            </a>
            <div class="tarjeta-footer justify-content-center">
                <a class="no-deco text-light" href="{{ route('post.show', [$post->id, $post->slug]) }}" target="_blank"
                    rel="noopener noreferrer">
                    {{ $post->songs->count() }} <i class="fa fa-music"></i>
                </a>
                {{-- <span>{{ $post->likeCount }} <i class="fa fa-heart"></i></span>
            <span>{{ $post->view_count }} <i class="fa fa-eye"></i></span>
            @if (isset($post->rating))
                <span style="color: rgb(162, 240, 181)">
                    @if (isset($score_format))
                        @switch($score_format)
                            @case('POINT_100')
                                {{ round($post->rating) }}
                            @break

                            @case('POINT_10_DECIMAL')
                                {{ round($post->rating / 10, 1) }}
                            @break

                            @case('POINT_10')
                                {{ round($post->rating / 10) }}
                            @break

                            @case('POINT_5')
                                {{ round($post->rating / 20) }}
                            @break

                            @default
                                {{ round($post->rating) }}
                        @endswitch
                    @else
                        {{ round($post->rating / 10, 1) }}
                    @endif
                    <i class="fa fa-star" aria-hidden="true"></i>
                </span>
            @else
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
            @endif --}}
            </div>
        </div>
    </article>
@endforeach
