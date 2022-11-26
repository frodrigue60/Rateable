@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        <h1>
            seasons index, current user:
        </h1>


        @if (session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card bg-dark text-light">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('admin.season.create') }}" role="button">Nuevo item</a>
            </div>

            <div class="card-body">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($seasons as $season)
                            <tr>
                                <td>{{ $season->id }}</td>
                                <td>{{ $season->name }}</td>
                                <td>
                                    @auth
                                        <a class="btn btn-danger" href="/admin/season/{{ $season->id }}/destroy"
                                            role="button">Delete</a>
                                        <a class="btn btn-success" href="/admin/season/{{ $season->id }}/edit"
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
            <div class="card-footer">
                <h4>Footer</h4>
            </div>
        </div>


    </div>

@endsection
