@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.season.create') }}" role="button">CREATE
                        SEASON</a>
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
                                        <a class="btn btn-sm btn-success" href="/admin/season/{{ $season->id }}/edit"
                                            role="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                            {{ $season->id }}</a>
                                        <a class="btn btn-sm btn-danger" href="/admin/season/{{ $season->id }}/destroy"
                                            role="button"><i class="fa fa-trash" aria-hidden="true"></i> Delete
                                            {{ $season->id }}</a>
                                    </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
