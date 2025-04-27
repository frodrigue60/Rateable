@extends('layouts.app')

@section('content')
    <div class="container ">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    {{ $report->songVariant->song->post->title }} {{ $report->songVariant->song->slug }} {{ $report->songVariant->slug }}
                </h5>
            </div>
            <div class="card-body">
                <span>Problem title:</span>
                <p class="card-text">{{ $report->title }}</p>

                <span>Problem description:</span>
                <p class="card-text">{{ $report->content }}</p>
            </div>
            <div class="card-footer d-flex gap-2">
                @if ($report->status == 'pending')
                    <a href="{{ route('admin.reports.toggle', $report->id) }}" class="btn btn-sm btn-primary w-100">Mark as
                        Read</a>
                @else
                    <a href="{{ route('admin.reports.toggle', $report->id) }}" class="btn btn-sm btn-success w-100">Mark as
                        Unread</a>
                @endif
                <form action="{{ route('admin.reports.destroy', $report->id) }}" method="post" class="d-flex w-100">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger w-100">Delete</button>
                </form>
            </div>
        </div>

    </div>
@endsection
