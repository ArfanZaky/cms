@extends('layouts.app')
@section('content')
    <style>
      .s1 {
        cursor: pointer;
      }
    </style>
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            {{-- add success --}}
            
            <h1>Menu Management</h1>
           
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
                        <h4>Data Menu</h4>
                        <div class="card-header-action">
                            <a href="{{ route('menu') }}" class="btn btn-info"><i class="fas fa-sync"></i> Refresh</a>
                            <a href="{{ route('menu.create') }}" class="btn btn-primary">Add Menu</a>
                        </div>

                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-custom">
                          <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu</th>
                                <th>Visibility</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody id="table-content">
                            @foreach ($menu_table as $menu)
                                @if ($menu['parent'] != 0)
                                  @continue
                                @endif
                                <tr class="s1" data-id="{{ $menu['id'] }}">
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{!! has_child($menu,'menu') !!}</td>
                                  <td>{!! Visibility($menu['visibility'] , 'cms.visibility.menu') !!}</td>
                                  <td>{!! Status($menu['status']) !!}</td>
                                  <td>
                                    <a href="{{ route('menu.edit', $menu['id']) }}" class="btn btn-primary">Edit</a>
                                    @if (request()->has('dev'))
                                      <a href="{{ route('menu.delete', $menu['id']) }}" class="btn btn-danger confirm-delete">Delete</a>
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
        $('.confirm-delete').on('click', function(e) {
                e.preventDefault();
                var link = $(this).attr('href');
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this file!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            swal("Poof! Your file has been deleted!", {
                                icon: "success",
                            });
                            // wait
                            setTimeout
                                (
                                    function() {
                                        window.location.href = link;
                                    }, 1000
                                );
                        }
                    });
            });
        $('#table-content').sortable({
            opacity: 0.6,
            cursor: 'move',
            itemSelector: 'tr',
            placeholder: '<tr class="placeholder"/>',
            connectWith: '.data_4',
            update: function() {
              var showEntries = $('#table-custom_length select').val();
              var countpag = $('ul .paginate_button').length;
              if (showEntries < 1 || countpag == 3) {
                var array = [];
                $('tr.s1').each(function(){
                  array.push($(this).attr('data-id'));
                });
                $.ajax({
                  url: "{{ route('menu.sortable') }}",
                  type: "POST",
                  data: {
                    array: array,
                    _token: "{{ csrf_token() }}"
                  },
                  success: function(data) {
                      iziToast.success({
                        title: 'Success',
                        message: 'Update Menu Order Success',
                        position: 'topRight'
                      });
                  }
                });
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