@extends ('layouts.app')

@section('title', 'Tags Create')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card bg-dark text-light">
                <div class="card-header">
                    Create artist
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.users.update', $user->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="nameUser">Name</label>
                            <input type="text" id="nameUser" name="name" class="form-control" required="true" value="{{$user->name}}">

                        </div>
                        <br>
                        <div class="form-group">
                            <label for="emailUser">Email</label>
                            <input type="text" id="emailUser" name="email" class="form-control" required="true" value="{{$user->email}}">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="userPass">Password</label>
                            <input type="text" id="userPass" name="password" class="form-control"  placeholder="Type a new password">
                        </div>
                        <br>
                        <label for="userType" class="form-label">User Type</label>
                        <select class="form-select" name="userType" id="userType">
                            <option value="">Select a user type</option>
                            @foreach ($type as $item)
                                <option value="{{ $item['value'] }}" {{ $user->type == $item['value'] ? 'selected' : '' }}>
                                    {{ $item['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <br>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
