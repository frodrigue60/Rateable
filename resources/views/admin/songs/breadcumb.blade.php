<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.post.index') }}">index</a></li>
        @if (isset($post))
            <li class="breadcrumb-item"><a href="{{ route('song.post.manage', $post->id) }}">{{ $post->title }}</a></li>
            @if (isset($song))
                <li class="breadcrumb-item"><a href="">{{ $song->suffix }}</a></li>
            @endif
        @else
            @if (isset($song->post))
                <li class="breadcrumb-item"><a
                        href="{{ route('song.post.manage', $song->post->id) }}">{{ $song->post->title }}</a></li>
                @if (isset($song))
                    <li class="breadcrumb-item"><a href="">{{ $song->suffix }}</a></li>
                @endif

            @endif
        @endif
    </ol>
</nav>
