@foreach ($posts as $post)
    @php

        //$thumb_path = public_path('storage/thumbnails/'.$post->thumbnail);

        if (Storage::disk('public')->exists($post->thumbnail)) {
            $thumbnail_url = Storage::url($post->thumbnail);
        } else {
            $thumbnail_url = $post->thumbnail_src;
        }

    @endphp
    <article class="tarjeta">
        <a class="no-deco" href="{{ $post->url }}" target="_blank" rel="noopener noreferrer">
            <div class="textos">
                <div class="tarjeta-header text-light">
                    <h3 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h3>
                </div>
                <img class="thumb" loading="lazy" src="{{ $thumbnail_url }}" alt="{{ $post->title }}"
                    title="{{ $post->title }}">
                <div class="tarjeta-footer justify-content-center">
                    <span class="text-light">
                        {{ $post->songs->count() }} <i class="fa-solid fa-music"></i>
                    </span>
                </div>
            </div>
        </a>
    </article>
@endforeach
