@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            {{-- add success --}}
            
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
                            <a href="{{ route('role.create') }}" class="btn btn-primary">Add User</a>
                        </div>
                    </div>
                    {{-- add button --}}
                    
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-2">
                          <thead>
                            <tr>
                              <th class="text-center">
                                #
                              </th>
                              <th width="50%">Name</th>
                              <th>Permissions</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($roles as $role)
                            <tr>
                              <td>
                                {{ $loop->iteration }}
                              </td>
                              <td>{{ $role->name }}</td>
                              <td>
                                <a href="{{ route('role.permission', $role->id) }}" class="btn btn-dark">Permissions</a>
                                
                              </td>
                            
                              <td>
                                <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('role.delete', $role->id) }}" type="submit" class="btn btn-danger confirm-delete">Delete</a>
                            
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