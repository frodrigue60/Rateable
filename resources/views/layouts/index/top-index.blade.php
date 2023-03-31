<section class="contenedor-main">
    <h2 hidden>TOP ANIME OPENINGS & ENDINGS OF ALL TIME</h2>
    <div class="container-top">
        <section class="container-items">
            <h3 hidden>TOP ANIME OPENINGS OF ALL TIME</h3>
            <div class="top-header">
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
                    <article class="top-item">
                        <div class="item-place">
                            <span><strong>{{ $j++ }}</strong></span>
                        </div>
                        <div class="item-info">
                            <div class="item-post-info">
                                <span><a href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                        class="text-light no-deco">{{ $song->post->title }}
                                        {{ $song->suffix != null ? $song->suffix : '' }}</a></span>
                            </div>
                            @if (isset($song->song_romaji) || isset($song->song_en) || isset($song->song_jp))
                                <div class="item-song-info">
                                    <span id="song-title"><strong><a
                                                href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                                class="no-deco text-light">
                                                @if (isset($song->song_romaji))
                                                    {{ $song->song_romaji }}
                                                @else
                                                    @if (isset($song->song_en))
                                                        {{ $song->song_en }}
                                                    @else
                                                        @if (isset($song->song_jp))
                                                            {{ $song->song_jp }}
                                                        @endif
                                                    @endif
                                                @endif
                                            </a></strong></span>
                                    @if (isset($song->artist->name))
                                        <span style="margin-left: 4px;margin-right:4px;">By</span>
                                        <span id="song-artist"><strong><a
                                                    href="{{ route('artist.show', [$song->id,$song->artist->name_slug]) }}"
                                                    class="no-deco text-light">
                                                    {{ $song->artist->name }}
                                                </a></strong></span>
                                    @endif
                                </div>
                            @endif
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
                    </article>
                @endisset
            @endforeach
        </section>
        <section class="container-items">
            <h3 hidden>TOP ANIME ENDINGS OF ALL TIME</h3>
            <div class="top-header">
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
                    <article class="top-item">
                        <div class="item-place">
                            <span><strong>{{ $j++ }}</strong></span>
                        </div>
                        <div class="item-info">
                            <div class="item-post-info">
                                <span><a href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                        class="text-light no-deco">{{ $song->post->title }}</a></span>
                            </div>
                            @if (isset($song->song_romaji) || isset($song->song_en) || isset($song->song_jp))
                                <div class="item-song-info">
                                    <span id="song-title"><strong><a
                                                href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}"
                                                class="no-deco text-light">
                                                @if (isset($song->song_romaji))
                                                    {{ $song->song_romaji }}
                                                @else
                                                    @if (isset($song->song_en))
                                                        {{ $song->song_en }}
                                                    @else
                                                        @if (isset($song->song_jp))
                                                            {{ $song->song_jp }}
                                                        @endif
                                                    @endif
                                                @endif
                                            </a></strong></span>
                                    @if (isset($song->artist->name))
                                        <span style="margin-left: 4px;margin-right:4px;">By</span>
                                        <span id="song-artist"><strong><a
                                                    href="{{ route('artist.show', [$song->id,$song->artist->name_slug]) }}"
                                                    class="no-deco text-light">
                                                    {{ $song->artist->name }}
                                                </a></strong></span>
                                    @endif
                                </div>
                            @endif
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
                    </article>
                @endisset
            @endforeach
        </section>
    </div>
</section>
