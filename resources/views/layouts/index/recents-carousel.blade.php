<section class="contenedor-main">
    {{-- RECENTS --}}
    <section class="carouselContainermain">
        <div class="top-header">
            <div>
                <h2 class="text-light mb-0">Recently added</h2>
            </div>
            <div>
                <a href="{{ route('filter', 'sort=recent') }}" class="btn btn-sm color4">All Recently Posts</a>
            </div>
        </div>
        <div class="owl-carousel carousel-recents-main">
            @foreach ($recently as $song)
                @isset($song->post)
                    <article class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header">
                                <h3 class="text-shadow text-uppercase post-titles text-light"><a href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}" class="text-light no-deco">{{ $song->post->title }}</a></h3>
                            </div>
                            <div class="{{ $song->type == 'OP' ? 'tag' : 'tag2' }}">
                                <span class="tag-content ">{{ $song->suffix != null ? $song->suffix : $song->type }}</span>
                            </div>
                            <a class="no-deco"
                                href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">
                                <img class="thumb" loading="lazy"
                                    src="{{ asset('/storage/thumbnails/' . $song->post->thumbnail) }}"
                                    alt="{{ $song->post->title }}" title="{{ $song->post->title }}">
                            </a>
                        </div>
                    </article>
                @endisset
            @endforeach
        </div>
    </section>
</section>
