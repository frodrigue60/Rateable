{{-- SEASON --}}
<div class="">
    <label class="" for="select-season">Season</label>
    <select class="form-select" aria-label="Default select example" name="season_id" id="select-season">
        <option selected value="">Any</option>
        @foreach ($seasons as $season)
            <option value="{{ $season->id }}">{{ $season->name }}
            </option>
        @endforeach
    </select>
</div>
