@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Type</th>
                        <th scope="col">Content</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $video = $song_variant->video;
                        if ($video->video_src) {
                            $content = $video->video_src;
                        } else {
                            $content = $video->embed_code;
                        }
                    @endphp
                    <tr>
                        <td scope="row">{{ $video->id }}</td>
                        <td>{{ $video->type }}</td>
                        <td>{{ $content }}</td>
                        <td>
                            <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-sm btn-success">Edit</a>
                            <a href="{{ route('admin.videos.show', $video->id) }}" class="btn btn-sm btn-primary">Show</a>
                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    @endsection
