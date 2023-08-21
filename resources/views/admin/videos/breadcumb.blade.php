<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @if (isset($song))
            <li class="breadcrumb-item"><a
                    href="{{ route('song.post.manage', $song->post->id) }}">{{ $song->post->title }}</a></li>
            @if (isset($song->suffix))
                <li class="breadcrumb-item"><a href="{{ route('admin.videos.index', $song->id) }}">{{ $song->suffix }}</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('admin.videos.index', $song->id) }}">Videos</a>
                </li>
            @endif
        @else
            @if (isset($video))
                <li class="breadcrumb-item"><a
                        href="{{ route('song.post.manage', $video->song->post->id) }}">{{ $video->song->post->title }}</a>
                </li>
                @if (isset($video->song))
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.videos.index', $video->song->id) }}">{{ $video->song->suffix }}</a>
                    </li>
                    <li class="breadcrumb-item"><a
                        href="{{ route('admin.videos.index', $video->song->id) }}">Videos</a>
                </li>
                    @if (isset($video))
                    <li class="breadcrumb-item"><a
                        href="">Edit: {{ $video->id }}</a>
                </li>
                    @endif
                @endif
            @endif
        @endif
    </ol>
</nav>
