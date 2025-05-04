 @extends('layouts.app')
 @section('meta')
 @endsection
 @section('content')
     @php
         $thumbnailUrl = '';

         if ($artist->thumbnail != null && Storage::disk('public')->exists($artist->thumbnail)) {
             $thumbnailUrl = Storage::url($artist->thumbnail);
         } elseif ($artist->thumbnail_src != null) {
             $thumbnailUrl = $artist->thumbnail_src;
         } else {
             $thumbnailUrl = asset('resources/images/default-thumbnail.jpg');
         }
     @endphp
     <div class="container">
         <div class="row">
             <div class="col-12 col-lg-3 text-center" {{-- style="border: solid 1px blue" --}}>
                 <div class="position-relative overflow-hidden rounded-1 m-2">
                     <img class="" loading="lazy" src="{{ $thumbnailUrl }}" alt="{{ $artist->title }}">
                 </div>
                 <div>
                     <h6>Alternative Name</h6>
                     <span>{{ $artist->name_js ? $artist->name_jp : 'N/A' }}</span>
                 </div>
                 <hr>
                 <div>
                     <h6>Active</h6>
                     <span> Date 1 - Date 2</span>
                 </div>
                 <hr>
                 <div class="d-flex flex-column">
                     <h6>Members</h6>
                     <span>Member 1</span>
                     <span>Member 2</span>
                     <span>Member 3</span>
                     <span>Member 4</span>
                 </div>
                 <hr>
                 <div class="d-flex flex-column">
                     <h6>Related Series</h6>
                     <a href="">Serie 1</a>
                     <a href="">Serie 2</a>
                     <a href="">Serie 3</a>
                     <a href="">Serie 4</a>
                 </div>
                 <hr>
                 <div class="d-flex flex-column">
                     <h6>External Links</h6>
                     <a href="">Link 1</a>
                     <a href="">Link 2</a>
                     <a href="">Link 3</a>
                     <a href="">Link 4</a>
                     <a href="">Link 5</a>
                 </div>
             </div>
             <div class="col-12 col-lg-9" {{-- style="border: solid 1px red" --}}>
                 <div class="">
                     <div>
                         <h2>Artist Infomation</h2>
                     </div>
                     <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                         et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                         aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                         cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                         culpa qui officia deserunt mollit anim id est laborum.</p>
                 </div>
                 <div class="my-3 bg-secondary p-2">
                     <h4>Menu Options</h4>
                 </div>
                 <hr>
                 <div class=" d-flex flex-wrap gap-2 justify-content-center">
                     @include('partials.songs.cards-v2', ['sonsg' => $songs])
                 </div>

             </div>
         </div>



     </div>
 @endsection

 @push('script')
 @endpush
