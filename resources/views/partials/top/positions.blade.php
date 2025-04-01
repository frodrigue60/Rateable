@php
    $j = 1;
@endphp
@foreach ($items as $variant)
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
                    <div class="item-song-info">
                        {{-- SONG TITLE --}}
                        <div class="text-ellipsis">
                            <a class="no-deco text-light bold" href="{{ $variant->url }}">{{ $variant->song->name }} </a>
                        </div>
                        {{-- SONG ARTISTS --}}
                        <div class="text-ellipsis">
                            @if (isset($variant->song->artists) && count($variant->song->artists) != 0)
                                @foreach ($variant->song->artists as $index => $artist)
                                    <a class="no-deco text-light" href="{{ $artist->url }}">{{ $artist->name }}</a>
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
                <span>{{ $variant->score }}
                    @if ($variant->userScore)
                        <i style="color: rgb(162, 240, 181)" class="fa fa-star" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-star" aria-hidden="true"></i>
                    @endif
                </span>
            </div>
        </div>
    @endisset
@endforeach
