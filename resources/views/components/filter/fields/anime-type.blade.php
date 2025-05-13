{{-- TYPE --}}
<div class="">
    <label for="select-format" class="">Format</label>
    <select class="form-select" aria-label="Default select example" id="select-format" name="format_id">
        <option value="" selected>Any</option>
        @foreach ($formats as $item)
            <option value="{{ $item->id }}">
                {{ $item->name }}
            </option>
        @endforeach
    </select>
</div>
