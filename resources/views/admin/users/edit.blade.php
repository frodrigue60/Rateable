@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card  ">
                <div class="card-header">
                    <h5 class="card-title">Edit user: {{ $user->name }}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.users.update', $user->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="mb-3">
                            <label for="nameUser" class="form-label">Name</label>
                            <input type="text" id="nameUser" name="name" class="form-control" required="true"
                                value="{{ $user->name }}">
                        </div>

                        <div class="mb-3">
                            <label for="emailUser" class="form-label">Email</label>
                            <input type="text" id="emailUser" name="email" class="form-control" required="true"
                                value="{{ $user->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="userPass" class="form-label">Password</label>
                            <input type="text" id="userPass" name="password" class="form-control"
                                placeholder="Type a new password">
                        </div>
                        <div class="mb-3">
                            <label for="userType" class="form-label">User Type</label>
                            <select class="form-select" name="userType" id="userType">
                                <option value="">Select a user type</option>
                                @foreach ($type as $item)
                                    <option value="{{ $item['value'] }}"
                                        {{ $user->type == $item['value'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
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
