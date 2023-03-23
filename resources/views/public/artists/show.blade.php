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
                     @foreach ($openings as $post)
                     <article class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header text-light">
                                <h3 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h3>
                            </div>
                            <div class="{{ $post->type == 'OP' ? 'tag' : 'tag2' }}">
                                <span class="tag-content ">{{ $post->suffix != null ? $post->suffix : $post->type }}</span>
                            </div>
                            <a class="no-deco" href="{{ route('post.show', [$post->id, $post->slug,$post->suffix != null ? $post->suffix : $post->type]) }}">
                                <img class="thumb" loading="lazy" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}" title="{{ $post->title }}">
                            </a>
                            <div class="tarjeta-footer text-light">
                                <span>{{ $post->likeCount }} <i class="fa fa-heart"></i></span>
                                <span>{{ $post->view_count }} <i class="fa fa-eye"></i></span>
                                <span>
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($post->averageRating) }}
                                            @break
        
                                            @case('POINT_10_DECIMAL')
                                                {{ round($post->averageRating / 10, 1) }}
                                            @break
        
                                            @case('POINT_10')
                                                {{ round($post->averageRating / 10) }}
                                            @break
        
                                            @case('POINT_5')
                                                {{ round($post->averageRating / 20) }}
                                            @break
        
                                            @default
                                                {{ round($post->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($post->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </article>
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
                     @foreach ($endings as $post)
                     <article class="tarjeta">
                        <div class="textos">
                            <div class="tarjeta-header text-light">
                                <h3 class="text-shadow text-uppercase post-titles">{{ $post->title }}</h3>
                            </div>
                            <div class="{{ $post->type == 'OP' ? 'tag' : 'tag2' }}">
                                <span class="tag-content ">{{ $post->suffix != null ? $post->suffix : $post->type }}</span>
                            </div>
                            <a class="no-deco" href="{{ route('post.show', [$post->id, $post->slug,$post->suffix != null ? $post->suffix : $post->type]) }}">
                                <img class="thumb" loading="lazy" src="{{ asset('/storage/thumbnails/' . $post->thumbnail) }}"
                                    alt="{{ $post->title }}" title="{{ $post->title }}">
                            </a>
                            <div class="tarjeta-footer text-light">
                                <span>{{ $post->likeCount }} <i class="fa fa-heart"></i></span>
                                <span>{{ $post->view_count }} <i class="fa fa-eye"></i></span>
                                <span>
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($post->averageRating) }}
                                            @break
        
                                            @case('POINT_10_DECIMAL')
                                                {{ round($post->averageRating / 10, 1) }}
                                            @break
        
                                            @case('POINT_10')
                                                {{ round($post->averageRating / 10) }}
                                            @break
        
                                            @case('POINT_5')
                                                {{ round($post->averageRating / 20) }}
                                            @break
        
                                            @default
                                                {{ round($post->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($post->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </article>
                     @endforeach
                 @endisset
             </section>
         </section>
     </div>
 @endsection
