@foreach ($song_variants as $variant)
    @php
        $song_id = $variant->song->id;
        $post_slug = $variant->song->post->slug;
        $suffix = $variant->song->suffix != null ? $variant->song->suffix : $variant->song->type;
        $version = $variant->version;
        $showVariantRoute = route('p.song.variant.show', [$song_id, $post_slug, $suffix, $version]);
        $forward_text = ($variant->song->suffix ? $variant->song->suffix : $variant->song->type) . 'v' . $variant->version;

        $url = route('song.show', [$variant->song->id, $variant->song->post->slug, $variant->song->suffix != null ? $variant->song->suffix : $variant->song->type]);
        $thumb_url = file_exists(asset('/storage/thumbnails/' . $variant->song->post->thumbnail)) ? asset('/storage/thumbnails/' . $variant->song->post->thumbnail) : $variant->song->post->thumbnail_src;
    @endphp

    <article class="tarjeta">
        <div class="textos">
            <div class="tarjeta-header text-light">
                <h3 class="text-shadow text-uppercase post-titles">{{ $variant->song->post->title }}</h3>
            </div>
            <div class="{{ $variant->song->type == 'OP' ? 'tag' : 'tag2' }}">
                <span class="tag-content ">{{ $forward_text }}</span>
            </div>
            <a class="no-deco" href="{{ $showVariantRoute }}">
                <img class="thumb" loading="lazy" src="{{ $thumb_url }}" alt="{{ $variant->song->post->title }}"
                    title="{{ $variant->song->post->title }}">
            </a>
            <div class="tarjeta-footer text-light">
                <span>{{ $variant->likeCount }} <i class="fa fa-heart"></i></span>
                <span>{{ $variant->views }} <i class="fa fa-eye"></i></span>
                @if (isset($variant->rating))
                    <span style="color: rgb(162, 240, 181)">{{ $variant->rating != null ? $variant->rating : '0' }}
                        <i class="fa fa-star" aria-hidden="true"></i>
                    </span>
                @else
                    <span>{{ $variant->score != null ? $variant->score : 'n/a' }} <i class="fa fa-star"
                            aria-hidden="true"></i>
                    </span>
                @endif
            </div>
        </div>
    </article>
@endforeach
