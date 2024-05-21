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
                    <h4>Create Users</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.store') }}" novalidate  class="needs-validation" method="POST">
                        @csrf

                        {{-- if error  --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="name">Role</label>
                            <select name="role" id="role" class="form-control select2">
                                <option value="">Select Role</option>
                                @foreach ($role as $roles)
                                    <option value="{{ $roles->id }}">{{ $roles->name }}</option>
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
                            <input type="text" name="name" class="form-control"  value="{{ old('name') }}" id="name" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" autocomplete="off" name="email" class="form-control"  value="{{ old('email') }}" id="email" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" autocomplete="off" name="password" class="form-control"  value="{{ old('password') }}" id="password" placeholder="Password">
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Password Confirmation</label>
                            <input type="password" name="password_confirmation" class="form-control"  value="{{ old('password_confirmation') }}" id="password_confirmation" placeholder="Password Confirmation">
                        </div>
                        
                        {{-- status --}}
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control select2">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
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