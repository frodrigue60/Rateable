<section class="contenedor-main">
    {{-- RECENTS --}}
    <section class="carouselContainermain">
        <div class="top-header">
            <div>
                <h2 class="text-light mb-0">Recently added</h2>
            </div>
            <div>
                <a href="{{ route('themes', 'sort=recent') }}" class="btn btn-sm color4">All Recently Posts</a>
            </div>
        </div>
        <div class="owl-carousel carousel-recents-main">
            @foreach ($recently as $variant)
                @include('layouts.variant.card')
            @endforeach
        </div>
    </section>
</section>
