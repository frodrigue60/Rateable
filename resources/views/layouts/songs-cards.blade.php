@foreach ($songs as $song)
    @php
        $url = route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]);
        $thumb_url = file_exists(asset('/storage/thumbnails/' . $song->post->thumbnail)) ? asset('/storage/thumbnails/' . $song->post->thumbnail) : $song->post->thumbnail_src;
    @endphp
    <article class="tarjeta">
        <div class="textos">
            <div class="tarjeta-header text-light">
                <h3 class="text-shadow text-uppercase post-titles">{{ $song->post->title }}</h3>
            </div>
            <div class="{{ $song->type == 'OP' ? 'tag' : 'tag2' }}">
                <span class="tag-content ">{{ $song->suffix != null ? $song->suffix : $song->type }}</span>
            </div>
            <a class="no-deco" target="blank_" href="{{ $url }}">
                <img class="thumb" loading="lazy" src="{{ $thumb_url }}" alt="{{ $song->post->title }}"
                    title="{{ $song->post->title }}">
            </a>
            <div class="tarjeta-footer text-light">
                <span>{{ $song->likeCount }} <i class="fa fa-heart"></i></span>
                <span>{{ $song->view_count }} <i class="fa fa-eye"></i></span>
                @if (isset($song->rating))
                    <span style="color: rgb(162, 240, 181)">{{ $song->rating != null ? $song->rating : '0' }} <i
                            class="fa fa-star" aria-hidden="true"></i>
                    </span>
                @else
                    <span>{{ $song->score != null ? $song->score : 'n/a' }} <i class="fa fa-star"
                            aria-hidden="true"></i>
                    </span>
                @endif
            </div>
        </div>
    </article>
@endforeach
