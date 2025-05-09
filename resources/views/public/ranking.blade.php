@extends('layouts.app')
@section('meta')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection
@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-between">
            <h2 class="text-center " id="section-header"></h2>
            <div>
                <button type="button" class="btn btn-primary" id="toggle-type-btn" disabled>
                    <i class="fa-solid fa-rotate"></i> <span id="toggle-type-btn-span">Seasonal</span>
                </button>
            </div>
        </div>
        <div class="d-flex gap-2 d-md-none">
            <div class="col-6 py-2">
                <button type="button" class="btn btn-primary w-100 tab-btn" data-tab="openings">
                    Openings
                </button>

            </div>
            <div class="col-6 py-2">
                <button type="button" class="btn btn-primary w-100 tab-btn" data-tab="endings">
                    Endings
                </button>

            </div>
        </div>
        <!-- DEKTOP -->
        <div class="row">
            <!--OPENINGS-->
            <section class="col-12 col-md-6 " id="openings-section">
                <h5 class="">Top Openings</h5>
                <!-- openings container -->
                <div id="container-ops" class="mb-3 gap-2 d-flex flex-column">
                    {{-- @include('layouts.top.openings') --}}
                </div>
                <!-- loader -->
                <div class="d-flex justify-content-center my-4" id="loader-ops">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <!-- load more button -->
                <button type="button" class="btn btn-primary w-100 load-more-op" id="load-more-op" disabled>
                    Load More
                </button>
            </section>

            <!--ENDINGS-->
            <section class="col-12 col-md-6 d-none d-md-block" id="endings-section">
                <h5 class="">Top Endings</h5>
                <!-- endings container -->
                <div id="container-eds" class="mb-3 gap-2 d-flex flex-column" hidden>
                    {{-- @include('layouts.top.endings') --}}
                </div>
                <!-- loader -->
                <div class="d-flex justify-content-center my-4" id="loader-eds">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <!-- load more button -->
                <button type="button" class="btn btn-primary w-100 load-more-ed" id="load-more-ed" disabled>
                    Load More
                </button>
            </section>
        </div>
    </div>
@endsection
@section('script')
    @vite(['resources/js/ranking_songs.js'])
@endsection
