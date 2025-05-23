@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mb-3">
            <a href="{{ route('admin.years.create') }}" class="btn btn-sm btn-primary">Add year</a>
        </div>
        <table class="table ">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Current</th>
                    <th scope="col">Actions</th>
                    {{-- <th scope="col">Handle</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($years as $year)
                    <tr>
                        <th scope="row">{{ $year->id }}</th>
                        <td>{{ $year->name }}</td>
                        <td>
                            <a href="{{ route('admin.years.toggle', $year->id) }}" class="btn btn-sm btn-{{ $year->current == true ? 'primary' : 'secondary' }}">
                                <i class="fa-solid fa-clock"></i>
                            </a>
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.years.edit', $year->id) }}" class="btn btn-sm btn-success">Edit</a>
                            <form action="{{ route('admin.years.destroy', $year->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                        {{-- <td>@mdo</td> --}}
                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>
@endsection
