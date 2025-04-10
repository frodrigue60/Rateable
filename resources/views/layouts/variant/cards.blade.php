@foreach ($song_variants as $variant)
    @php
        $version = $variant->version_number;
        $forward_text =
            ($variant->song->slug ? $variant->song->slug : $variant->song->type) . 'v' . $variant->version_number;

        $post = $variant->song->post;
        $title = $post->title;
        $thumbnail_url = '';

        if (Storage::disk('public')->exists($post->thumbnail)) {
            $thumbnail_url = Storage::url($post->thumbnail);
        } else {
            $thumbnail_url = $post->thumbnail_src;
        }

    @endphp

    <article class="tarjeta">
        <a class="no-deco" href="{{ $variant->url }}" target="_blank" rel="noopener noreferrer">
            <div class="textos">
                <div class="tarjeta-header text-light">
                    <h3 class="text-shadow text-uppercase post-titles">{{ $title }}</h3>
                </div>
                <div class="{{ $variant->song->type == '1' ? 'tag' : 'tag2' }}">
                    <span class="tag-content ">{{ $forward_text }}</span>
                </div>
                <img class="thumb" loading="lazy" src="{{ $thumbnail_url }}" alt="{{ $title }}"
                    title="{{ $title }}">
                <div class="tarjeta-footer text-light">
                    <span>{{ $variant->favoritesCount }} <i class="fa-solid fa-heart"></i></span>
                    <span>{{ $variant->viewsString }} <i class="fa-solid fa-eye"></i></span>
                    <span>{{ $variant->score }}
                        @if ($variant->userScore)
                            <i style="color: rgb(162, 240, 181);" class="fa-solid fa-star" aria-hidden="true"></i>
                        @else
                            <i class="fa-solid fa-star" aria-hidden="true"></i>
                        @endif
                    </span>
                </div>
            </div>
        </a>
    </article>
@endforeach
