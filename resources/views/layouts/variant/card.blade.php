@php
    $version = $variant->version_number;
    $forward_text =
        ($variant->song->slug ? $variant->song->slug : $variant->song->type) . 'v' . $variant->version_number;

    $post = $variant->song->post;
    $title = $post->title;

    if (Storage::disk('public')->exists($post->thumbnail)) {
        $thumbnail_url = Storage::url($post->thumbnail);
    } else {
        $thumbnail_url = $post->thumbnail_src;
    }

    $likeCount = 0;
    if ($variant->likesCount >= 1000000) {
        $likeCount = number_format(intval($variant->likesCount / 1000000), 0) . 'M';
    } elseif ($variant->likesCount >= 1000) {
        $likeCount = number_format(intval($variant->likesCount / 1000), 0) . 'K';
    } else {
        $likeCount = $variant->likesCount;
    }
@endphp

<article class="tarjeta">
    <a class="no-deco" href="{{ $variant->url }}" target="_blank" rel="nofollow noopener noreferrer">
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
                <span>{{ $likeCount }} <i class="fa-solid fa-heart"></i></span>
                <span>{{ $variant->viewsString }} <i class="fa-solid fa-eye"></i></span>
                <span>{{ $variant->score }}
                    @if (isset($variant->userScore))
                        <i style="color: rgb(162, 240, 181);" class="fa-solid fa-star" aria-hidden="true"></i>
                    @else
                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                    @endif
                </span>
            </div>
        </div>
    </a>
</article>
