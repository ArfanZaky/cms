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
                    <h4>Edit Permissions</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('permission.update', $Permissions->id) }}"  novalidate  class="needs-validation" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input type="text" name="name" class="form-control"  value="{{ $Permissions->name }}" id="name" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="name">Detail</label>
                            <input type="text" name="code" class="form-control"  value="{{ $Permissions->code }}" id="name" placeholder="Detail">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection