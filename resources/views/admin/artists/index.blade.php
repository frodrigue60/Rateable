@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.artist.create') }}" role="button">CREATE
                        ARTIST</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    <form class="d-flex" action="{{route('admin.artist.search')}}" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Name JP</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($artists as $artist)
                                <tr>

                                    <td>{{ $artist->id }}</td>
                                    <td><a href="{{ route('artist.show', [$artist->id,$artist->name_slug]) }}"
                                            class="no-deco">{{ $artist->name }}</a>
                                    </td>
                                    <td>
                                        @if (isset($artist->name_jp))
                                            {{ $artist->name_jp }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if (Auth::User()->isAdmin())
                                            <a class="btn btn-success btn-sm" href="/admin/artist/{{ $artist->id }}/edit"
                                                role="button"><i class="fa-solid fa-pencil"></i></a>
                                            <a class="btn btn-danger btn-sm"
                                                href="/admin/artist/{{ $artist->id }}/destroy" role="button"><i
                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                        @else
                                            <button disabled="disabled" class="btn btn-success btn-sm"><i class="fa-solid fa-pencil"></i></button>
                                            <button disabled="disabled" class="btn btn-danger btn-sm"><i class="fa fa-trash"
                                                    aria-hidden="true"></i></button>
                                        @endif
                                    </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $artists->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
