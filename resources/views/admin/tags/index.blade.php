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
        <div class="card bg-dark">
            {{-- CARD HEADER --}}
            <div class="card-header">
                <a class="btn btn-primary btn-sm" href="{{ route('admin.tags.create') }}" role="button">New item</a>
            </div>
            {{-- CARD BODY --}}
            <div class="card-body">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($tags as $tag)
                            <tr>

                                <td>{{ $tag->id }}</td>
                                <td><a href="{{ route('fromtag', $tag->slug) }}" class="no-deco">{{ $tag->name }}</a></td>
                                <td>{{ $tag->slug }}</td>
                                <td>
                                    @auth
                                        <a class="btn btn-danger btn-sm" href="/admin/tags/{{ $tag->id }}/destroy"
                                            role="button">Delete</a>
                                        <a class="btn btn-success btn-sm" href="/admin/tags/{{ $tag->id }}/edit"
                                            role="button">Edit</a>
                                    @endauth

                                    @guest
                                        <a class="btn btn-danger disabled" href="#" role="button">Delete</a>
                                        <a class="btn btn-success disabled" href="#" role="button">Edit</a>

                                    @endguest
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
@endsection
