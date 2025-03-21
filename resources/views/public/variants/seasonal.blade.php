@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="{{ url()->current() }}" />
    @if (isset($currentSeason->name))
        <meta name="keywords"
            content="ranking, top, anime openings {{ $currentSeason->name }}, openings anime {{ $currentSeason->name }}, anime endings {{ $currentSeason->name }}, endings anime {{ $currentSeason->name }}, of {{ $currentSeason->name }}">
        @if (Request::is('openings'))
            <title>Openings {{ $currentSeason->name }}</title>
            <meta title="Openings {{ $currentSeason->name }}">
            <meta name="description" content="Openings of {{ $currentSeason->name }} anime season">

            <meta property="og:type" content="website" />
            {{-- <meta property="og:image" content="{{ asset('resources/images/og-image-wide.png') }}">
        <meta property="og:image:secure_url" content="{{ asset('resources/images/og-image-wide.png') }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="828">
        <meta property="og:image:height" content="450">
        <meta property="og:image:alt" content="Anirank banner" /> --}}
            <meta property="og:url" content="{{ url()->current() }}" />

            <meta name="twitter:card" content="summary" />
            <meta name="twitter:site" content="@frodrigue60" />
            <meta name="twitter:creator" content="@frodrigue60" />
            <meta property="og:title" content="Openings {{ $currentSeason->name }}" />
            <meta property="og:description" content="Openings of {{ $currentSeason->name }} anime season" />
        @endif
        @if (Request::is('endings'))
            <title>Endings {{ $currentSeason->name }}</title>
            <meta title="Endings {{ $currentSeason->name }}">
            <meta name="description" content="Endings of {{ $currentSeason->name }} anime season">

            <meta property="og:type" content="website" />
            {{-- <meta property="og:image" content="{{ asset('resources/images/og-image-wide.png') }}">
        <meta property="og:image:secure_url" content="{{ asset('resources/images/og-image-wide.png') }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="828">
        <meta property="og:image:height" content="450">
        <meta property="og:image:alt" content="Anirank banner" /> --}}
            <meta property="og:url" content="{{ url()->current() }}" />

            <meta name="twitter:card" content="summary" />
            <meta name="twitter:site" content="@frodrigue60" />
            <meta name="twitter:creator" content="@frodrigue60" />
            <meta property="og:title" content="Endings {{ $currentSeason->name }}" />
            <meta property="og:description" content="Endings of {{ $currentSeason->name }} anime season" />
        @endif
    @endif
@endsection
@section('content')
    <div class="container mb-3 text-light">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="p-0 m-0">{{ $currentSeason->name }} {{ $currentYear->name }}</h3>
            {{-- <button class="btn btn-secondary text-light">
                <i class="fa-solid fa-ranking-star"></i>
            </button> --}}
        </div>
        <hr>
        <div>
            <div class="my-2">
                <h4>OPENINGS</h4>
            </div>
            <div class="d-flex gap-3 mb-3" id="div-openings">
                {{-- @foreach ($openings as $variant)
                    @include('layouts.variant.card')
                @endforeach --}}
            </div>
        </div>
        <div>
            <div class="my-2">
                <h4>ENDINGS</h4>
            </div>
            <div class="d-flex gap-3 mb-3" id="div-endings">
                {{-- @foreach ($endings as $variant)
                    @include('layouts.variant.card')
                @endforeach --}}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            console.log('DOM LOADED');
            const baseUrl = document.querySelector('meta[name="base-url"]').content;
            const csrf_token = document.querySelector('meta[name="csrf-token"]').content;
            const divOpenings = document.querySelector('#div-openings');
            const divEndings = document.querySelector('#div-endings');

            fetch(baseUrl + '/api/seasonal', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        'X-CSRF-TOKEN': csrf_token,
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        console.log(response.status);
                        return;
                    } else {
                        return response.json();
                    }
                })
                .then(data => {
                    if (data === "") {
                        console.error("No data from backend");
                        return;
                    } else {
                        //console.log(data);

                        divOpenings.innerHTML += data.openings;
                        divEndings.innerHTML += data.endings;

                        /* let titles = document.querySelectorAll('.post-titles');

                        function cutTitles() {
                            titles.forEach(title => {
                                if (title.textContent.length > 25) {
                                    title.textContent = title.textContent.substr(0, 25) + "...";
                                }
                            });
                        }
                        cutTitles(); */
                    }
                })
                .catch(error => console.error(error));
        });
    </script>
@endsection
