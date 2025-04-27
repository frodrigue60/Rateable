@guest
    <div class="d-flex justify-content-center comment-form ">
        <h3>Please <a class="" href="{{ route('login') }}">login</a> or <a class=""
                href="{{ route('register') }}">register</a> for comment</h3>
    </div>
@endguest
@auth
    <div class="py-2">
        <div>
            <form id="commnent-form" action="" method="post" class="d-flex flex-column gap-2">
                @csrf
                <input type="hidden" id="song_id" name="song_id" value="{{ $song->id }}">
                <textarea id="comment-content" name="content" class="form-control" id="exampleFormControlTextarea1" rows="2"
                    placeholder="Comment ... (optional)" maxlength="255"></textarea>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
@endauth
