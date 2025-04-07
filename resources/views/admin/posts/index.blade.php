@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card bg-dark">
                {{-- CARD HEADER --}}
                <div class="card-header">
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.posts.create') }}">CREATE POST</a>
                    <a class="btn btn-sm btn-primary" href="{{ route('posts.wipe') }}">WIPE ALL POSTS</a>
                </div>
                {{-- CARD BODY --}}
                <div class="card-body">
                    {{-- search form --}}
                    @if (Auth::user()->isAdmin())
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        API OPTIONS
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body bg-dark">
                                        <form class="d-flex gap-1" action="{{ route('admin.search.animes') }}"
                                            method="POST">
                                            @csrf
                                            <input class="form-control" type="text" name="q" placeholder="Search"
                                                required />
                                            <select class="form-select" aria-label="Default select example" name="types">
                                                <option selected value="TV">TV</option>
                                                <option value="TV_SHORT">TV SHORT</option>
                                                <option value="MOVIE">MOVIE</option>
                                                <option value="SPECIAL">SPECIAL</option>
                                                <option value="OVA">OVA</option>
                                                <option value="ONA">ONA</option>
                                            </select>
                                            <button class="btn btn-outline-success" type="submit">Search</button>
                                        </form>
                                        <br>
                                        <form class="d-flex gap-1" action="{{ route('get.seasonal.animes') }}"
                                            method="POST">
                                            @csrf
                                            <input class="form-control" type="number" name="year" placeholder="YEAR"
                                                required />
                                            <select class="form-select" aria-label="Default select example" name="season">
                                                <option selected value="">Select a season</option>
                                                <option value="WINTER">WINTER</option>
                                                <option value="SPRING">SPRING</option>
                                                <option value="SUMMER">SUMMER</option>
                                                <option value="FALL">FALL</option>
                                            </select>
                                            <select class="form-select" aria-label="Default select example" name="types">
                                                <option selected value="TV">TV</option>
                                                <option value="TV_SHORT">TV SHORT</option>
                                                <option value="MOVIE">MOVIE</option>
                                                <option value="SPECIAL">SPECIAL</option>
                                                <option value="OVA">OVA</option>
                                                <option value="ONA">ONA</option>
                                            </select>
                                            <button class="btn btn-outline-success" type="submit">Create loot</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    @endif
                    <form class="d-flex" action="{{ route('admin.posts.search') }}" method="GET">
                        <input class="form-control me-2" type="text" name="q" placeholder="Search" required />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Themes</th>
                                <th scope="col">Status</th>
                                @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                    <th scope="col">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <th scope="row">{{ $post->id }}</th>
                                    <td>
                                        <a href="{{ $post->url }}" class="no-deco">{{ $post->title }}</a>
                                    </td>
                                    <td>
                                        @isset($post->season->id)
                                            <a href="{{ route('seasons.show', $post->season->id) }}" target="_blank"
                                                rel="noopener noreferrer">{{ $post->season->name }}</a>
                                        @endisset
                                        @isset($post->year->id)
                                            <a href="{{ route('years.show', $post->year->id) }}" target="_blank"
                                                rel="noopener noreferrer">{{ $post->year->name }}</a>
                                        @endisset

                                    </td>
                                    <td>
                                        {{ $post->songs->count() }}
                                    </td>


                                    @if (Auth::User()->isCreator())
                                        <td>
                                            @if ($post->status == null)
                                                <button disabled class="btn btn-sm btn-secondary">N/A</button>
                                            @endif
                                            @if ($post->status == false)
                                                <button disabled class="btn btn-warning btn-sm"><i
                                                        class="fa-solid fa-clock"></i></button>
                                            @endif
                                            @if ($post->status == true)
                                                <button disabled class="btn btn-primary btn-sm"><i
                                                        class="fa-solid fa-clock"></i></button>
                                            @endif
                                        </td>
                                    @endif
                                    @if (Auth::User()->isAdmin() || Auth::User()->isEditor())
                                        <td>
                                            @if ($post->status == true)
                                                <form action="{{ route('admin.posts.unapprove', $post->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <button class="btn btn-primary btn-sm"> <i
                                                            class="fa-solid fa-toggle-on"></i></button>
                                                </form>
                                            @else
                                                @if ($post->status == false)
                                                    <form action="{{ route('admin.posts.approve', $post->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <button class="btn btn-warning btn-sm"><i
                                                                class="fa-solid fa-toggle-off"></i></button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.posts.edit', $post->id) }}"
                                                class="btn btn-success btn-sm"><i class="fa-solid fa-pencil"></i></a>
                                            <a href="{{ route('admin.posts.destroy', $post->id) }}"
                                                class="btn btn-danger btn-sm"><i class="fa fa-trash"
                                                    aria-hidden="true"></i></a>
                                            <a class="btn btn-sm btn-primary"
                                                href="{{ route('admin.posts.show', $post->id) }}"><i
                                                    class="fa-solid fa-eye"></i></a>
                                            <a class="btn btn-sm btn-primary"
                                                href="{{ route('posts.songs', $post->id) }}"><i
                                                    class="fa-solid fa-list"></i></a>
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
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
