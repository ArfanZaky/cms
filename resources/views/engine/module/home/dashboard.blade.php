@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {!! Helper::_setting_code('web_report') !!}

        <div class="section-body">
            {{-- audit log --}}
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recent Activity</h4>
                        </div>
                        <div class="card-body">
                            {{-- tabble --}}
                            <div >
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Activity</th>
                                            <th>Name</th>
                                            <th>IP</th>
                                            <th>Datetime</th>
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
    </section>
@endsection

@section('javascript')
    <script>
        datatableServerside({
            url: "{{ route('Apilogs') }}",
            columns: [
                {
                    data: 'admin_name',
                    name: 'admin_name'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'name_main',
                    name: 'name_main',
                },
                {
                    data: 'ip',
                    name: 'ip'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
            ],
            columnDefs: [{
                targets: [0, 3],
                className: 'text-center'
            }],
        });


    </script>
@endsection
