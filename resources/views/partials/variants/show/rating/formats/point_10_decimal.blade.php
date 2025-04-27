<form action="" method="post" id="rating-form" class="d-flex flex-column w-100 p-4 text-center"
    data-song="{{ $song->id }}" data-scoreformat="{{ Auth::user()->score_format }}">

    <div class="input-group">
        <div class="mb-3 w-100">
            <label for="scoreInput" class="form-label">You score</label>
            <input type="number" class="form-control" id="scoreInput" placeholder="Max 10 with decimal" name="score"
                max="10" min="0" step=".1" value="{{ $format_rating }}">
        </div>
        <div class="w-100">
            <button type="submit" class="btn btn-primary w-100">
                Button
            </button>
        </div>
    </div>
</form>
