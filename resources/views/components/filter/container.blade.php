<form class="d-flex gap-3 flex-wrap" id="form-filter" data-api-url="{{ $apiEndpoint }}" method="{{ $method }}">
    <!-- Campos dinámicos -->
    @isset($fields)
        @foreach ($fields as $field)
            @includeIf("components.filter.fields.{$field}")
        @endforeach
    @else
        @include('components.filter.fields.name')
        @include('components.filter.fields.type')
        @include('components.filter.fields.year')
        @include('components.filter.fields.season')
        @include('components.filter.fields.sort')
    @endisset

    {{-- @include('components.filter.submit') --}}
</form>
