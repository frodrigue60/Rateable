@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card text-light m-0 bg-dark">
            <div class="card-header">
                <a href="{{ route('admin.songs.variants.add', $song->id) }}" class="btn btn-sm btn-primary">Create Variant</a>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Variant ID</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Video ID</th>

                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($song_variants)
                                @foreach ($song_variants as $variant)
                                    <tr>
                                        <td>{{ $variant->id }}</td>
                                        <td>{{ $variant->slug }}</td>
                                        <td>
                                            @if ($variant->video)
                                                <a class="btn-sm btn btn-primary"
                                                    href="{{ route('admin.variants.videos', $variant->id) }}">Video
                                                    {{ $variant->id }}</a>
                                            @else
                                                <button class="btn-sm btn btn-primary" href="" disabled>Video</button>
                                            @endif
                                        </td>

                                        <td class="d-flex gap-1">
                                            <a class="btn-sm btn btn-success"
                                                href="{{ route('admin.variants.edit', $variant->id) }}"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                            <form class="d-flex" action="{{ route('admin.variants.destroy', $variant->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn btn-danger"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </form>

                                            <a class="btn btn-sm btn-primary"
                                                href="{{ route('admin.variants.videos.add', $variant->id) }}"><i
                                                    class="fa-solid fa-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endsection
