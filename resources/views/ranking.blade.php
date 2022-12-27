@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- openings columm --}}
            <div class="col-lg">
                <h2 class="text-center text-light">TOP OPENINGS @isset($currentSeason->name)
                        - {{ $currentSeason->name }}
                    @endisset
                </h2>
                <table class="table text-center text-light" style="background-color: #0e3d5f">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Average Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i < 0; $i++)
                        @endfor
                        @foreach ($openings->sortByDesc('averageRating') as $opening)
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td scope="row"><a href="{{ route('show', $opening->id) }}"
                                        class="no-deco text-light">{{ $opening->title }}</a></td>
                                <td scope="row">

                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($opening->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($opening->averageRating / 10, 1) }}
                                            @break

                                            @case('POINT_10')
                                                {{ round($opening->averageRating / 10) }}
                                            @break

                                            @case('POINT_5')
                                                {{ round($opening->averageRating / 20) }}
                                            @break

                                            @default
                                                {{ round($opening->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($opening->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- endings columm --}}
            <div class="col-lg">
                <h2 class="text-center text-light">TOP ENDINGS @isset($currentSeason->name)
                    - {{ $currentSeason->name }}
                    @endisset
                </h2>
                <table class="table text-center text-light" style="background-color: #0e3d5f">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Average Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i < 0; $i++)
                        @endfor
                        @foreach ($endings->sortByDesc('averageRating') as $ending)
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td scope="row"><a href="{{ route('show', $ending->id) }}"
                                        class="no-deco text-light">{{ $ending->title }}</a></td>
                                <td scope="row">
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($ending->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($ending->averageRating / 10, 1) }}
                                            @break

                                            @case('POINT_10')
                                                {{ round($ending->averageRating / 10) }}
                                            @break

                                            @case('POINT_5')
                                                {{ round($ending->averageRating / 20) }}
                                            @break

                                            @default
                                                {{ round($ending->averageRating) }}
                                        @endswitch
                                    @else
                                        {{ round($opening->averageRating / 10, 1) }}
                                    @endif
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
