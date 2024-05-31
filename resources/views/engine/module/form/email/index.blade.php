@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Email Management</h1>
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
                        <h4>Data Email</h4>
                        <div class="card-header-action">
                            <a href="{{ route('form.email.create') }}" class="btn btn-primary">Add Email</a>
                        </div>
                    </div>
                    {{-- add button --}}
                    
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-email">
                          <thead>
                            <tr>
                              <th>Id</th>
                              <th>Product</th>
                              <th>Branch</th>
                              <th>Email</th>
                              <th>Created At</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($email as $value)
                            <tr>
                              <td>{{ $value->id }}</td>
                              <td>{{ $value?->content?->translations?->first()?->name }}</td>
                              <td>{{ $value?->branch?->translations?->first()?->name }}</td>
                              <td>{{ $value->email }}</td>
                              <td>{{ $value->created_at }}</td>
                              <td>
                                <a href="{{ route('form.email.edit', $value->id) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('form.email.delete', $value->id) }}" class="btn btn-danger confirm-delete">Delete</a>
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
    <script>
      $("#table-email").dataTable({
        // show 10
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
          "paginate": {
            "next": '<i class="fas fa-angle-right"></i>',
            "previous": '<i class="fas fa-angle-left"></i>'
          }
        },
        "searching": true,
        "order": [[ 0, "desc" ]]

      });
    </script>
@endsection