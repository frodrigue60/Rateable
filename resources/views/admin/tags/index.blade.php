@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.tags.create') }}" role="button">CREATE TAG</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    <form class="d-flex" action="{{ route('searchtag') }}" method="GET">
                        <input class="form-control me-2" type="text" name="search" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Current season</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tags as $tag)
                                <tr>
                                    <td>{{ $tag->id }}</td>
                                    <td><a href="{{ route('fromtag', $tag->slug) }}" class="no-deco">{{ $tag->name }}</a>
                                    </td>
                                    <td>{{ $tag->slug }}</td>
                                    @if (Auth::User()->isEditor() || Auth::User()->isAdmin())
                                        <td>
                                            @if ($tag->flag == '0')
                                                <a class="btn btn-secondary btn-sm" href="{{ route('admin.tags.set', $tag->id) }}" role="button"><i
                                                        class="fa fa-clock-o" aria-hidden="true"></i> Set
                                                    {{ $tag->id }}</a>
                                            @endif
                                            @if ($tag->flag == '1')
                                                <a class="btn btn-primary btn-sm" href="{{ route('admin.tags.unset', $tag->id) }}" role="button"><i
                                                        class="fa fa-check" aria-hidden="true"></i> Unset
                                                    {{ $tag->id }}</a>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        @if (Auth::User()->isEditor() || Auth::User()->isAdmin())
                                            <a class="btn btn-success btn-sm" href="/admin/tags/{{ $tag->id }}/edit"
                                                role="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                {{ $tag->id }}</a>
                                            <a class="btn btn-danger btn-sm" href="/admin/tags/{{ $tag->id }}/destroy"
                                                role="button"><i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                {{ $tag->id }}</a>
                                        @endif


                                    </td>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {!! $tags->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
