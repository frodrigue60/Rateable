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
        <div class="position-relative overflow-hidden" style="min-height: 95px;border-radius:5px;">
            <img class="card-2-bg" src="{{ $img_url }}" alt="{{ $variant->song->post->title }}">
            <div class="gradient-1">
            </div>
            <div class="card-2-data p-2 d-flex flex-row justify-content-between w-100 gap-2">
                <div class="d-flex flex-column overflow-hidden">
                    <div class="m-2 fs-5">
                        {{-- <span><i class="fa-solid fa-award"></i></span> --}}
                        <span class="text-light">
                            # {{ $j++ }}
                        </span>
                    </div>
                    @isset($variant->song)
                        <a class="no-deco text-light bold text-truncate" href="{{ $variant->url }}">{{ $variant->song->name }}
                        </a>
                        {{-- <span class="d-inline-block text-truncate">
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
                        </span> --}}
                        <a class="no-deco text-light text-truncate" target="_blank"
                            href="{{ $variant->song->post->url }}">{{ $variant->song->post->title }}</a>
                    @endisset
                </div>
                <div class="d-flex align-items-end">
                    <div class="badge rounded-pill bg-dark fw-light">
                        <span id="score">{{ $variant->scoreString }}</span>
                        <span>
                            @if ($variant->userScore)
                                <i style="color: rgb(162, 240, 181)" class="fa fa-star" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-star" aria-hidden="true"></i>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endisset
@endforeach
