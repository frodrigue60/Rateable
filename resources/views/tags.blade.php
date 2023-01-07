@extends ('layouts.app')
@section('meta')
    @if (Request::is('tags'))
        <title>All Seasons Openings & Endings</title>
        <meta title="All Seasons Openings & Endings">
    @endif
@endsection
@section('content')
    <div class="container">
        <div class="col-12 text-center">
            <h1 class="text-uppercase text-light">All Seasons</h1>
        </div>
        <div class="row justify-content-center">
            @foreach ($tags as $tag)
                <div class="col-lg-4 col-md-6 col-sm-6 mb-3">
                    <a href="{{ route('fromtag', $tag->slug) }}" class="text-dark no-deco">
                        <div class="card" style="background-color: #95c4e5">
                            <div class="card-body">
                                <div class="d-flex justify-content-end px-md-1">
                                    <div class="text-end">
                                        <h3><strong>{{ $tag->name }}</strong></h3>
                                        <h4>Posts: {{ $tag->count }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
