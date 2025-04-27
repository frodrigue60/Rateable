@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card  ">
                <div class="card-header">
                    <h5 class="card-title">Edit variant</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('admin.variants.update',[$songVariant->id])}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- <div class="mb-3">
                            <label for="formFileBanner" class="form-label">Upload Video File</label>
                            <input class="form-control" type="file" id="formFileBanner" name="video">
                        </div> --}}
                        <div class="mb-3">
                            <label for="version" class="form-label">Theme Version Number</label>
                            <input type="number" class="form-control" placeholder="Theme Version 1,2,3..." id="version"
                                name="version_number" value="{{ $songVariant->version_number }}">
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
