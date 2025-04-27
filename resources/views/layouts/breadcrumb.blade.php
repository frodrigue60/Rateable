<div class="container">
    <nav aria-label="breadcrumb" class="">
        <ol class="breadcrumb">
            @isset($breadcrumb)
                @foreach ($breadcrumb as $item)
                    @if (!$loop->last)
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="{{ $item['url'] }}" class="">{{ $item['name'] }}</a>
                        </li>
                    @else
                        <li class="breadcrumb-item active " aria-current="page">
                            {{ $item['name'] }}
                        </li>
                    @endif
                @endforeach
            @endisset
        </ol>
    </nav>
</div>
