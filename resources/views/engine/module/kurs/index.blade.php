@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Kurs Management</h1>
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
                            <h4>Data Kurs</h4>
                            <div class="card-header-action">
                                <a href="{{ asset('assets/img/example-kurs.xlsm') }}" class="btn btn-light" download>Download
                                    Example</a>
                                <a href="#" class="btn btn-info" onclick="importFile()" >Import from Excel</a>
                                <a href="{{ route('kurs.export') }}" class="btn btn-success">Export to Excel</a>
                                <a href="#" class="btn btn-primary" onclick="form_data(this)" data-type="add">Add
                                    Kurs</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Flag</th>
                                            <th>Country</th>
                                            <th>Currency</th>
                                            <th>Buy</th>
                                            <th>Sell</th>
                                            <th width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @php
                                                        ($item->image != 'default.jpg') ? $image = env('APP_URL').$item->image : $image = asset('storage/images/1/countries/'.strtolower($item->currency).'.png');
                                                    @endphp
                                                    <img src="{{ $image }}" alt="{{ $item->country }}"
                                                        style="width: 50px;height: 50px;">
                                                </td>

                                                <td>{{ $item->country }}</td>
                                                <td>{{ $item->currency }}</td>
                                                <td>{{ $item->bn_buy }}</td>
                                                <td>{{ $item->bn_sell }}</td>
                                                <td>
                                                    <a href="#" onclick="form_data(this)" data-type="edit"
                                                        data-id="{{ $item->id }}" 
                                                        data-country={{ $item->country }}
                                                        data-currency={{ $item->currency }}
                                                        data-bn_buy={{ $item->bn_buy }}
                                                        data-bn_sell={{ $item->bn_sell }} 
                                                        data-image={{ $item->image }}
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modal_import">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Kurs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_import" action="{{ route('kurs.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12 text-center">
                                        <div id="image-preview" class="image-preview m-auto">
                                          <label for="image-upload" id="image-label">Choose File</label>
                                          <input type="file" name="kurs" id="image-upload">
                                        </div>
                                      </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="submit" id="submit_form" class="btn btn-primary">Import</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_form">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Kurs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_data" action="{{route('kurs.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="id" hidden id="id" class="form-control">
                        <div class='row'>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" name="country" id="country" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <input type="text" name="currency" id="currency" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bn_buy">BN Buy</label>
                                    <input type="text" name="bn_buy" id="bn_buy" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bn_sell">BN Sell</label>
                                    <input type="text" name="bn_sell" id="bn_sell" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="bn_sell">Image</label>
                                    <input id="thumbnail" value="" class="form-control" type="text" name="image" required>
                                </div>
                                <img src="" id="holder" width="400px" height="400px" alt="image" style="width: -webkit-fill-available;height: fit-content;display: none;" />
                            </div>

                            <div class="col-md-2 mt-1">
                                <a id="lfm" data-input="thumbnail" data-preview="holder">
                                    <div class="image_list">
                                        <span>
                                            <i class="fa fa-upload"></i>
                                        </span>
                                    </div>
                                </a>
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
        document.getElementById('image-upload').onchange = function () {
            document.getElementById('image-label').innerHTML = this.files[0].name;
        };
        function importFile() {
            $('#modal_import').modal('show');
        }

        function form_data(e) {
            $('#id').val("");
            $('#country').val("");
            $('#currency').val("");
            $('#bn_buy').val("");
            $('#bn_sell').val("");
            $('#thumbnail').val("");
            lfm('lfm', 'image');
            
            var type = $(e).attr('data-type');
            if (type == 'add') {
                $('#form_data').attr('action', '{{ route('kurs.store') }}');
                $('#modal_form').modal('show');
            } else if (type == 'edit') {
                var id = $(e).attr('data-id');
                var country = $(e).attr('data-country');
                var currency = $(e).attr('data-currency');
                var bn_buy = $(e).attr('data-bn_buy');
                var bn_sell = $(e).attr('data-bn_sell');
                var image = $(e).attr('data-image');

                $('#id').val(id);
                $('#country').val(country);
                $('#currency').val(currency);
                $('#bn_buy').val(bn_buy);
                $('#bn_sell').val(bn_sell);
                $('#thumbnail').val(image);
                $('#form_data').attr('action', '{{ route('kurs.update') }}');
                $('#modal_form').modal('show');
            }else {
                var id = $(e).attr('data-id');
                $('#id').val(id);
                $('#form_data').attr('action', '{{ route('kurs.delete') }}');
                // event.preventDefault();
                event.stopPropagation();
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
    </script>
@endsection
