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
                            <h4>Data Section Pages</h4>
                            <div class="card-header-form mr-5 w-25">
                                <form action="" method="GET"
                                    {{ request()->has('type') ? 'style=display:none' : '' }}>
                                    <div class="input-group">
                                        <select class="form-control select2" name="visibility"
                                            onchange="this.form.submit()">
                                            <option value="-">-- Select Visibility --</option>
                                            @foreach (config('cms.visibility.page.section') as $key => $value)
                                                @if ( !in_array($key, session('permission_page')) )
                                                    @continue
                                                @endif
                                                <option value="{{ $key }}"
                                                    {{ request()->has('visibility') && request()->get('visibility') == $key ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                           
                            <div class="card-header-action">
                               
                                <a href="{{ route('page.section.create') }}" class="btn btn-primary">Add Pages</a>
                            </div>
                        </div>
                        {{-- add button --}}

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-2">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Name</th>
                                            <th>status</th>
                                            <th width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sort">
                                        @foreach ($data as $key => $item)
                                            @if($key == 0)
                                                @php
                                                    if($item->translations?->first()?->slug == 'search'){
                                                        $view = \\App\Helper\Helper::_view_page() . '/search?keyword=bank';
                                                    }else{
                                                        $view = \\App\Helper\Helper::_view_page() . '/page/' . $item->translations?->first()?->slug;
                                                    }
                                                @endphp
                                            @endif
                                            <tr class="s1" data-id="{{ $item->id }}">
                                                <td>{{ $item->sort }}</td>
                                                <td>{{ $item->translations[0]->name }}</td>
                                                <td>{!! Status($item->status) !!}</td>
                                                <td>
                                                    <a href="{{ $view }}"
                                                        target="_blank" class="btn btn-info ">View</a>
                                                    <a href="{{ route('page.section.edit', $item->id) }}"
                                                        class="btn btn-primary">Edit</a>
                                                    @if (request()->has('dev'))
                                                    <a href="{{ route('page.section.delete', $item->id) }}"
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
                    if (showEntries < 1 || countpag == 3) {
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
