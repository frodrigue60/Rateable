@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container ">
        <div class="mb-3">
            <a href="{{ route('admin.seasons.create') }}" class="btn btn-sm btn-primary">Create season</a>
        </div>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Current</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($seasons as $season)
                    <tr>
                        <th scope="row">{{ $season->id }}</th>
                        <td>{{ $season->name }}</td>
                        <td>
                            <a href="{{ route('admin.seasons.toggle', $season->id) }}"
                                class="btn btn-sm btn-{{ $season->current == true ? 'primary' : 'secondary' }}">
                                <i class="fa-solid fa-clock"></i>
                            </a>
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.seasons.edit', $season->id) }}" class="btn btn-sm btn-success"><i
                                    class="fa-solid fa-pencil"></i></a>
                            <a href="{{ route('admin.seasons.show', $season->id) }}" class="btn btn-sm btn-primary"><i
                                    class="fa-solid fa-eye"></i></a>
                            <form action="{{ route('admin.seasons.destroy', $season->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i
                                        class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
