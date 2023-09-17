@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    <h5 class="card-title">Create artist</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @method('POST')
                        @csrf
                        <div class="mb-3">
                            <label for="nameUser">Name</label>
                            <input type="text" id="nameUser" name="name" class="form-control" required="true"
                                value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="emailUser">Email</label>
                            <input type="text" id="emailUser" name="email" class="form-control" required="true"
                                value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label for="userPass">Password</label>
                            <input type="password" id="userPass" name="password" class="form-control" required="true">
                        </div>
                        <div class="mb-3">
                            <label for="userType" class="form-label">User  Type</label>
                            <select class="form-select" name="userType" id="userType">
                                <option value="">Select a role</option>
                                @foreach ($type as $item)
                                    <option {{ old('userType') == $item['value'] ? 'selected' : '' }}
                                        value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
