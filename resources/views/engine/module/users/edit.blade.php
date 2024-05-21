@extends('layouts.app')
@section('content')
{{-- add roles --}}
<section class="section">
    <div class="section-header">
        <h1>Users Management</h1>
    </div>
{{-- form --}}
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Users</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.update', $user->id) }}" novalidate  class="needs-validation" method="POST">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @csrf
                        <div class="form-group">
                            <label for="name">Role</label>
                            <select name="role" id="role" class="form-control select2" required>
                                <option value="">Select Role</option>
                                @foreach ($role as $roles)
                                    <option value="{{ $roles->id }}" {{ (isset($rolerelation->role_id) ? $rolerelation->role_id : 0 ) == $roles->id ? 'selected' : '' }}>{{ $roles->name }}</option>
                                @endforeach
                            </select>
                            {{-- invalid --}}
                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Username</label>
                            <input type="text" name="name" class="form-control"  value="{{ $user->name }}" id="name" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" id="email" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control"  value="{{ old('password') }}" id="password" placeholder="Password">
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Password Confirmation</label>
                            <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" id="password_confirmation" placeholder="Password Confirmation">
                        </div>
                        
                        {{-- status --}}
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control select2">
                                <option value="">Select Status</option>
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            {{-- invalid --}}
                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection