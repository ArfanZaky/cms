@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            {{-- add success --}}
            
            <h1>Gallery Management</h1>
           
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
                        <h4>Data Gallery</h4>
                        <div class="card-header-action">
                            <a href="{{ route('gallery.file.create') }}" class="btn btn-primary">Add Gallery</a>
                        </div>

                    </div>
                    {{-- add button --}}
                    
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-2">
                          <thead>
                            <tr>
                                <th>No</th>
                                <th>Gallery</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $file)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>
                                <a href="{{ route('gallery.file.show', $file->id) }}" target="_blank">
                                <?php 
                                    if (pathinfo($file->attachment, PATHINFO_EXTENSION) == 'pdf') {
                                        echo '<img src="'.asset('assets/img/pdf_thumbnail.png').'" id="holder-xs" width="100px" height="100px" alt="image"class="img-thumbnail">'; 
                                    }elseif (pathinfo($file->attachment, PATHINFO_EXTENSION) == 'docx') {
                                        echo '<img src="'.asset('assets/img/word_thumbnail.png').'" id="holder-xs" width="100px" height="100px" alt="image"class="img-thumbnail">'; 
                                    }elseif (pathinfo($file->attachment, PATHINFO_EXTENSION) == 'xlsx') {
                                        echo '<img src="'.asset('assets/img/excel_thumbnail.png').'" id="holder-xs" width="100px" height="100px" alt="image"class="img-thumbnail">'; 
                                    }
                                ?>
                                </a>
                                
                              </td>
                    
                              <td>{{ $file->translations[0]->name }}</td>
                              <td>{!! Status($file->status) !!}</td>
                              <td>
                                <a href="{{ route('gallery.file.edit', $file->id) }}" class="btn btn-primary">Edit</a>
                           
                             
                                <a href="{{ route('gallery.file.delete', $file->id) }}" class="btn btn-danger confirm-delete">Delete</a>
                          
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