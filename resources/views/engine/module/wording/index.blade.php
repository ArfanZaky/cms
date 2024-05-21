@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            {{-- add success --}}
            <h1>Setting Form</h1>
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
        @error('code')
            <div class="alert alert-danger">Code is {{ $errors->first('code') }}</div>
        @enderror
        <div class="section-body">

            <div id="output-status"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Wordings</h4>
{{--                            add--}}
                            <div class="card-header-action">
                                <a href="#" class="btn btn-primary add-wording">Add New</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">
                                            #
                                        </th>
                                        <th>Key</th>
                                        @foreach($language as $key => $l)
                                            <th>{{ $l }}</th>
                                        @endforeach
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($chunks as $chunk)
                                        <?php
                                            $values = [];
                                            foreach ($chunk as $key => $value) {
                                                $code = $value->code;
                                                $values[] = $value->value;
                                            }
                                            ?>
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $code }}</td>

                                                @foreach($values as $v)
                                                <td>
                                                    <input type="text" class="form-control data-{{$code}}" value="{{ $v }}">
                                                </td>
                                                @endforeach

                                            <td>
                                                <a href="#" class="btn btn-primary edit-wording" data-key="{{ $code }}" >Save</a>
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
    </section>
{{--    modal--}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-wording">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Wording</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('wordings.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-0">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" class="form-control" name="code" required>
                            </div>
                            @foreach($language as $key => $l)
                                <div class="form-group">
                                    <label>{{ $l }}</label>
                                    <input type="text" class="form-control" name="{{ $l }}" required>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('javascript')
    <script>
        $(document).ready(function() {
            // add
            $('.add-wording').click(function() {
                $('#modal-wording').modal('show');
            });

            $('.edit-wording').click(function() {
                var key = $(this).data('key');
                var values = [];
                $('.data-'+key).each(function() {
                    values.push($(this).val());
                });
                console.log(values);
                console.log(key);

                $.ajax({
                    url: '{{ route('wordings.update') }}',
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        code: key,
                        values: values
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            swal({
                                title: "Success",
                                text: "Data has been updated",
                                icon: "success",
                                button: "Ok",
                            });
                        } else {
                            swal({
                                title: "Error",
                                text: "Something went wrong",
                                icon: "error",
                                button: "Ok",
                            });
                        }
                    }
                });
            });

        });
    </script>
@endsection
