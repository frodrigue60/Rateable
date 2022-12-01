@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- openings columm --}}
            <div class="col">
                <h2 class="text-center text-light">TOP OPENINGS - {{ $currentSeason->name }}</h2>
                <table class="table table-dark text-center">
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
                                <td scope="row">{{ $opening->title }}</td>
                                <td scope="row">

                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($opening->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($opening->averageRating / 10, 1) }}
                                                <i class="fa fa-star"></i>
                                            @break

                                            @case('POINT_10')
                                                {{ round($opening->averageRating / 10) }} <i class="fa fa-star"></i>
                                            @break

                                            @case('POINT_5')
                                                {{ round($opening->averageRating / 20) }} <i class="fa fa-star"></i>
                                            @break

                                            @default
                                                {{ round($opening->averageRating) }}
                                        @endswitch
                                    @else
                                    {{ round($opening->averageRating / 10, 1) }}
                                    <i class="fa fa-star"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- endings columm --}}
            <div class="col">
                <h2 class="text-center text-light">TOP ENDINGS - {{ $currentSeason->name }}</h2>
                <table class="table table-dark text-center">
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
                                <td scope="row">{{ $ending->title }}</td>
                                <td scope="row">
                                    @if (isset($score_format))
                                        @switch($score_format)
                                            @case('POINT_100')
                                                {{ round($ending->averageRating) }}
                                            @break

                                            @case('POINT_10_DECIMAL')
                                                {{ round($ending->averageRating / 10, 1) }} <i class="fa fa-star"></i>
                                            @break

                                            @case('POINT_10')
                                                {{ round($ending->averageRating / 10) }} <i class="fa fa-star"></i>
                                            @break

                                            @case('POINT_5')
                                                {{ round($ending->averageRating / 20) }} <i class="fa fa-star"></i>
                                            @break

                                            @default
                                                {{ round($ending->averageRating) }}
                                        @endswitch
                                    @else
                                    {{ round($opening->averageRating / 10, 1) }}
                                    <i class="fa fa-star"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
