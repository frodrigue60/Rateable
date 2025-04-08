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
        <div class="d-flex justify-content-center align-items-center">
            <h2 class="p-0 m-0">{{ $currentSeason->name }} {{ $currentYear->name }}</h2>
        </div>
        <div>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 id="section-header" class="p-0 m-0">OPENINGS</h3>
                <button type="button" class="btn btn-primary" id="toggle-type-btn">
                    <i class="fa-solid fa-rotate"></i> <span id="btn-toggle-text">Endings</span>
                </button>

            </div>
            <div class="contenedor-tarjetas mb-3" id="content-container">
                {{-- DATA --}}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            //console.log('DOM LOADED');
            const baseUrl = document.querySelector('meta[name="base-url"]').content;
            const csrf_token = document.querySelector('meta[name="csrf-token"]').content;
            const token = localStorage.getItem('api_token');

            // Estado inicial
            let currentType = 'OP';
            const contentContainer = document.getElementById('content-container');
            const sectionHeader = document.getElementById('section-header');
            const toggleBtn = document.getElementById('toggle-type-btn');

            fetchData(currentType);

            // Función para hacer el fetch
            async function fetchData(type) {
                toggleBtn.disabled = true;

                try {
                    const response = await fetch(baseUrl + '/api/seasonal', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf_token,
                            'Authorization': 'Bearer ' + token,
                        },
                        body: JSON.stringify({
                            type: type
                        })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();

                    renderData(data);
                    updateHeader(type);

                } catch (error) {
                    console.error('Error:', error);
                    // Mostrar mensaje de error al usuario
                    contentContainer.innerHTML = `<p class="error">Error loading ${type} data</p>`;
                } finally {
                    toggleBtn.disabled = false;
                }
            }

            // Función para actualizar el encabezado
            function updateHeader(type) {
                sectionHeader.textContent = type === 'OP' ?
                    'OPENINGS' :
                    'ENDINGS';
                document.querySelector('#btn-toggle-text').textContent = type === 'OP' ?
                    'Endings' :
                    'Openings';
            }

            // Función para renderizar datos (ejemplo básico)
            function renderData(data) {
                contentContainer.innerHTML = data.themes;

            }

            // Manejador del botón
            toggleBtn.addEventListener('click', () => {
                currentType = currentType === 'OP' ? 'ED' : 'OP';
                fetchData(currentType);
            });
        });
    </script>
@endsection
