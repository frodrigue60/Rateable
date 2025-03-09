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
                         @include('layouts.song-card')
                     @endforeach
                 @endisset
             </section>
         </section>
     </div>
 @endsection
