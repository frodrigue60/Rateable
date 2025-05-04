@guest
    <div class="d-flex justify-content-center my-4">
        <h3>Please <a class="" href="{{ route('login') }}">login</a> or <a class=""
                href="{{ route('register') }}">register</a> for comment</h3>
    </div>
@endguest
@auth
    <div class="">
        <div>
            <form id="commnent-form" action="" method="post" class="d-flex gap-2">
                @csrf
                <input type="hidden" id="song_id" name="song_id" value="{{ $song->id }}">
                <div class="form-floating w-100">
                    <textarea id="comment-content" name="content" class="form-control" placeholder="Write us your comment" required rows="2"></textarea>
                    <label for="comment-content">Write us your comment</label>
                </div>
                <div class="d-block">
                    <button class="btn btn-primary d-block" type="submit" id="submit-comment-btn"><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>
@endauth
