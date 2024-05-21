@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Template Management</h1>
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
                            <h4>Data Template</h4>
                            <div class="card-header-action">
                                {{-- add banner modal --}}
                                <a href="#" class="btn btn-primary" onclick="form_data(this)" data-type="add">Add
                                    Template</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->code }}</td>
                                                <td>
                                                    <a href="#" onclick="form_data(this)" data-type="edit"
                                                        data-id="{{ $item->id }}" data-name={{ Str::slug($item->name) }}
                                                        data-code={{ Str::slug($item->code) }}
                                                        class="btn btn-primary btn-action mr-1">Edit</a>
                                                    <a href="#" onclick="form_data(this)" data-type="delete"
                                                        data-id="{{ $item->id }}" class="btn btn-danger ">Delete</a>
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

    {{-- modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_form">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_data" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="id" hidden id="id" class="form-control">
                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Code">Code</label>
                                    <input name="code" id="Code"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="submit" id="submit_form" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal --}}

@endsection


@section('javascript')
    <script>

        $('.button-edit').click(function () {
          if ($('.readonly_type').attr('readonly') == 'readonly'){
            $('.readonly_type').removeAttr('readonly');
            $('.button-edit').text('Cancel');
          }else{
            $('.readonly_type').attr('readonly', 'readonly');
            $('.button-edit').text('Edit Type');
          }
        });
     

        function form_data(e) {
            $('#id').val("");
            $('#name').val("");
            $('#Code').val("");
            var type = $(e).attr('data-type');
            if (type == 'add') {
                $('#form_data').attr('action', '{{ route('template.store') }}');
                $('#modal_form').modal('show');
            } else if (type == 'edit') {
                var id = $(e).attr('data-id');
                var name = $(e).attr('data-name').replace(/-/g, " ");
                var Code = $(e).attr('data-code').replace(/-/g, " ");
                $('#id').val(id);
                $('#name').val(name);
                $('#Code').val(Code);
                $('#form_data').attr('action', '{{ route('template.update') }}');
                $('#modal_form').modal('show');
            }else {
                var id = $(e).attr('data-id');
                $('#id').val(id);
                $('#form_data').attr('action', '{{ route('template.delete') }}');
                $('#form_data').attr('method', 'get');
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this file!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $('#form_data').submit();
                        } else {
                            swal("Your file is safe!");
                        }
                    });

            }
        }
        $(document).ready(function() {
            $('#form_data').submit(function(e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                var type = $(this).attr('method');
                AjaxFunction({
                    data: data,
                    url: url,
                    type: type,
                });
            });
        });
    </script>
@endsection
