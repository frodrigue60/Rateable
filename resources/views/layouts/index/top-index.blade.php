<section class="contenedor-main">
    <h2 hidden>TOP ANIME OPENINGS & ENDINGS OF ALL TIME</h2>
    <div class="container-top">
        <section class="container-items limit-items-index">
            <h3 hidden>TOP ANIME OPENINGS OF ALL TIME</h3>
            <div class="top-header-ranking">
                <div>
                    <span>Global Rank Openings</span>
                </div>
                <div>
                    <a href="{{ route('global.ranking') }}" class="btn btn-sm color4">Global Ranking</a>
                </div>
            </div>
            @php
                $j = 1;
            @endphp
            @foreach ($openings as $song)
                @isset($song->post)
                    <div class="top-item">
                        <div class="item-place">
                            <span>{{ $j++ }}</span>
                        </div>

                        <div class="item-info"
                            @if (isset($song->post->banner)) style="background-image: url({{ asset('/storage/anime_banner/' . $song->post->banner) }})"
                        @else
                            style="background-image: url(https://s4.anilist.co/file/anilistcdn/media/anime/banner/98707-ZcFGfUAS4YwK.jpg);" @endif>
                            <div class="item-info-filter"></div>
                            @isset($song)
                                <div class="item-song-info">
                                    @if (isset($song))
                                        @if (isset($song->song_romaji))
                                            <strong><a
                                                    href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_romaji }}</a
                                                    href=""></strong>
                                        @else
                                            @if (isset($song->song_en))
                                                <strong><a
                                                        href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_en }}</a
                                                        href=""></strong>
                                            @else
                                                @if (isset($song->song_jp))
                                                    <strong><a
                                                            href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_jp }}</a
                                                            href=""></strong>
                                                @endif
                                            @endif
                                        @endif
                                    @else
                                        <strong>N/A</strong>
                                    @endif

                                    @if (isset($song->artist))
                                        <strong><a
                                                href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}">{{ $song->artist->name }}</a
                                                href=""></strong>
                                    @else
                                        <strong>N/A</strong>
                                    @endif
                                </div>
                            @endisset
                            <div class="item-post-info">
                                <span><a
                                        href="{{ route('post.show', [$song->post->id, $song->post->slug]) }}">{{ $song->post->title }}</a>
                                    {{ $song->suffix ? '(' . $song->suffix . ')' : '' }}</span>
                            </div>

                        </div>

                        <div class="item-score">
                            <span>
                                @if (isset($score_format))
                                    @switch($score_format)
                                        @case('POINT_100')
                                            {{ round($song->averageRating) }}
                                        @break

                                        @case('POINT_10_DECIMAL')
                                            {{ round($song->averageRating / 10, 1) }}
                                        @break

                                        @case('POINT_10')
                                            {{ round($song->averageRating / 10) }}
                                        @break

                                        @case('POINT_5')
                                            {{ round($song->averageRating / 20) }}
                                        @break

                                        @default
                                            {{ round($song->averageRating) }}
                                    @endswitch
                                @else
                                    {{ round($song->averageRating / 10, 1) }}
                                @endif
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                @endisset
            @endforeach
        </section>
        <section class="container-items limit-items-index">
            <h3 hidden>TOP ANIME ENDINGS OF ALL TIME</h3>
            <div class="top-header-ranking">
                <div>
                    <span>Global Rank Endings</span>
                </div>
                <div>
                    <a href="{{ route('global.ranking') }}" class="btn btn-sm color4">Global Ranking</a>
                </div>
            </div>
            @php
                $j = 1;
            @endphp
            @foreach ($endings as $song)
                @isset($song->post)
                    <div class="top-item">
                        <div class="item-place">
                            <span>{{ $j++ }}</span>
                        </div>

                        <div class="item-info"
                            @if (isset($song->post->banner)) style="background-image: url({{ asset('/storage/anime_banner/' . $song->post->banner) }})"
                @else
                    style="background-image: url(https://s4.anilist.co/file/anilistcdn/media/anime/banner/98707-ZcFGfUAS4YwK.jpg);" @endif>
                            <div class="item-info-filter"></div>
                            @isset($song)
                                <div class="item-song-info">
                                    @if (isset($song))
                                        @if (isset($song->song_romaji))
                                            <strong><a
                                                    href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_romaji }}</a
                                                    href=""></strong>
                                        @else
                                            @if (isset($song->song_en))
                                                <strong><a
                                                        href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_en }}</a
                                                        href=""></strong>
                                            @else
                                                @if (isset($song->song_jp))
                                                    <strong><a
                                                            href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song->song_jp }}</a
                                                            href=""></strong>
                                                @endif
                                            @endif
                                        @endif
                                    @else
                                        <strong>N/A</strong>
                                    @endif

                                    @if (isset($song->artist))
                                        <strong><a
                                                href="{{ route('artist.show', [$song->artist->id, $song->artist->name_slug]) }}">{{ $song->artist->name }}</a
                                                href=""></strong>
                                    @else
                                        <strong>N/A</strong>
                                    @endif
                                </div>
                            @endisset
                            <div class="item-post-info">
                                <span><a
                                        href="{{ route('post.show', [$song->post->id, $song->post->slug]) }}">{{ $song->post->title }}</a>
                                    {{ $song->suffix ? '(' . $song->suffix . ')' : '' }}</span>
                            </div>

                        </div>

                        <div class="item-score">
                            <span>
                                @if (isset($score_format))
                                    @switch($score_format)
                                        @case('POINT_100')
                                            {{ round($song->averageRating) }}
                                        @break

                                        @case('POINT_10_DECIMAL')
                                            {{ round($song->averageRating / 10, 1) }}
                                        @break

                                        @case('POINT_10')
                                            {{ round($song->averageRating / 10) }}
                                        @break

                                        @case('POINT_5')
                                            {{ round($song->averageRating / 20) }}
                                        @break

                                        @default
                                            {{ round($song->averageRating) }}
                                    @endswitch
                                @else
                                    {{ round($song->averageRating / 10, 1) }}
                                @endif
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                @endisset
            @endforeach
        </section>
    </div>
</section>
