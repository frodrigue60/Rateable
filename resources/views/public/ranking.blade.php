@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection
@section('content')
    <div class="container" id="scroll-container">
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
            <h2 class="text-center " id="section-header"></h2>
            <div>
                <button type="button" class="btn btn-primary" id="toggle-type-btn">
                    <i class="fa-solid fa-rotate"></i> <span id="toggle-type-btn-span">Seasonal</span>
                </button>
            </div>
        </div>
        <div class="container-top desktop-view">
            <!--OPENINGS-->
            <section class="container-items  openings-column">
                <div class="top-header-ranking">
                    <h5 class="m-0 my-1 p-0">Top Openings</h5>
                </div>
                <div id="container-ops" class="gap-2 d-flex flex-column w-100 openings-list h-100">
                    {{-- @include('layouts.top.openings') --}}

                </div>
                <div class="d-flex justify-content-center" id="loader-ops">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </section>
            <!--ENDINGS-->
            <section class="container-items  endings-column">
                <div class="top-header-ranking">
                    <h5 class="m-0 my-1 p-0">Top Endings</h5>
                </div>
                <div id="container-eds" class="gap-2 d-flex flex-column w-100 endings-list h-100" hidden>
                    {{-- @include('layouts.top.endings') --}}

                </div>
                <div class="d-flex justify-content-center" id="loader-eds">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
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
    @vite(['resources/js/ranking_songs.js'])
@endsection
