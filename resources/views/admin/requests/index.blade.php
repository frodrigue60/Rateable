@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card ">
                {{-- CARD HEADER --}}
                <div class="card-header ">
                    <h5 class="card-title">Requests Panel</h5>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    <form class="d-flex" action="" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table ">
                        <thead>
                            <tr>
                                <th class="col" scope="col">ID</th>
                                <th scope="col">Content</th>
                                @if (Auth::User()->isStaff())
                                    <th class="col" scope="col">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td>
                                        {{ $request->id }}
                                    </td>
                                    <td>
                                        {{ $request->content }}
                                    </td>
                                    <td>
                                        @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                            @if (Auth::User()->isStaff())
                                                @if ($request->status == 'pending')
                                                    <a class="btn btn-secondary btn-sm"
                                                        href="{{ route('admin.requests.show', $request->id) }}">Show
                                                    </a>
                                                @else
                                                    @if ($request->status == 'attended')
                                                        <button class="btn btn-success btn-sm">OK</button>
                                                    @endif
                                                @endif
                                            @endif
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ route('admin.requests.destroy', $request->id) }}"><i
                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
