@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header text-light">
                    <h5>Reports Panel</h5>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    <form class="d-flex" action="" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                {{-- <th scope="col">ID</th> --}}
                                {{-- <th scope="col">Post ID</th> --}}
                                <th scope="col">Reports</th>
                                <th scope="col">Source</th>
                                <th scope="col">Status</th>
                                @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                    <th scope="col">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    {{-- <td>{{ $report->id }}</td> --}}
                                    {{-- <td>
                                        {{ $report->post_id }}
                                    </td> --}}
                                    <td>
                                        {{ $report->nums }}
                                    </td>
                                    <td>
                                        <a href="{{ $report->source }}">{{ $report->source }}</a>
                                    </td>

                                    <td>
                                        @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                            @if ($report->status == 'pending')
                                                <a class="btn btn-secondary btn-sm"
                                                    href="{{ route('admin.report.fixed', $report->id) }}"><i class="fa-solid fa-clock"></i></a>
                                            @else
                                                @if ($report->status == 'fixed')
                                                    <a class="btn btn-success btn-sm"
                                                        href="{{ route('admin.report.unfixed', $report->id) }}"><i class="fa-solid fa-check"></i></a>
                                                @endif
                                            @endif
                                        @else
                                            @if ($report->status == 'pending')
                                                <button disabled class="btn btn-warning btn-sm"><i class="fa-solid fa-clock"></i></button>
                                            @else
                                                @if ($report->status == 'fixed')
                                                    <button disabled class="btn btn-success btn-sm"><i class="fa-solid fa-check"></i></button>
                                                @endif
                                            @endif
                                        @endif

                                    </td>

                                    @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                        <td>
                                            {{-- <a href="{{ route('song.post.edit', $report->post_id) }}"
                                                class="btn btn-success btn-sm"><i class="fa-solid fa-pencil"></i> Edit Post</a> --}}
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ route('admin.report.destroy', $report->id) }}"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
