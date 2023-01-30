<form action="{{ route('favorites') }}" method="get">
    {{-- FILTER BY --}}
    <section class="searchItem">
        <span class="text-light">Filter By</span>
        <select id="chzn-filterBy" name="filterBy" class="form-select" aria-label="Default select example">
            <option value="">Select a filter method</option>
            @foreach ($filters as $item)
                <option value="{{ $item['value'] }}"
                    {{ $requested->filterBy == $item['value'] ? 'selected' : '' }}>{{ $item['name'] }}
                </option>
            @endforeach
        </select>
    </section>
    {{-- TYPE --}}
    <section class="searchItem">
        <span class="text-light">Select Type</span>
        <select id="chzn-type" name="type" class="form-select" aria-label="Default select example">
            <option value="">Select the type</option>
            @foreach ($types as $item)
                <option value="{{ $item['value'] }}"
                    {{ $requested->type == $item['value'] ? 'selected' : '' }}>{{ $item['name'] }}
                </option>
            @endforeach
        </select>
    </section>
    {{-- TAGS --}}
    <section class="searchItem">
        <span class="text-light">Select Season</span>
        <select id="chzn-tag" name='tag' class="form-select" aria-label="Default select example">
            <option value="">Select the season</option>
            @foreach ($tags as $tag)
                <option value="{{ $tag->name }}"
                    {{ $requested->tag == $tag->name ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>
    </section>
    {{-- SORT --}}
    <section class="searchItem">
        <span class="text-light">Sort By</span>
        <select id="chzn-sort" name="sort" class="form-select" aria-label="Default select example">
            <option value="">Select order method</option>
            @foreach ($sortMethods as $item)
                <option value="{{ $item['value'] }}"
                    {{ $requested->sort == $item['value'] ? 'selected' : '' }}>{{ $item['name'] }}
                </option>
            @endforeach
        </select>
    </section>
    {{-- LETTER --}}
    <section class="searchItem">
        <span class="text-light">Filter by Letter</span>
        <select id="chzn-char" name="char" class="form-select" aria-label="Default select example">
            <option value="">Select a letter</option>
            @foreach ($characters as $item)
                <option value="{{ $item }}" class="text-uppercase"
                    {{ $requested->char == $item ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
    </section>
    <br>
    <div class="d-flex justify-content-center">
        <button class="btn btn-primary w-100" type="submit">Do it</button>
    </div>
</form>