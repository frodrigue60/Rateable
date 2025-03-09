 @extends('layouts.app')
 @section('meta')
     @if (isset($tagName))
         <title>{{ $tagName->name }} Openings & Endings</title>
         <meta title="{{ $tagName->name }}  Openings & Endings">
     @endif
     @if (isset($artist))
         <title>{{ $artist->name }} Openings & Endings</title>
         <meta title="{{ $artist->name }}  Openings & Endings">
     @endif
 @endsection
 @section('content')
     <div class="container">
         <div class="container text-center text-light">
             @isset($tagName)
                 <h1>{{ $tagName->name }}</h1>
             @endisset
             @isset($artist)
                 <h1>{{ $artist->name }}
                     @isset($artist->name_jp)
                         ({{ $artist->name_jp }})
                     @endisset
                 </h1>
             @endisset
         </div>
         <section>
             <div class="color1">
                 <h2 class="text-light">OPENINGS</h2>
             </div>
             <section class="contenedor-favoritos">
                 @isset($openings)
                     @foreach ($openings as $song)
                         {{-- <article class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header text-light">
                                <h3 class="text-shadow text-uppercase post-titles">{{ $song->post->title }}</h3>
                            </div>
                            <div class="{{ $song->type == 'OP' ? 'tag' : 'tag2' }}">
                                <span class="tag-content ">{{ $song->slug != null ? $song->slug : $song->type }}</span>
                            </div>
                            <a class="no-deco" href="{{ route('song.show', [$song->id,$song->post->slug,$song->slug != null ? $song->slug : $song->type ]) }}">
                                <img class="thumb" loading="lazy" src="{{ asset('/storage/thumbnails/' . $song->post->thumbnail) }}"
                                    alt="{{ $song->post->title }}" title="{{ $song->post->title }}">
                            </a>
                            <div class="tarjeta-footer text-light">
                                <span>{{ $song->likeCount }} <i class="fa fa-heart"></i></span>
                                <span>{{ $song->view_count }} <i class="fa fa-eye"></i></span>
                                <span>
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($song->averageRating) }}
                                            @break
        
                                            @case('POINT_10_DECIMAL')
                                                {{ round($song->averageRating / 10, 1) }}
                                            @break
        
                                            @case('POINT_10')
                                                {{ round($song->averageRating / 10) }}
                                            @break
        
                                            @case('POINT_5')
                                                {{ round($song->averageRating / 20) }}
                                            @break
        
                                            @default
                                                {{ round($song->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($song->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </article> --}}
                         @include('layouts.song-card')
                     @endforeach
                 @endisset
             </section>
         </section>
         <br>
         <section>
             <div class="color1">
                 <h2 class="text-light">ENDINGS</h2>
             </div>
             <section class="contenedor-favoritos">
                 @isset($endings)
                     @foreach ($endings as $song)
                         {{-- <article class="tarjeta">
                             <div class="textos">
                                 <div class="tarjeta-header text-light">
                                     <h3 class="text-shadow text-uppercase post-titles">{{ $song->post->title }}</h3>
                                 </div>
                                 <div class="{{ $song->type == 'OP' ? 'tag' : 'tag2' }}">
                                     <span class="tag-content ">{{ $song->slug != null ? $song->slug : $song->type }}</span>
                                 </div>
                                 <a class="no-deco"
                                     href="{{ route('song.show', [$song->id, $song->post->slug, $song->slug != null ? $song->slug : $song->type]) }}">
                                     <img class="thumb" loading="lazy"
                                         src="{{ asset('/storage/thumbnails/' . $song->post->thumbnail) }}"
                                         alt="{{ $song->post->title }}" title="{{ $song->post->title }}">
                                 </a>
                                 <div class="tarjeta-footer text-light">
                                     <span>{{ $song->likeCount }} <i class="fa fa-heart"></i></span>
                                     <span>{{ $song->view_count }} <i class="fa fa-eye"></i></span>
                                     <span>
                                         @if (isset($score_format))
                                             @switch($score_format)
                                                 @case('POINT_100')
                                                     {{ round($song->averageRating) }}
                                                 @break

                                                 @case('POINT_10_DECIMAL')
                                                     {{ round($song->averageRating / 10, 1) }}
                                                 @break

                                                 @case('POINT_10')
                                                     {{ round($song->averageRating / 10) }}
                                                 @break

                                                 @case('POINT_5')
                                                     {{ round($song->averageRating / 20) }}
                                                 @break

                                                 @default
                                                     {{ round($song->averageRating) }}
                                             @endswitch
                                         @else
                                             {{ round($song->averageRating / 10, 1) }}
                                         @endif
                                         <i class="fa fa-star" aria-hidden="true"></i>
                                     </span>
                                 </div>
                             </div>
                         </article> --}}
                         @include('layouts.song-card')
                     @endforeach
                 @endisset
             </section>
         </section>
     </div>
 @endsection
