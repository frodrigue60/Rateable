@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection
@section('content')
    <div class="container">
        <h1 class="text-center text-light" id="section-header"></h1>
        <div class="container-top">
            <section class="container-items text-light">
                @if (Request::routeIs('/') || Request::routeIs('global.ranking'))
                    <h2 hidden class="text-light">Best Anime Openings of All Time</h2>
                @endif
                <div class="top-header-ranking">
                    <div>
                        <span>Top Openings</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" id="toggle-type-btn">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                </div>
                {{-- @include('layouts.top.openings') --}}
                <div id="container-ops" class="gap-2 d-flex flex-column">
                    
                </div>
            </section>
            {{-- ENDINGS --}}
            <section class="container-items text-light">
                @if (Request::routeIs('/') || Request::routeIs('global.ranking'))
                    <h2 hidden class="text-light">Best Anime Endings of All Time</h2>
                @endif
                <div class="top-header-ranking">
                    <div>
                        <span>Top Endings</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" id="toggle-type-btn">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                </div>
                <div id="container-eds" class="gap-2 d-flex flex-column">

                </div>
                {{-- @include('layouts.top.endings') --}}
            </section>
        </div>
    </div>

@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            console.log('DOM LOADED');
            const baseUrl = document.querySelector('meta[name="base-url"]').content;
            const csrf_token = document.querySelector('meta[name="csrf-token"]').content;
            const token = localStorage.getItem('api_token');

            // Estado inicial
            let rankingType = '0';
            //const contentContainer = document.getElementById('content-container');
            const sectionHeader = document.getElementById('section-header');
            const toggleBtn = document.getElementById('toggle-type-btn');
            const containerOps = document.getElementById('container-ops');
            const containerEds = document.getElementById('container-eds');

            fetchData(rankingType);

            // Función para hacer el fetch
            async function fetchData(rankingType) {
                toggleBtn.disabled = true;

                try {
                    const response = await fetch(baseUrl + '/api/ranking', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf_token,
                            'Authorization': 'Bearer ' + token,
                        },
                        body: JSON.stringify({
                            ranking_type: rankingType
                        })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();

                    renderData(data);
                    updateHeader(rankingType);
                    console.log(data);

                } catch (error) {
                    console.error('Error:', error);
                    // Mostrar mensaje de error al usuario
                    contentContainer.innerHTML = `<p class="error">Error loading ${rankingType} data</p>`;
                } finally {
                    toggleBtn.disabled = false;
                }
            }

            // Función para actualizar el encabezado
            function updateHeader(rankingType) {
                sectionHeader.textContent = rankingType === '0' ?
                    'Ranking Openings & Endings Of All Time' :
                    'Seasonal Ranking Openings & Endings';
            }

            // Función para renderizar datos (ejemplo básico)
            function renderData(data) {
                console.log('renderData() ');
                console.log(data);
                containerOps.innerHTML = data.openings;
                containerEds.innerHTML = data.endings;
            }

            // Manejador del botón
            toggleBtn.addEventListener('click', () => {
                rankingType = rankingType === '0' ? '1' : '0';
                fetchData(rankingType);
            });
        });
    </script>
@endsection
