@foreach ($comments as $comment)
    @php
        $user_pp_url = '';

        if ($comment->user->image != null && Storage::disk('public')->exists($comment->user->image)) {
            $user_pp_url = Storage::url($comment->user->image);
        } else {
            $user_pp_url = asset('/storage/profile/' . 'default.jpg');
        }

    @endphp
    <div class="py-2">
        <div class="comment-container">
            <div class="profile-pic-container">
                <img class="user-profile-pic" src="{{ $user_pp_url }}" alt="User profile pic">
            </div>
            <div class="w-100 p-1">

                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <div class="user-name">
                            <a class="no-deco text-light"
                                href="{{ route('user.list', $comment->user->slug) }}">{{ $comment->user->name }}
                            </a>
                        </div>
                    </div>
                    <div class="d-flex flex-row gap-2">
                        <form action="{{ route('comments.like', $comment->id) }}" method="post">
                            @csrf
                            <button class="text-light"
                                style="background-color: transparent;border:none;">{{ $comment->likesCount }}
                                <i class="fa-regular fa-thumbs-up"></i></button>
                        </form>

                        <form action="{{ route('comments.dislike', $comment->id) }}" method="post">
                            @csrf
                            <button class="text-light"
                                style="background-color: transparent;border:none;">{{ $comment->dislikesCount }}
                                <i class="fa-regular fa-thumbs-down"></i></button>
                        </form>

                        @if (Auth::check() && Auth::user()->isAdmin())
                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="date">
                    <span>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                    </span>
                </div>
                <div class="comment-content">
                    <span>{{ $comment->content }}</span>
                </div>

            </div>

        </div>
    </div>
@endforeach
