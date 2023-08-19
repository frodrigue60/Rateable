@extends ('layouts.app')

@section('title', 'Posts Index')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.tags.create') }}" role="button">CREATE TAG</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    <form class="d-flex" action="{{ route('search.tag') }}" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Current season</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tags as $tag)
                                <tr>
                                    <td>{{ $tag->id }}</td>
                                    <td><a href="{{ route('animes', 'tag=' . str_replace(' ', '+', $tag->name)) }}"
                                            class="no-deco">{{ $tag->name }}</a>
                                    </td>
                                    <td>{{ $tag->slug }}</td>
                                    <td>
                                        @if (Auth::User()->isEditor() || Auth::User()->isAdmin())
                                            @if ($tag->flag == '0')
                                                <a class="btn btn-secondary btn-sm"
                                                    href="{{ route('admin.tags.set', $tag->id) }}" role="button"><i
                                                        class="fa-solid fa-clock"></i></a>
                                            @endif
                                            @if ($tag->flag == '1')
                                                <a class="btn btn-primary btn-sm"
                                                    href="{{ route('admin.tags.unset', $tag->id) }}" role="button"><i
                                                        class="fa fa-check" aria-hidden="true"></i>
                                                    {{ $tag->id }}</a>
                                            @endif
                                        @else
                                            @if ($tag->flag == '0')
                                                <button disabled="disabled" class="btn btn-secondary btn-sm">
                                                    <i class="fa-solid fa-clock"></i>
                                                    {{ $tag->id }}
                                                </button>
                                            @endif
                                            @if ($tag->flag == '1')
                                                <button disabled="disabled" class="btn btn-primary btn-sm"><i
                                                        class="fa fa-check" aria-hidden="true"></i>
                                                    {{ $tag->id }}</button>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if (Auth::User()->isEditor() || Auth::User()->isAdmin())
                                            <a class="btn btn-success btn-sm" href="{{route('admin.tags.edit',$tag->id)}}"
                                                role="button"><i class="fa-solid fa-pencil"></i></a>
                                            <a class="btn btn-danger btn-sm" href="{{route('admin.tags.destroy',$tag->id)}}"
                                                role="button"><i class="fa-solid fa-trash"></i></a>
                                        @else
                                            <button disabled="disabled" class="btn btn-success btn-sm"><i
                                                    class="fa-solid fa-pencil"></i></button>
                                            <button disabled="disabled" class="btn btn-danger btn-sm"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        @endif
                                    </td>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- CARD FOOTER --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {!! $tags->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
