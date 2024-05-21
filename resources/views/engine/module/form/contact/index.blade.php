@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
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
                            <h4>Form Contact</h4>
                            {{-- FILTER --}}
                            {{-- input date time interval --}}

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="product">Filter By Date : </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar"></i>
                                                </div>
                                            </div>
                                            <input type="text" id="daterange" class="form-control daterange-cus">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="status">Reset Form: </label>
                                        <button type="button" class="btn btn-danger form-control "
                                            id="btn-filter">Reset</button>

                                    </div>
                                    <div class="form-group">
                                        <label for="status">Export: </label>
                                        <button type="button" class="btn btn-success form-control " id="btn-export">Export
                                            to Excel</button>

                                    </div>

                                </div>

                            </div>

                            <div>
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>

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

    {{-- modal show --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_form">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('form.contact.update') }}" method="POST" enctype="multipart/form-data" id="form-close">
                        @csrf
                        <input type="text" name="id" hidden id="id" class="form-control">
                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <input type="text" name="product" id="product" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="text" name="amount" id="amount" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reference_no">Reference No</label>
                                    <input type="text" name="reference_no" id="reference_no" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status_form">Status</label>
                                    <input type="text" name="status_form" id="status_form" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Description">Description</label>
                                    <textarea name="description" id="description" class="form-control" cols="30" style="height: 100px"
                                        rows="10"></textarea>
                                </div>
                            </div>

                            {{-- submit --}}
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <input type="submit" id="close_form" name="submit" class="btn btn-success" value="Close This Form" onclick="return confirm('Are you sure?')">
                                    <input type="submit" id="update_form" name="submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modal_logs">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Logs
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="activities" style="height: 500px; overflow:auto;flex-direction: column;flex-wrap: nowrap;">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')
    <script>
        let data = '';
        let product = '';
        let status = '';
        let type = "{{ request()->type }}";

        function filter() {
            var date1 = data.split(' - ')[0];
            var date2 = data.split(' - ')[1];
            datatableServerside({
                url: "{{ route('form.contact.filter') }}" + '?start_date=' + date1 + '&end_date=' + date2 +
                    '&product=' + product + '&status=' + status + '&type=' + type,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },

                ],
                columnDefs: [{
                        targets: [0, 1, 2, 3, 4, 5],
                        className: 'text-center',
                        visible: true,
                    },
                ],
            });
        }
        filter("");

        // $('.modal-show').click(function() {
        $(document).on('click', '.modal-show', function(e) {
            var id = $(this).attr('data-id');
            var full_name = $(this).attr('data-full_name');
            var reference_no = $(this).attr('data-reference_no');
            var date = $(this).attr('data-date');
            var product = $(this).attr('data-product');
            var amount = $(this).attr('data-amount');
            var status_form = $(this).attr('data-status_form');
            var type = $(this).attr('data-type');
            $('#id').val(id);
            $('#name').val(full_name);
            $('#reference_no').val(reference_no);
            $('#date').val(date);
            $('#product').val(product);
            $('#amount').val(amount);
            $('#status_form').val(status_form);
            console.log(status_form == 'Closed');
            if( status_form == 'Closed')
            {
                $('#close_form').addClass('d-none')
                $('#update_form').addClass('d-none')
            }
            else
            {
                $('#close_form').removeClass('d-none')
                $('#update_form').removeClass('d-none')
            }

            console.log(status_form);

            if (type == 'close') {
                $('#close_form').show();
                $('#update_form').hide();
                $('#status_form').attr('disabled', true);
            } else {
                $('#close_form').hide();
                $('#update_form').show();
                $('#status_form').attr('disabled', false);
            }

            $('#modal_form').modal('show');
            $('#modal_form .modal-title').text('Show Data');

        });

        $(document).on('click', '.logs-show', function(e) {
            var id = $(this).attr('data-id');
            $('.activities').html('');
            $.ajax({
                url: "{{ route('form.contact.logs') }}"+ '/' + id, 
                type: "GET",
                success: function(response) {
                    $('.activities').html(response.html);
                }
            });
            $('#modal_logs').modal('show');
        });



        $('#daterange').change(function() {
            data = $(this).val();
            // $('#table-1').dataTable().fnClearTable();
            $('#table-1').dataTable().fnDestroy();
            filter();
        });

        $('#select-product').change(function() {
            product = $(this).val();
            // $('#table-1').dataTable().fnClearTable();
            $('#table-1').dataTable().fnDestroy();
            filter();
        });

        $('#select-status').change(function() {
            status = $(this).val();
            // $('#table-1').dataTable().fnClearTable();
            $('#table-1').dataTable().fnDestroy();
            filter();
        });


        $('#btn-filter').click(function() {
            $('#table-1').dataTable().fnDestroy();
            data = '';
            product = '';
            status = '';
            $('#daterange').val('');
            $('#select-product').val('');
            filter();
        });

        $('#btn-export').click(function() {
            var date1 = data.split(' - ')[0];
            var date2 = data.split(' - ')[1];
            var product = $('#select-product').val();
            var status = $('#select-status').val();
            // new tab
            window.open("{{ route('form.contact.export') }}" + '?start_date=' + date1 + '&end_date=' + date2 +
                '&product=' + product + '&status=' + status + '&type=' + type, '_blank');
        });

        $(document).on('click', '.confirm-close', function(e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: 'Once closed, you will not be able to edit this form!',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Poof! The form has been closed!", {
                        icon: "success",
                    });
                    // wait
                    setTimeout
                    (
                        function()
                        {
                            $('.confirm-close').unbind('click').click();
                        }
                        , 1000
                    );
                    
                } 
            });
        });
    </script>
@endsection
