@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container-top">

            <div class="container-items" style="width: 100%">
                <div id="top-header">
                    <div>
                        <h3 class="mb-0">Top 100 Openings</h3>
                    </div>
                </div>
                @for ($j = 1; $j < 0; $j++)
                @endfor
                @foreach ($openings->sortByDesc('averageRating') as $post)
                    <div class="top-item">
                        <div id="item-place">
                            <span><strong>{{ $j++ }}</strong></span>
                        </div>
                        <div id="item-info">
                            <div id="item-post-info">
                                <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                        class="text-light no-deco">{{ $post->title }}</a></span>
                            </div>
                            @isset($post->song->song_romaji)
                                    <div id="item-song-info">
                                        <span><strong>{{ $post->song->song_romaji }}</strong> By
                                            <strong>{{ $post->artist->name }}</strong></span>
                                    </div>
                                @endisset
                        </div>
                        <div id="item-score">
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
                @endforeach
            </div>
            <div class="container-items">
                <div id="top-header">
                    <div>
                        <h3 class="mb-0">Top 100 Endings</h3>
                    </div>

                </div>
                @for ($j = 1; $j < 0; $j++)
                @endfor
                @foreach ($endings->sortByDesc('averageRating') as $post)
                    <div class="top-item">
                        <div id="item-place">

                            <span><strong>{{ $j++ }}</strong></span>
                        </div>
                        <div id="item-info">
                            <div id="item-post-info">
                                <span><a href="{{ route('showbyslug', [$post->id, $post->slug]) }}"
                                        class="text-light no-deco">{{ $post->title }}</a></span>
                            </div>
                            @isset($post->song->song_romaji)
                                    <div id="item-song-info">
                                        <span><strong>{{ $post->song->song_romaji }}</strong> By
                                            <strong>{{ $post->artist->name }}</strong></span>
                                    </div>
                                @endisset
                        </div>
                        <div id="item-score">
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
                @endforeach
            </div>
        </div>
    </div>
@endsection
