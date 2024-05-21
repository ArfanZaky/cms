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
                    <h4>Add Roles</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('role.store') }}" novalidate  class="needs-validation" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control"  value="{{ old('name') }}" id="name" placeholder="Name">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection