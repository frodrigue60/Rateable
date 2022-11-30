@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- openings columm --}}
            <div class="col">
                <h2 class="text-center text-light">TOP OPENINGS - {{ $currentSeason->name }}</h2>
                <table class="table table-dark">
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
                                <td scope="row">{{ $opening->averageRating/20 }} <i class="fa fa-star"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- endings columm --}}
            <div class="col">
                <h2 class="text-center text-light">TOP ENDINGS - {{ $currentSeason->name }}</h2>
                <table class="table table-dark">
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
                                <td scope="row">{{ $ending->averageRating/20 }} <i class="fa fa-star"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
@endsection
