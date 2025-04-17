{{-- YEAR --}}
<div class="">
    <label class="text-light" for="select-year">Year</label>
    <select class="form-select" aria-label="Default select example" name="year_id" id="select-year">
        <option selected value="">Any</option>
        @foreach ($years as $year)
            <option value="{{ $year->id }}">{{ $year->name }}
            </option>
        @endforeach
    </select>
</div>