{{-- SORT --}}
<div class="">
    <label for="select-sort" class="text-light">Sort</label>
    <select class="form-select" aria-label="Default select example" id="select-sort" name="sort">
        <option value="">Any</option>
        @foreach ($sortMethods as $item)
            <option value="{{ $item['value'] }}">
                {{ $item['name'] }}
            </option>
        @endforeach
    </select>
</div>