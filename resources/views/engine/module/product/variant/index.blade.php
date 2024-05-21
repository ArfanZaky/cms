@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Variant Management</h1>
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
                        <h4>Data Variant</h4>
                        <div class="card-header-form mr-5 w-25" >
                          <form action="" method="GET" {{ (request()->has('type')) ? 'style=display:none' : '' }}>
                              <div class="input-group">
                                  <select class="form-control select2" name="product" onchange="this.form.submit()">
                                      <option value="-">-- Select Product --</option>
                                      @foreach ($product as $item)
                                          <option value="{{ $item->id }}" {{ (request()->get('product') == $item->id) ? 'selected' : '' }}>{{ $item->translations->first()->name }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </form>
                        </div>
                        <div class="card-header-action">
                            <a href="{{ route('variant.create') }}" class="btn btn-primary">Add Variant</a>
                        </div>
                    </div>
                    {{-- add button --}}

                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-2">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Product</th>
                              <th width="20%">Name</th>
                              <th>Sort</th>
                              <th>status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody id="table-content" >
                            @forelse  ($data as $item)
                                <tr class="s1" data-id="{{ $item->id }}">
                              <td>{{ $loop->iteration }}</td>
                              <td>{!! $item->relation?->product?->translations->first()?->name !!}</td>
                              <td>{!! $item?->translations[0]?->name ?? "" !!}</td>
                              <td>{{ $item->sort }}</td>

                              <td>
                                {!! Status($item->status) !!}
                              <td>
                                <a href="{{ route('variant.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('variant.delete', $item->id) }}" class="btn btn-danger confirm-delete">Delete</a>
                              </td>
                            </tr>
                            @empty
                            @endforelse


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
        $(document).ready(function() {
            $('#table-content').sortable({
                opacity: 0.6,
                cursor: 'move',
                itemSelector: 'tr',
                placeholder: '<tr class="placeholder"/>',
                update: function() {
                    var data = [];
                    data.model = 'App\\Models\\WebProducts';
                    data.route = '{{ route('sortable') }}';
                    data.token = '{{ csrf_token() }}';
                    // check show entries datatable
                    var showEntries = $('#table-2_length select').val();
                    // show entries mush -1
                    if (showEntries < 1) {
                      sendOrderToServer(data);
                    }else{
                      iziToast.error({
                          title: 'Error',
                          message: 'Please select show entries all',
                          position: 'topRight'
                      });
                    }
                }
            });
        });
    </script>
@endsection