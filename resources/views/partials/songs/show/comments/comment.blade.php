@php
    $user_pp_url = '';

    if ($comment->user->image != null && Storage::disk('public')->exists($comment->user->image)) {
        $user_pp_url = Storage::url($comment->user->image);
    } else {
        $user_pp_url = asset('/storage/profile/' . 'default.jpg');
    }

@endphp
<div class="comment d-flex flex-column" {{-- style="border: solid 1px red;" id="comment-container-{{ $comment->id }}" --}} data-id="{{ $comment->id }}">
    <div class="d-flex mb-3 bg-dark-subtle rounded-1 p-2 gap-2"
        id="comment-{{ $comment->id }}">
        <div class="">
            <!-- IMAGE CONTAINER -->
            <div class="ratio-1x1 rounded-circle overflow-hidden" style="width: 50px">
                <img class="w-100" src="{{ $user_pp_url }}" alt="User profile pic">
            </div>

        </div>
        <div class="w-100">
            <div class="d-flex flex-column">
                <div class="d-flex flex-row justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex gap-2 align-items-center">
                            <a class="text-decoration-none"
                                href="{{ route('user.list', $comment->user->slug) }}">{{ $comment->user->name }}
                            </a>
                            <!-- BADGES -->
                            <div>
                                <i class="fa-solid fa-crown"></i> <i class="fa-solid fa-code"></i>
                            </div>
                            <!-- DATE -->
                            <span
                                class=" text-secondary">{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }} {{ '#'.$comment->id }}
                            </span>
                        </div>
                    </div>
                    @auth
                        <div class="dropdown">
                            <button class="btn bg-transparent" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="dropdown-item"><i class="fa-solid fa-pencil"></i> Edit</button>
                                </li>
                                {{-- <li><a class="dropdown-item" href="#">Delete</a></li> --}}

                                @if ($comment->user_id == Auth::user()->id || Auth::user()->isAdmin())
                                    {{--  <button type="button" class="btn btn-danger btn-del-comment"
                            data-comment-id="{{ $comment->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button> --}}
                                    <li>
                                        <button type="button" class="dropdown-item btn-del-comment"
                                            data-comment-id="{{ $comment->id }}">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endauth
                </div>
                <div class="d-flex flex-column">
                    <p>{{ $comment->content }}</p>
                    <div class="d-flex gap-2">
                        <button class="btn-like-comment btn btn-sm " data-comment-id="{{ $comment->id }}">
                            <i class="fa-regular fa-thumbs-up"></i> <span
                                id="likes-span-{{ $comment->id }}">{{ $comment->likesCount }}</span></button>

                        <button class="btn-dislike-comment btn btn-sm " data-comment-id="{{ $comment->id }}">
                            <i class="fa-regular fa-thumbs-down"></i> <span
                                id="dislikes-span-{{ $comment->id }}">{{ $comment->dislikesCount }}</span></button>
                        <button class="btn-reply-comment btn btn-sm " data-comment-id="{{ $comment->id }}">
                            <i class="fa-solid fa-reply"></i> Reply
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Formulario oculto (se muestra al hacer clic) -->
    <div id="reply-form-{{ $comment->id }}" style="display: none;" class="mb-3">
        <form action="" method="POST" class="form-reply d-flex gap-2" data-parent-comment-id="{{ $comment->id }}" id="form-reply-{{ $comment->id }}">
            <div class="form-floating w-100">
                <textarea class="form-control" name="content" {{-- required --}} id="floatingTextarea" rows="2"></textarea>
                <label for="floatingTextarea">Comments</label>
            </div>

            <div class="d-block">
                <button class="btn btn-primary d-block" type="submit" id="submit-comment-btn"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
        </form>
    </div>

    <!-- Mostrar respuestas anidadas (recursivo) -->
    <div class="replies" style="margin-left: 30px;" id="replies-{{ $comment->id }}">
        @if ($comment->replies->count())
            @include('partials.songs.show.comments.comments', ['comments' => $comment->replies->sortByDesc('created_at')])
            {{-- @foreach ($comment->replies as $item)
                <p>{{ $item->content }}</p>
            @endforeach --}}
        @endif
    </div>
</div>
