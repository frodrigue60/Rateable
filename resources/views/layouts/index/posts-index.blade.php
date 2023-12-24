<section class="contenedor-main">
    <div class="top-header mb-2 mt-2">
        <div>
            <h2 class="text-light mb-0">Most Pupular</h2>
        </div>
        <div>
            <a href="{{ route('themes', 'sort=likeCount') }}" class="btn btn-sm color4">Most Popular</a>
        </div>
    </div>
    {{-- POPULAR POSTS --}}
    <section class="contenedor-tarjetas-main">
        @foreach ($popular->take(14) as $variant)
            @isset($variant->song->post)
                {{-- <article class="tarjeta">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h3 class="text-shadow text-uppercase post-titles">{{ $song->post->title }}</h3>
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
                        <div class="tarjeta-footer text-light">
                            <span>{{ $song->likeCount }} <i class="fa fa-heart"></i></span>
                            <span>{{ $song->view_count }} <i class="fa fa-eye"></i></span>
                            <span>{{$song->score != null ? $song->score : 'n/a'}} <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </article> --}}
                @include('layouts.song-variant-card')
            @endisset
        @endforeach
    </section>
    <div class="top-header mb-2 mt-2">
        <div>
            <h2 class="text-light mb-0">Most Viewed</h2>
        </div>
        <div>
            <a href="{{ route('themes', 'sort=view_count') }}" class="btn btn-sm color4">Most Viewed</a>
        </div>
    </div>
    {{-- MOST VIEWED --}}
    <section class="contenedor-tarjetas-main">
        @foreach ($viewed->take(14) as $variant)
            @isset($variant->song->post)
                {{-- <article class="tarjeta">
                    <div class="textos">
                        <div class="tarjeta-header text-light">
                            <h3 class="text-shadow text-uppercase post-titles">{{ $song->post->title }}</h3>
                        </div>
                        <div class="{{ $song->type == 'OP' ? 'tag' : 'tag2' }}">
                            <span class="tag-content ">{{ $song->suffix != null ? $song->suffix : $song->type }}</span>
                        </div>
                        <a class="no-deco"
                            href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">
                            <img class="thumb" loading="lazy" src="{{ asset('/storage/thumbnails/' . $song->post->thumbnail) }}"
                                alt="{{ $song->post->title }}" title="{{ $song->post->title }}">
                        </a>
                        <div class="tarjeta-footer text-light">
                            <span>{{ $song->likeCount }} <i class="fa fa-heart"></i></span>
                            <span>{{ $song->view_count }} <i class="fa fa-eye"></i></span>
                            <span>{{$song->score != null ? $song->score : 'n/a'}} <i class="fa fa-star" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </article> --}}
                @include('layouts.song-variant-card')
            @endisset
        @endforeach
    </section>
</section>
