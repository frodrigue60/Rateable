@foreach ($posts as $post)
    @php
        if (Storage::disk('public')->exists($post->thumbnail)) {
            $thumbnailUrl = Storage::url($post->thumbnail);
        } else {
            $thumbnailUrl = $post->thumbnail_src;
        }

    @endphp
    {{-- <article class="tarjeta">
        <a class="no-deco" href="{{ $post->url }}" rel="nofollow noopener noreferrer">
            <div class="textos">
                <div class="tarjeta-header text-light">
                    <h3 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h3>
                </div>
                <img class="thumb" loading="lazy" src="{{ $thumbnail_url }}" alt="{{ $post->title }}"
                    title="{{ $post->title }}">
                <div class="tarjeta-footer justify-content-center">
                    <span class="text-light">
                        {{ $post->songs->count() }} <i class="fa-solid fa-music"></i>
                    </span>
                </div>
            </div>
        </a>
    </article> --}}
    <div class="d-flex flex-column" style="border: solid 1px red;">
        <div class="d-flex gap-3">
            <img src="{{ $thumbnailUrl }}" alt="" style="max-width: 120px;height:auto;">
            <div class="row">
                <div class="col text-truncate">
                    <span>{{ $post->title }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-1 justify-content-center">
            <a class="btn btn-sm text-light" data-bs-toggle="collapse" href="#collapseExample{{ $post->id }}" role="button" aria-expanded="false" aria-controls="collapseExample{{ $post->id }}">
                Show themes
              </a>
        </div>
        <div class="collapse" id="collapseExample{{ $post->id }}">
            <div class="">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-dark text-light">An item</li>
                    <li class="list-group-item">A second item</li>
                    <li class="list-group-item">A third item</li>
                    <li class="list-group-item">A fourth item</li>
                    <li class="list-group-item">And a fifth one</li>
                </ul>
            </div>
        </div>
    </div>
@endforeach
