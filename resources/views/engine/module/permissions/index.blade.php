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
                        <h4>Data Permissions</h4>
                        <div class="card-header-action">
                          @if (in_array('permission/create', session('permission')))
                            <a href="{{ route('permission.create') }}" class="btn btn-primary">Add Permissions</a>
                          @endif
                        </div>

                    </div>
                    {{-- add button --}}
                    
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-custom-alt">
                          <thead>
                            <tr>
                                <th>No</th>
                                <th>Slug</th>
                                <th>Detail</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($permissions as $permission)
                            <tr class="s1" data-id="{{ $permission->id }}">
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $permission->name }}</td>
                              <td>{{ $permission->code }}</td>
                              <td>{{ $permission->created_at }}</td>
                              <td>
                                @if (in_array('permission/edit', session('permission')))
                                <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-primary">Edit</a>
                                @endif
                                @if (in_array('permission/delete', session('permission')))
                                <a href="{{ route('permission.delete', $permission->id) }}" class="btn btn-danger confirm-delete">Delete</a>
                                @endif
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
    {{-- sortable jqeury--}}
    <script>
       $(document).ready(function() {
        $('#table-custom-alt').DataTable({
          "aLengthMenu": [10],
          'sort': false,
        });
    });
    </script>
   

@endsection
