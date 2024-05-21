@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Brand Management</h1>
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
                        <h4>Data Brand</h4>
                        <div class="card-header-action">
                            <a href="{{ route('product.brand.create') }}" class="btn btn-primary">Add Brand</a>
                        </div>
                    </div>
                    {{-- add button --}}
                    
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-2">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th width="60%">Name</th>
                              <th>status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($menu_table as $item)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{!! $item['name'] !!}</td>
                              <td>
                                {!! Status($item['status']) !!}
                              <td>
                                <a href="{{ route('product.brand.edit', $item['id']) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('product.brand.delete', $item['id']) }}" class="btn btn-danger confirm-delete">Delete</a>
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