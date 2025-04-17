{{-- TYPE --}}
<div class="">
    <label for="select-type" class="text-light">Type</label>
    <select class="form-select" aria-label="Default select example" id="select-type" name="type">
        <option value="" selected>Any</option>
        @foreach ($types as $item)
            <option value="{{ $item['value'] }}">
                {{ $item['name'] }}
            </option>
        @endforeach
    </select>
</div>