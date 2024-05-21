@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Office Management</h1>
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
                            <h4>Data Office</h4>
                            <div class="card-header-action">
                                {{-- add banner modal --}}
                                <a href="#" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addOTypefficeModal">Add Office Type</a>
                                &nbsp;
                                <a href="#" class="btn btn-primary" onclick="form_data(this)" data-type="add">Add
                                    Office</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Office Type</th>
                                            <th>status</th>
                                            <th width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->address }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->type->name }}</td>
                                                <td>{!! Status($item->status) !!}</td>

                                                <td>
                                                    <a href="#" onclick="form_data(this)" data-type="edit"
                                                        data-id="{{ $item->id }}" data-name={{ $item->name }}
                                                        data-address={{ $item->address }} data-phone={{ $item->phone }}
                                                        data-email={{ $item->email }}
                                                        data-office_type={{ $item->type->id }}
                                                        data-status={{ $item->status }} data-fax={{ $item->fax }}
                                                        data-city={{ $item->city }} data-province={{ $item->province }}
                                                        data-maps={{ $item->maps }}
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
                    <h5 class="modal-title">Add Office</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_data" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="id" hidden id="id" class="form-control">
                        <div class='row'>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_id">Type</label>
                                    <select name="type_id" id="type_id" class="form-control select2" required>
                                        @foreach ($type as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea name="address" id="address" cols="30" rows="10" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fax">Fax</label>
                                    <input type="text" name="fax" id="fax" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" name="city" id="city" class="form-control" required>
                                </div>
                            </div>
                            {{-- province --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="province">Province</label>
                                    <input type="text" name="province" id="province" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="maps">Longitude</label>
                                    <input type="text" name="maps[]" id="Longitude" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="maps">Latitude</label>
                                    <input type="text" name="maps[]" id="Latitude" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option selected value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
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

    {{-- addTypeOfficeModal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="addOTypefficeModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Office Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- list --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4></h4>
                                    <div class="card-header-action">
                                        <a href="#" class="btn btn-info button-save" style="display: none">Save</a>
                                        <a href="#" class="btn btn-warning button-edit" >Edit Type</a>

                                        <a href="#" class="btn btn-primary button-add"
                                            >Add Type</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="table-1">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        #
                                                    </th>
                                                    <th>Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($type as $item)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <input type="text" name="name" id="name" data-id="{{ $item->id }}" readonly class="form-control readonly_type" value="{{ $item->name }}">
                                                        </td>
                                                        <td>
                                                          @if ($item->deleted_at == null)
                                                            <a href="#" onclick="form_data(this)" data-type="delete_type"
                                                                data-id="{{ $item->id }}"
                                                                class="btn btn-danger ">Delete</a>
                                                          @else
                                                            <a href="#" onclick="form_data(this)" data-type="restore_type"
                                                                data-id="{{ $item->id }}"
                                                                class="btn btn-success ">Restore</a>
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
        </div>
    </div>

    {{-- modal sm add name  --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="add_type">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Type Office</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="form_add_type">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name_type" id="name_type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')
    <script>

        $('.button-edit').click(function () {
          if ($('.readonly_type').attr('readonly') == 'readonly'){
            $('.readonly_type').removeAttr('readonly');
            $('.button-edit').text('Cancel');
            $('.button-save').show();
          }else{
            $('.readonly_type').attr('readonly', 'readonly');
            $('.button-edit').text('Edit Type');
            $('.button-save').hide();
          }
        });

        $('.button-save').click(function () {
          var data = [];
          $('.readonly_type').each(function () {
            data.push({
              id: $(this).data('id'),
              name: $(this).val()
            });
          });
          $.ajax({
            url: '{{ route('office.type.update') }}',
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              data: data
            },
            success: function (res) {
              if (res.status){
                  swal({
                      title: "Success",
                      text: "Data has been updated",
                      icon: "success",
                      button: "OK",
                  });
                  $('.readonly_type').attr('readonly', 'readonly');
                  $('.button-edit').text('Edit Type');
                  $('.button-save').hide();
              }else{
                console.log(res);
              }
            }
          });
        });

        $('.button-add').click(function () {
          $('#add_type').modal('show');
        });

        $('#form_add_type').submit(function (e) {
          e.preventDefault();
          $.ajax({
            url: '{{ route('office.type.add') }}',
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              name: $('#name_type').val()
            },
            success: function (res) {
              if (res.status){
                swal({
                    title: "Success",
                    text: "Data has been added",
                    icon: "success",
                    button: "OK",
                });
                $('#add_type').modal('hide');
                location.reload();
              }else{
                console.log(res);
              }
            }
          });
        });

        function form_data(e) {
            $('#id').val("");
            $('#name').val("");
            $('#status').val("");
            $('#address').val("");
            $('#phone').val("");
            $('#email').val("");
            $('#fax').val("");
            $('#city').val("");
            $('#province').val("");
            $('#Longitude').val("");
            $('#Latitude').val("");
            $('#type_id').val("");
            var type = $(e).attr('data-type');
            if (type == 'add') {
                $('#form_data').attr('action', '{{ route('office.store') }}');
                $('#modal_form').modal('show');
            } else if (type == 'edit') {
                var id = $(e).attr('data-id');
                var name = $(e).attr('data-name');
                var status = $(e).attr('data-status');
                var office_type = $(e).attr('data-office_type');
                var address = $(e).attr('data-address');
                var phone = $(e).attr('data-phone');
                var email = $(e).attr('data-email');
                var fax = $(e).attr('data-fax');
                var city = $(e).attr('data-city');
                var province = $(e).attr('data-province');
                var maps = $(e).attr('data-maps');
                var Longitude = maps.split(',')[0];
                var Latitude = maps.split(',')[1];
                $('#id').val(id);
                $('#name').val(name);
                $('#status').val(status);
                $('#address').val(address);
                $('#phone').val(phone);
                $('#email').val(email);
                $('#fax').val(fax);
                $('#city').val(city);
                $('#province').val(province);
                $('#Longitude').val(Longitude);
                $('#Latitude').val(Latitude);
                $('#type_id').val(office_type);
                $('#form_data').attr('action', '{{ route('office.update') }}');
                $('#modal_form').modal('show');
            }else if(type == 'delete_type'){
                var id = $(e).attr('data-id');
                $('#id').val(id);
                $('#form_data').attr('action', '{{ route('office.type.delete') }}');
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
            }else if(type == 'restore_type'){
                var id = $(e).attr('data-id');
                $('#id').val(id);
                $('#form_data').attr('action', '{{ route('office.type.delete') }}');
                $('#form_data').attr('method', 'get');
                swal({
                        title: "Are you sure?",
                        text: "you will restore this file!",
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
            } else {
                var id = $(e).attr('data-id');
                $('#id').val(id);
                $('#form_data').attr('action', '{{ route('office.delete') }}');
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
