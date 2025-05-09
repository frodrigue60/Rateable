@php
    $j = 1;
@endphp
@foreach ($items as $song)
    @isset($song->post)
        @php
            $img_url = null;
            if ($song->post->thumbnail != null) {
                if (Storage::disk('public')->exists($song->post->thumbnail)) {
                    $img_url = Storage::url($song->post->thumbnail);
                }
            } else {
                $img_url =
                    'https://static.vecteezy.com/system/resources/thumbnails/005/170/408/small/banner-abstract-geometric-white-and-gray-color-background-illustration-free-vector.jpg';
            }

        @endphp
        {{-- <div class="card-2 mb-2">
            <img class="card-2-bg" src="{{ $img_url }}" alt="{{ $song->post->title }}">
            <div class="gradient-1">
                <div class="m-2 fs-5">

                    <span class="">
                        # {{ $j++ }}
                    </span>
                </div>
            </div>
            <div class="card-2-data p-2 d-flex flex-row justify-content-between w-100 gap-2">
                <div class="d-flex flex-column overflow-hidden">
                    @isset($song)
                        <a class="no-deco  bold text-truncate" href="{{ $song->urlFirstVariant }}">{{ $song->name }}
                        </a>
                        <span class="d-inline-block text-truncate">
                            @if (isset($song->artists) && count($song->artists) != 0)
                                @foreach ($song->artists as $index => $artist)
                                    <a class="no-deco " href="{{ $artist->url }}">{{ $artist->name }}</a>
                                    @if ($index < count($song->artists) - 1)
                                        ,
                                    @endif
                                @endforeach
                            @else
                                <span>N/A</span>
                            @endif
                        </span>
                        <a class="no-deco  text-truncate" target="_blank" rel="noopener noreferrer"
                            href="{{ $song->post->url }}">{{ $song->post->title }}</a>
                    @endisset
                </div>
                <div class="d-flex align-items-end">
                    <div class="badge rounded-pill  fw-medium">
                        <span id="score">{{ $song->scoreString }}</span>
                        <span>
                            @if ($song->userScore)
                                <i style="color: rgb(162, 240, 181)" class="fa fa-star" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-star" aria-hidden="true"></i>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="card d-flex flex-row gap-2 rounded-1 w-100 overflow-hidden">
            <div>
                <img class="" style="width: auto;height:100px;" src="{{ $img_url }}" alt="{{ $song->post->title }}">
            </div>
            <div class="d-flex w-100 overflow-hidden p-2 gap-2">
                <div class="d-flex flex-column justify-content-center me-auto overflow-hidden">
                    <div class="overflow-hidden  text-truncate">
                         <a class="no-deco " href="{{ $song->url }}">
                        {{ $song->name }}
                    </a>
                    </div>
                    <div class="overflow-hidden text-truncate">
                        @if (isset($song->artists) && count($song->artists) != 0)
                                @foreach ($song->artists as $index => $artist)
                                    <a class="no-deco " href="{{ $artist->url }}">{{ $artist->name }}</a>
                                    @if ($index < count($song->artists) - 1)
                                        ,
                                    @endif
                                @endforeach
                            @else
                                <span>N/A</span>
                            @endif
                    </div>
                    <div class="overflow-hidden text-truncate">
                        <a class="no-deco " href="{{ $song->post->url }}">
                        {{ $song->post->title }}
                    </a>
                    </div>

                </div>
                <div class="d-flex align-items-end">
                    <div class="badge bg-secondary rounded-pill  fw-medium">
                        <span id="score">{{ round($song->averageRating) }}</span>
                        <span><i class="fa-solid fa-star"></i></span>
                    </div>
                </div>
            </div>
        </div>
    @endisset
@endforeach
