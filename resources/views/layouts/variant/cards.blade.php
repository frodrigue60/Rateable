@foreach ($song_variants as $variant)
    @php
        $song_id = $variant->song->id;
        $post_slug = $variant->song->post->slug;
        $suffix = $variant->song->slug != null ? $variant->song->slug : $variant->song->type;
        $version = $variant->version_number;
        $forward_text =
            ($variant->song->slug ? $variant->song->slug : $variant->song->type) . 'v' . $variant->version_number;

        $post = $variant->song->post;
        $thumbnail_url = '';

        if (Storage::disk('public')->exists($post->thumbnail)) {
            $thumbnail_url = Storage::url($post->thumbnail);
        } else {
            $thumbnail_url = $post->thumbnail_src;
        }

        $title = $variant->song->post->title;

        if ($variant->views >= 1000000) {
            $views = number_format(intval($variant->views / 1000000), 0) . 'M';
        } elseif ($variant->views >= 1000) {
            $views = number_format(intval($variant->views / 1000), 0) . 'K';
        } else {
            $views = $variant->views;
        }
    @endphp

    <article class="tarjeta">
        <div class="textos">
            <div class="tarjeta-header text-light">
                <h3 class="text-shadow text-uppercase post-titles">{{ $title }}</h3>
            </div>
            <div class="{{ $variant->song->type == 'OP' ? 'tag' : 'tag2' }}">
                <span class="tag-content ">{{ $forward_text }}</span>
            </div>
            <a class="no-deco" href="{{ $variant->url }}">
                <img class="thumb" loading="lazy" src="{{ $thumbnail_url }}" alt="{{ $title }}"
                    title="{{ $title }}">
            </a>
            <div class="tarjeta-footer text-light">
                <span>{{ $variant->likeCount }} <i class="fa fa-heart"></i></span>
                <span>{{ $views }} <i class="fa fa-eye"></i></span>
                @if (isset($variant->rating))
                    <span style="color: rgb(162, 240, 181)">{{ $variant->rating != null ? $variant->rating : '0' }}
                        <i class="fa fa-star" aria-hidden="true"></i>
                    </span>
                @else
                    <span>{{ $variant->score != null ? $variant->score : 'N/A' }} <i class="fa fa-star"
                            aria-hidden="true"></i>
                    </span>
                @endif
            </div>
        </div>
    </article>
@endforeach
