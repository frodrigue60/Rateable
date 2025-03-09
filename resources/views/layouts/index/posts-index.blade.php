<section class="contenedor-main">
    <div class="top-header mb-2 mt-2">
        <div>
            <h2 class="text-light mb-0">Most Pupular</h2>
        </div>
        <div>
            <a href="{{ route('themes', 'sort=likeCount') }}" class="btn btn-sm color4">Most Popular</a>
        </div>
    </div>
    {{-- POPULAR POSTS --}}
    <section class="contenedor-tarjetas-main">
        @foreach ($popular->take(14) as $variant)
            @isset($variant->song->post)
                @include('layouts.variant.card')
            @endisset
        @endforeach
    </section>
    <div class="top-header mb-2 mt-2">
        <div>
            <h2 class="text-light mb-0">Most Viewed</h2>
        </div>
        <div>
            <a href="{{ route('themes', 'sort=view_count') }}" class="btn btn-sm color4">Most Viewed</a>
        </div>
    </div>
    {{-- MOST VIEWED --}}
    <section class="contenedor-tarjetas-main">
        @foreach ($viewed->take(14) as $variant)
            @isset($variant->song->post)
                @include('layouts.variant.card')
            @endisset
        @endforeach
    </section>
</section>
