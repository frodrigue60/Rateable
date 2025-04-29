@guest
    <div class="d-flex justify-content-center my-4">
        <h3>Please <a class="" href="{{ route('login') }}">login</a> or <a class=""
                href="{{ route('register') }}">register</a> for comment</h3>
    </div>
@endguest
@auth
    <div class="">
        <div>
            <form id="commnent-form" action="" method="post" class="d-flex flex-column gap-2">
                @csrf
                <input type="hidden" id="song_id" name="song_id" value="{{ $song->id }}">
                <div class="form-floating">
                    <textarea id="comment-content" name="content" class="form-control" placeholder="Write us your comment" required></textarea>
                    <label for="comment-content">Write us your comment</label>
                </div>
                <button class="btn btn-primary" type="submit" id="submit-comment-btn">Submit</button>
            </form>
        </div>
    </div>
@endauth
