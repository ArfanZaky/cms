@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Vacancy Management</h1>
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
                        <h4>Data Vacancy</h4>
                        <div class="card-header-action">
                            <a href="{{ route('vacancy.create') }}" class="btn btn-primary">Add Vacancy</a>
                        </div>
                    </div>
                    {{-- add button --}}
                    
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-2">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>content</th>
                              <th>Name</th>
                              <th>status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $vacancy)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $vacancy->categories->translations[0]->name }}</td>
                              <td>{{ $vacancy->translations[0]->name }}</td>
                            
                                <td>{!! Status($vacancy->status) !!}</td>

                              <td>
                                <a href="{{ route('vacancy.edit', $vacancy->id) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('vacancy.delete', $vacancy->id) }}" class="btn btn-danger confirm-delete">Delete</a>
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