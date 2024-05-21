@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Users Management</h1>
        </div>
        @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
       @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
      @endif
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                        <h4>Data Users</h4>
                        <div class="card-header-action">
                            <a href="{{ route('user.create') }}" class="btn btn-primary">Add User</a>
                        </div>
                    </div>
                    {{-- add button --}}
                    
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-2">
                          <thead>
                            <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Created At</th>
                              <th>status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($users as $user)
                            <tr>
                              <td>{{ $user->name }}</td>
                              <td>{{ $user->email }}</td>
                              <td>{{ $user->created_at }}</td>
                                <td>{!! Status($user->status) !!}</td>

                              <td>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger confirm-delete">Delete</a>
                              </td>
                            </tr>
                            @endforeach

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </section>
@endsection


@section('javascript')

@endsection