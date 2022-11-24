@extends ('layouts.app')

@section('title', 'All Tags')

@section('content')
    <div class="container-fluid" style="width: 90%">
        <section>
            <div class="row">
                <div class="col-12 mt-3 mb-1">
                    <h2 class="text-uppercase text-light">All Tags</h2>
                </div>
            </div>
            <div class="row">
                @foreach ($tags as $tag)
                    <div class="col-xl-3 col-sm-6 col-12 mb-4">
                        <a href="{{ route('fromtag', $tag->slug) }}" class="text-dark">
                        <div class="card ">
                            <div class="card-body">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <i class="fas fa-pencil-alt text-info fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3>{{ $tag->name }}</h3>
                                        <h4>Posts {{ $tag->count }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
