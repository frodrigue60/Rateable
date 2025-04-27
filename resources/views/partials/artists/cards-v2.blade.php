@foreach ($artists as $artist)
    @php
        $thumbnailUrl = '';

        if ($artist->thumbnail != null && Storage::disk('public')->exists($artist->thumbnail)) {
            $thumbnailUrl = Storage::url($artist->thumbnail);
        } elseif ($artist->thumbnail_src != null) {
            $thumbnailUrl = $artist->thumbnail_src;
        } else {
            $thumbnailUrl =  asset('resources/images/default-thumbnail.jpg') ;
        }
    @endphp
    <div class="media-card">
        <div class="position-relative overflow-hidden">
            <a href="{{ $artist->url }}" class="cover">
                <img class="image loaded z-0" loading="lazy" src="{{ $thumbnailUrl }}" alt="{{ $artist->title }}">
            </a>
        </div>
        <div>
            <a href="{{ $artist->url }}" class="title">
                {{ $artist->name }}
            </a>
        </div>
    </div>
@endforeach
