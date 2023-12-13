@php
    $j = 1;
@endphp
@foreach ($endings as $song)
    @isset($song->post)
        @php
            $song_name = null;

            if (isset($song->song_romaji)) {
                $song_name = $song->song_romaji;
            } else {
                if (isset($song->song_en)) {
                    $song_name = $song->song_en;
                } else {
                    if (isset($song->song_jp)) {
                        $song_name = $song->song_jp;
                    }
                }
            }

            $img_url = null;
            if ($song->post->banner != null) {
                $img_url = file_exists(asset('/storage/anime_banner/' . $song->post->banner)) ? asset('/storage/anime_banner/' . $song->post->banner) : $song->post->banner_src;
            } else {
                $img_url = 'https://static.vecteezy.com/system/resources/thumbnails/005/170/408/small/banner-abstract-geometric-white-and-gray-color-background-illustration-free-vector.jpg';
            }

        @endphp
        <div class="top-item">
            <div class="item-place">
                <span>{{ $j++ }}</span>
            </div>
            <div class="item-info" style="background-image: url({{ $img_url }})">
                <div class="item-info-filter"></div>
                @isset($song)
                    <div class="item-song-info">
                        {{-- SONG TITLE --}}
                        <div class="text-ellipsis">
                            @if ($song_name != null)
                                <a class="no-deco text-light bold"
                                    href="{{ route('song.show', [$song->id, $song->post->slug, $song->suffix != null ? $song->suffix : $song->type]) }}">{{ $song_name }}</a>
                            @else
                                <strong>N/A</strong>
                            @endif
                        </div>
                        {{-- SONG ARTISTS --}}
                        <div class="text-ellipsis">
                            @if (isset($song->artists) && count($song->artists) != 0)
                                @foreach ($song->artists as $index => $artist)
                                    <a class="no-deco text-light"
                                        href="{{ route('artist.show', [$artist->id, $artist->name_slug]) }}">{{ $artist->name }}</a>
                                    @if ($index < count($song->artists) - 1)
                                        ,
                                    @endif
                                @endforeach
                            @else
                                <span>N/A</span>
                            @endif
                        </div>
                    </div>
                @endisset
                {{-- ANIME TITLE --}}
                <div class="item-post-info">
                    <a class="no-deco text-light" target="_blank"
                        href="{{ route('post.show', [$song->post->id, $song->post->slug]) }}">{{ $song->post->title }}</a>
                    {{ $song->suffix ? '(' . $song->suffix . ')' : '' }}
                </div>
            </div>
            <div class="item-score">
                <span>{{ $song->score != null ? $song->score : 'n/a' }} <i class="fa fa-star" aria-hidden="true"></i></span>
            </div>
        </div>
    @endisset
@endforeach
