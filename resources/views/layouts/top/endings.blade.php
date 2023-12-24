@php
    $j = 1;
@endphp
@foreach ($endings as $variant)
    @isset($variant->song->post)
        @php
            $song_name = null;

            if (isset($variant->song->song_romaji)) {
                $song_name = $variant->song->song_romaji;
            } else {
                if (isset($variant->song->song_en)) {
                    $song_name = $variant->song->song_en;
                } else {
                    if (isset($variant->song->song_jp)) {
                        $song_name = $variant->song->song_jp;
                    }
                }
            }

            $img_url = null;
            if ($variant->song->post->banner != null) {
                $img_url = file_exists(asset('/storage/anime_banner/' . $variant->song->post->banner)) ? asset('/storage/anime_banner/' . $variant->song->post->banner) : $variant->song->post->banner_src;
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
                @isset($variant->song)
                    @php
                        $song_id = $variant->song->id;
                        $post_slug = $variant->song->post->slug;
                        $suffix = $variant->song->suffix != null ? $variant->song->suffix : $variant->song->type;
                        $version = $variant->version;
                        $showVariantRoute = route('p.song.variant.show', [$song_id, $post_slug, $suffix, $version]);
                        $forward_text = ($variant->song->suffix ? $variant->song->suffix : $variant->song->type) . 'v' . $variant->version;
                    @endphp
                    <div class="item-song-info">
                        {{-- SONG TITLE --}}
                        <div class="text-ellipsis">
                            @if ($song_name != null)
                                <a class="no-deco text-light bold"
                                    href="{{ $showVariantRoute }}">{{ $song_name . ' ' . $forward_text }} </a>
                            @else
                                <strong>N/A</strong>
                            @endif
                        </div>
                        {{-- SONG ARTISTS --}}
                        <div class="text-ellipsis">
                            @if (isset($variant->song->artists) && count($variant->song->artists) != 0)
                                @foreach ($variant->song->artists as $index => $artist)
                                    <a class="no-deco text-light"
                                        href="{{ route('artist.show', [$artist->id, $artist->name_slug]) }}">{{ $artist->name }}</a>
                                    @if ($index < count($variant->song->artists) - 1)
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
                        href="{{ route('post.show', [$variant->song->post->id, $variant->song->post->slug]) }}">{{ $variant->song->post->title }}</a>
                </div>
            </div>
            <div class="item-score">
                <span>{{ $variant->score != null ? $variant->score : 'N/A' }} <i class="fa fa-star"
                        aria-hidden="true"></i></span>
            </div>
        </div>
    @endisset
@endforeach
