@foreach ($posts as $post)
    @php
        /* $thumb_url = file_exists(asset('/storage/thumbnails/' . $song->post->thumbnail)) ? asset('/storage/thumbnails/' . $song->post->thumbnail) : $song->post->thumbnail_src; */
        $thumbnailUrl = '';

        if (Storage::disk('public')->exists($post->thumbnail)) {
            $thumbnailUrl = Storage::url($post->thumbnail);
        } else {
            $thumbnailUrl = $post->thumbnail_src;
        }
    @endphp
    {{-- <article class="tarjeta">
        <div class="textos">
            <div class="tarjeta-header ">
                <h3 class="text-shadow text-uppercase post-titles">{{ $song->post->title }}</h3>
            </div>
            @if ($song->theme_num > 1)
                <div class="{{ $song->type == 'OP' ? 'tag' : 'tag2' }}">
                    <span class="tag-content ">{{ $song->theme_num > 1 ? $song->slug : $song->type }}</span>
                </div>
            @endif

            <a class="no-deco" href="{{ $song->urlFirstVariant }}" rel="nofollow noopener noreferrer">
                <img class="thumb" loading="lazy" src="{{ $thumbnailUrl }}" alt="{{ $song->post->title }}"
                    title="{{ $song->post->title }}">
            </a>
            <div class="tarjeta-footer ">
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
        <div>
            <span>{{ $song->post->title }}</span>
        </div>
    </article> --}}

    <div class="media-card">
        <div class="position-relative overflow-hidden">
            <a href="{{ $post->url }}" class="cover">
                <img class="image loaded z-0" loading="lazy" src="{{ $thumbnailUrl }}" alt="{{ $post->title }}">
            </a>
        </div>
        <div>
            <a href="{{ $post->url }}" class="title">
                {{ $post->title }}
            </a>
        </div>
    </div>
@endforeach
