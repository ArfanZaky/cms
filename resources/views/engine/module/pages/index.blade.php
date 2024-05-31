@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Page Management</h1>
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
                            <h4>Data Generic Pages</h4>
                            {{-- filter with selectbox --}}

                            <div class="card-header-action">
                                <?php
                                $type = request()->has('type') ? request()->get('type') : false;
                                if ($type) {
                                    $url = route('page.generic.create', ['type' => $type]);
                                } else {
                                    $url = route('page.generic.create');
                                }
                                ?>
                                <a href="{{ $url }}" class="btn btn-primary">Add Pages</a>
                            </div>
                        </div>
                        {{-- add button --}}

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-c">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Name</th>
                                            <th>status</th>
                                            <th width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sort">
                                        @foreach ($data as $item)
                                            <tr class="s1" data-id="{{ $item->id }}">
                                                <td>{{ $item->sort }}</td>
                                                <td>{{ $item->translations?->first()?->name }}</td>
                                                <td>{!! Status($item->status) !!}</td>
                                                <td>
                                                    <a href="{{ route('page.generic.builder', $item->id) }}"
                                                        class="btn btn-primary ">Builder</a>
                                                    <a href="{{ \\App\Helper\Helper::_view_page() . '/page/' . $item->translations?->first()?->slug }}"
                                                        target="_blank" class="btn btn-info ">View</a>
                                                    @if (!$type)
                                                        <a href="{{ route('page.generic.edit', $item->id) }}"
                                                            class="btn btn-warning ">Edit</a>
                                                    @else
                                                        <a href="{{ route('page.generic.edit', [$item->id, 'type' => $type]) }}"
                                                            class="btn btn-warning ">Edit</a>
                                                    @endif
                                                    @if (request()->has('dev'))
                                                        <a href="{{ route('page.generic.delete', $item->id) }}"
                                                            class="btn btn-danger confirm-delete">Delete</a>
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
    <script>
        $(document).ready(function() {
            $("#table-c").dataTable({
                // show 10\
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "order": [
                    [1, 'desc']
                ],
                "language": {
                    "paginate": {
                        "next": '<i class="fas fa-angle-right"></i>',
                        "previous": '<i class="fas fa-angle-left"></i>'
                    }
                },
                "searching": true,

            });
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

            $('#sort').sortable({
                opacity: 0.6,
                cursor: 'move',
                itemSelector: 'tr',
                update: function() {
                    var data = [];
                    data.model = 'App\\Models\\WebPages';
                    data.route = '{{ route('sortable') }}';
                    data.token = '{{ csrf_token() }}';
                    var showEntries = $('#table-custom_length select').val();
                    var countpag = $('ul .paginate_button').length;
                    var visibility = '{{ request()->get('visibility') }}';
                    if (showEntries < 1 || countpag == 3) {
                        if (visibility) {
                            sendOrderToServer(data);
                        } else {
                            iziToast.error({
                                title: 'Error',
                                message: 'Please select Visibility',
                                position: 'topRight'
                            });
                        }
                    } else {
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
