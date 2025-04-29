@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card ">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.users.create') }}" role="button">CREATE
                        USER</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    <form class="d-flex" action="{{ route('admin.users.search') }}" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Type</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><a href="{{ route('user.list', $item->slug) }}">{{ $item->name }}</a></td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td class="d-flex gap-2">
                                        @if (Auth::User()->isAdmin())
                                            <a class="btn btn-success btn-sm"
                                                href="{{ route('admin.users.edit', $item->id) }}" role="button"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                            <form action="{{ route('admin.users.destroy', $item->id) }}" method="post" class="d-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
