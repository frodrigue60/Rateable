@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        @include('admin.videos.breadcumb')
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" href="{{route('admin.videos.create',$song->id)}}" role="button">ADD VIDEO</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                {{-- <th scope="col">First</th> --}}
                                <th scope="col">Type</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($song->videos)
                                @foreach ($song->videos as $video)
                                    <tr>
                                        <th scope="row">{{$video->id}}</th>
                                        {{-- <td>Mark</td> --}}
                                        <td>{{$video->type}}</td>
                                        <td>
                                            <a name="" id="" class="btn btn-sm btn-success" href="{{route('admin.videos.edit',$video->id)}}" role="button">Edit</a>
                                            <a name="" id="" class="btn btn-sm btn-primary" href="{{route('admin.videos.show',$video->id)}}" role="button">Show</a>
                                            <a name="" id="" class="btn btn-sm btn-danger" href="{{route('admin.videos.destroy',$video->id)}}" role="button">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset

                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{-- {!! $tags->links() !!} --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
