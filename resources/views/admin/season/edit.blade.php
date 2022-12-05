@extends ('layouts.app')

@section('title', 'Tags Edit/Update')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-footer">
                        Edit the Current Season
                    </div>
                    <div class="card-body">
                        <form method="post"
                            action="{{ route('admin.season.update', $season->id) }}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <label for="season">Select Season</label>
                            <select class="chzn-select" name="season" id="season" style="width:50%;">
                                @foreach ($seasons as $season)
                                    <option selected value="{{ $season->name }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                            <br>
                            <br>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="card-footer">

                    </div>

                </div>
            </div>

        </div>

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
