@php
    $j = 1;
@endphp
@foreach ($openings as $variant)
    @isset($variant->song->post)
        @php
            $img_url = null;
            if ($variant->song->post->banner != null) {
                if (Storage::disk('public')->exists($variant->song->post->banner)) {
                    $img_url = Storage::url($variant->song->post->banner);
                }
            } else {
                $img_url =
                    'https://static.vecteezy.com/system/resources/thumbnails/005/170/408/small/banner-abstract-geometric-white-and-gray-color-background-illustration-free-vector.jpg';
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
                        $suffix = $variant->song->slug != null ? $variant->song->slug : $variant->song->type;
                        $version = $variant->version_number;
                        $forward_text =
                            ($variant->song->slug ? $variant->song->slug : $variant->song->type) .
                            ' v' .
                            $variant->version_number;

                        $song_name = null;

                        $song_name =
                            $variant->song->song_romaji ?? ($variant->song->song_en ?? $variant->song->song_jp);
                    @endphp
                    <div class="item-song-info">
                        {{-- SONG TITLE --}}
                        <div class="text-ellipsis">
                            @if ($song_name != null)
                                <a class="no-deco text-light bold" href="{{ $variant->url }}">{{ $song_name }} </a>
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
                        href="{{ $variant->song->post->url }}">{{ $variant->song->post->title }}</a>
                </div>
            </div>
            <div class="item-score">
                <span>{{ $variant->score != null ? $variant->score : 'N/A' }} <i class="fa fa-star"
                        aria-hidden="true"></i></span>
            </div>
        </div>
    @endisset
@endforeach
