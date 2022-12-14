@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <h1 class="text-light">Create current season</h1>

        <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{ route('admin.season.store') }}"
            enctype="multipart/form-data">
            @csrf
            <select class="chzn-select" name="season" id="season" style="width:50%;">
                @foreach ($seasons as $season)
                    <option value="{{ $season->name }}">{{ $season->name }}</option>
                @endforeach
            </select>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">

        <script type="text/javascript">
            $(function() {
                $(".chzn-select").chosen();
            });
        </script>

    </div>


@endsection
