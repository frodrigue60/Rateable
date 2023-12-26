<section class="contenedor-main">
    {{-- RECENTS --}}
    <section class="carouselContainermain">
        <div class="top-header">
            <div>
                <h2 class="text-light mb-0">Recently added</h2>
            </div>
            <div>
                <a href="{{ route('themes', 'sort=recent') }}" class="btn btn-sm color4">All Recently Posts</a>
            </div>
        </div>
        <div class="owl-carousel carousel-recents-main">
            @foreach ($recently as $variant)
                {{-- @isset($variant->song->post)
                    @php
                        $song_id = $variant->song->id;
                        $post_slug = $variant->song->post->slug;
                        $suffix = $variant->song->suffix != null ? $variant->song->suffix : $variant->song->type;
                        $version = $variant->version;
                        $showVariantRoute = route('p.song.variant.show', [$song_id, $post_slug, $suffix, $version]);
                        $forward_text = ($variant->song->suffix ? $variant->song->suffix : $variant->song->type) . 'v' . $variant->version;
                    @endphp
                    @php
                        $thumb_url = file_exists(asset('/storage/thumbnails/' . $variant->song->post->thumbnail)) ? asset('/storage/thumbnails/' . $variant->song->post->thumbnail) : $variant->song->post->thumbnail_src;
                    @endphp
                    <article class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header">
                                <h3 class="text-shadow text-uppercase post-titles text-light"><a
                                        href="{{ $showVariantRoute }}"
                                        class="text-light no-deco">{{ $variant->song->post->title }}</a></h3>
                            </div>
                            <div class="{{ $variant->song->type == 'OP' ? 'tag' : 'tag2' }}">
                                <span
                                    class="tag-content ">{{ $variant->song->suffix != null ? $variant->song->suffix : $variant->song->type }}</span>
                            </div>

                            <a class="no-deco" href="{{ $showVariantRoute }}">
                                <img class="thumb" loading="lazy" src="{{ $thumb_url }}"
                                    alt="{{ $variant->song->post->title }}" title="{{ $variant->song->post->title }}">
                            </a>
                        </div>
                    </article>
                @endisset --}}
                @include('layouts.song-variant-card')
            @endforeach
        </div>
    </section>
</section>
