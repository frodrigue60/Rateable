@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header text-light">
                    <h5 class="card-title">Reports Panel</h5>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    {{-- <form class="d-flex" action="" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form> --}}
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                {{-- <th scope="col">Song ID</th> --}}
                                <th scope="col">ID</th>
                                <th scope="col">Source</th>
                                <th scope="col">UserID</th>
                                @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                    <th scope="col">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td>
                                        {{ $report->id }}
                                    </td>
                                    <td>
                                        <a href="{{ $report->source }}">{{ $report->songVariant->song->post->title }}</a>
                                    </td>

                                    <td>
                                        {{ $report->user_id }}

                                    </td>

                                    @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                        <td class="d-flex gap-2">
                                            @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                                @if ($report->status == 'pending')
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('admin.reports.toggle', $report->id) }}"><i
                                                            class="fa-solid fa-clock"></i></a>
                                                @else
                                                    @if ($report->status == 'fixed')
                                                        <a class="btn btn-success btn-sm"
                                                            href="{{ route('admin.reports.toggle', $report->id) }}"><i
                                                                class="fa-solid fa-check"></i></a>
                                                    @endif
                                                @endif
                                            @else
                                                @if ($report->status == 'pending')
                                                    <button disabled class="btn btn-warning btn-sm"><i
                                                            class="fa-solid fa-clock"></i></button>
                                                @else
                                                    @if ($report->status == 'fixed')
                                                        <button disabled class="btn btn-success btn-sm"><i
                                                                class="fa-solid fa-check"></i></button>
                                                    @endif
                                                @endif
                                            @endif
                                            <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-primary"><i
                                                class="fa-solid fa-eye"></i></a>

                                            <form class="d-flex" action="{{ route('admin.reports.destroy', $report->id) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </form>
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
