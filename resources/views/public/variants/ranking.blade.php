@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection
@section('content')
    <div class="container">
        <style>
            /* Ocultar mobile-view por defecto */
            .mobile-view {
                display: none;
            }

            /* Tabs - Solo visible en mobile */
            .tabs {
                display: flex;
                margin-bottom: 1rem;
            }

            .tab-button {
                flex: 1;
                padding: 10px;
                background: #f0f0f0;
                border: none;
                cursor: pointer;
            }

            .tab-button.active {
                background: #007bff;
                color: white;
            }

            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            /* Desktop (2 columnas) */
            .desktop-view {
                display: flex;
                gap: 20px;
            }

            /* .openings-column,
                                                                            .endings-column {
                                                                                flex: 1;
                                                                            } */

            /* Media Query para mobile */
            @media (max-width: 768px) {
                .desktop-view {
                    display: none;
                }

                .mobile-view {
                    display: block;
                }
            }
        </style>
        <div class="d-flex flex-row justify-content-between">
            <h2 class="text-center text-light" id="section-header"></h2>
            <div>
                <button type="button" class="btn btn-primary" id="toggle-type-btn">
                    <i class="fa-solid fa-rotate"></i> <span id="toggle-type-btn-span">Seasonal</span>
                </button>
            </div>
        </div>
        <div class="container-top desktop-view">
            {{-- OPENINGS --}}
            <section class="container-items text-light openings-column">
                <div class="top-header-ranking">
                    <h5 class="m-0 my-1 p-0">Top Openings</h5>
                </div>
                <div id="container-ops" class="gap-2 d-flex flex-column w-100 openings-list">
                    {{-- @include('layouts.top.openings') --}}
                </div>
            </section>
            {{-- ENDINGS --}}
            <section class="container-items text-light endings-column">
                <div class="top-header-ranking">
                    <h5 class="m-0 my-1 p-0">Top Endings</h5>
                </div>
                <div id="container-eds" class="gap-2 d-flex flex-column w-100 endings-list">
                    {{-- @include('layouts.top.endings') --}}
                </div>
            </section>
        </div>

        <div class="mobile-view">
            <div class="tabs" role="tablist">
                <button role="tab" aria-selected="true" aria-controls="openings-tab" id="openings-tab-btn"
                    data-tab="openings" class="tab-button active">
                    Openings
                </button>
                <!-- ... -->
                <button role="tab" aria-selected="true" aria-controls="endings-tab" id="endings-tab-btn"
                    data-tab="endings" class="tab-button">
                    Endings
                </button>
            </div>

            <div class="tab-content active" data-tab="openings">
                <div class="openings-list top-list">
                    <!-- Mismo contenido que en desktop -->
                </div>
            </div>
            <div class="tab-content" data-tab="endings">
                <div class="endings-list top-list">
                    <!-- Mismo contenido que en desktop -->
                </div>
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
            const token = localStorage.getItem('api_token');

            // Estado inicial
            let rankingType = '0'; //0 = GLOBAL, 1 = SEASONAL
            const sectionHeader = document.getElementById('section-header');
            const toggleBtn = document.getElementById('toggle-type-btn');
            const containerOps = document.getElementById('container-ops');
            const containerEds = document.getElementById('container-eds');

            fetchData(rankingType);

            async function fetchData(rankingType) {
                toggleBtn.disabled = true;

                try {
                    const response = await fetch(baseUrl + '/api/variants/ranking', {
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

                    if (rankingType == 0) {
                        sectionHeader.textContent = 'Top Openings & Endings Of All Time';
                        document.querySelector('#toggle-type-btn-span').textContent = 'Seasonal';
                    } else {
                        let season = data.currentSeason;
                        let year = data.currentYear;
                        sectionHeader.textContent = 'Top Openings & Endings ' + season.name + ' ' + year.name;
                        document.querySelector('#toggle-type-btn-span').textContent = 'Global';
                    }
                    //updateHeader(rankingType);
                    //console.log(data);

                    if (window.innerWidth <= 640) {
                        let temp1 = document.querySelector(
                            '.openings-column .openings-list').innerHTML;
                        document.querySelector('.mobile-view .openings-list').innerHTML =
                            temp1;

                        let temp2 = document.querySelector(
                            '.endings-column .endings-list').innerHTML;
                        document.querySelector('.mobile-view .endings-list').innerHTML =
                            temp2;

                    }

                } catch (error) {
                    console.error('Error:', error);
                    //contentContainer.innerHTML = `<p class="error">Error loading ${rankingType} data</p>`;
                } finally {
                    toggleBtn.disabled = false;
                }
            }

            function updateHeader(rankingType) {
                sectionHeader.textContent = rankingType === '0' ?
                    'Top Openings & Endings Of All Time' :
                    'Seasonal Ranking Openings & Endings';
            }

            function renderData(data) {
                //console.log('renderData() ');
                //console.log(data);
                containerOps.innerHTML = data.openings;
                containerEds.innerHTML = data.endings;
            }

            toggleBtn.addEventListener('click', () => {
                rankingType = rankingType === '0' ? '1' : '0';
                fetchData(rankingType);
            });

            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', () => {
                    // Remover clase active de todos los botones y contenidos
                    document.querySelectorAll('.tab-button, .tab-content').forEach(el => {
                        el.classList.remove('active');
                    });

                    // Activar el botón y contenido clickeado
                    button.classList.add('active');
                    const tabName = button.dataset.tab;
                    document.querySelector(`.tab-content[data-tab="${tabName}"]`).classList.add(
                        'active');

                    // Opcional: Si usas un framework como Laravel, puedes generar el contenido una vez
                    const openingsContent = document.querySelector(
                        '.openings-column .openings-list').innerHTML;
                    const endingsContent = document.querySelector('.endings-column .endings-list')
                        .innerHTML;

                    document.querySelector('.mobile-view .openings-list').innerHTML =
                        openingsContent;
                    document.querySelector('.mobile-view .endings-list').innerHTML = endingsContent;
                });
            });

            // 1. Configuración de breakpoints y funciones asociadas
            const breakpoints = {
                sm: 640, // Tailwind-style
                md: 768,
                lg: 1024
            };

            // 2. Elementos del DOM
            const cleanupElements = document.querySelectorAll('.top-list');
            const triggerButton = document.getElementById('openings-tab-btn');

            // 3. Estado previo para comparación
            let previousWidth = window.innerWidth;
            let currentBreakpoint = getCurrentBreakpoint(window.innerWidth);

            // 4. Función para determinar el breakpoint actual
            function getCurrentBreakpoint(width) {
                if (width < breakpoints.sm) return 'xs';
                if (width < breakpoints.md) return 'sm';
                if (width < breakpoints.lg) return 'md';
                return 'lg';
            }

            // 5. Callback principal
            function handleResize(entries) {
                const entry = entries[0];
                const newWidth = entry.contentRect.width || window.innerWidth;
                const newBreakpoint = getCurrentBreakpoint(newWidth);

                if (newBreakpoint === 'sm') {
                    triggerButton?.click();

                    currentBreakpoint = newBreakpoint;
                }
                //previousWidth = newWidth;
            }

            const resizeObserver = new ResizeObserver(handleResize);

            resizeObserver.observe(document.body);
        });
    </script>
@endsection
