@extends('layouts.app')
@section('content')
    {{-- add roles --}}
    <section class="section">
        <div class="section-header">
            <h1>Email Management</h1>
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
        {{-- form --}}
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Email</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('form.email.store') }}" novalidate class="needs-validation row"
                                method="POST">
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">content Product</label>
                                        <select class="form-control select2" id="content">
                                            <option value="">Select content Product</option>
                                            @foreach ($content_product as $value)
                                                <option value="{{ $value['value'] }}">{{ $value['label'] }}</option>
                                            @endforeach
                                        </select>
                                        {{-- invalid --}}
                                        @error('role')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Product</label>
                                        <select class="form-control select2" id="product">
                                            <option value="">Select Product</option>
                                        </select>
                                        {{-- invalid --}}
                                        @error('role')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Sub Product</label>
                                        <select name="content_id" class="form-control select2" id="sub">
                                            <option value="">Select Sub Product</option>
                                        </select>
                                        {{-- invalid --}}
                                        @error('role')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Branch</label>
                                        <select name="branch" class="form-control select2" id="sub">
                                            <option value="">Select Branch</option>
                                            @foreach ($branch as $value)
                                                <option value="{{ $value['value'] }}">{{ $value['label'] }}</option>
                                            @endforeach
                                        </select>
                                        {{-- invalid --}}
                                        @error('role')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input required type="email" autocomplete="off" name="email"
                                            class="form-control" value="{{ old('email') }}" id="email"
                                            placeholder="Email">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection


    @section('javascript')
        <script>
            $(document).ready(function() {
                // 'form.email.filter'
                $('#content').on('change', function() {
                    var id = $(this).val();
                    if (id != '') {
                        $.ajax({
                            url: "/engine/form/email/en/filter/" + id,
                            type: 'GET',
                            success: function(data) {
                                if (data.status.code == 200) {
                                    $('#product').empty();
                                    $('#sub').empty();
                                    var arr = data.data;
                                    for (var i = 0; i < arr.length; i++) {
                                        $('#product').append('<option value="' + arr[i].value +
                                            '">' + arr[i].label + '</option>');
                                    }
                                }
                            }
                        });
                    }
                });

                $('#product').on('change', function() {
                    var id = $(this).val();
                    if (id != '') {
                        $.ajax({
                            url: "/engine/form/email/en/filter/" + id,
                            type: 'GET',
                            success: function(data) {
                                if (data.status.code == 200) {
                                    $('#sub').empty();
                                    var arr = data.data;
                                    for (var i = 0; i < arr.length; i++) {
                                        $('#sub').append('<option value="' + arr[i].value + '">' +
                                            arr[i].label + '</option>');
                                    }
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
