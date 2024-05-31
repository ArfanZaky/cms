@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            {{-- add success --}}

            <h1>Gallery Item Management</h1>

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
        <div class="card-body">
            <ul class="nav nav-pills" id="myTab3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active show" id="gallery-tab-1" data-toggle="tab" href="#gallery-1" role="tab"
                        aria-controls="home" aria-selected="true">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="gallery-tab-2" data-toggle="tab" href="#gallery-2" role="tab"
                        aria-controls="profile" aria-selected="false">Table</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent2">
                <div class="tab-pane fade active show" id="gallery-1" role="tabpanel" aria-labelledby="gallery-tab-1">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Data Gallery Item</h4>
                                        <div class="card-header-form mr-5 w-25">
                                            <form action="" method="GET"
                                                {{ request()->has('content') ? 'style=display:none' : '' }}>
                                                <div class="input-group">
                                                    <select class="form-control select2" name="content"
                                                        onchange="this.form.submit()">
                                                        <option value="-">-- Select Gallery --</option>
                                                        @foreach ($gallery as $key => $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ request()->has('content') && request()->get('content') == $item->id ? 'selected' : '' }}>
                                                                {{ $item->translations[0]->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="card-header-action">
                                            <a href="{{ route('gallery.photo.create', ['visibility' => request()->get('visibility')]) }}"
                                                class="btn btn-primary">Add Photo</a>
                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div class="col-12 col-sm-12 col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="gallery gallery-md">
                                                        @foreach ($data as $key => $photo)
                                                            <?php
                                                            $photo->image != 'default.jpg' ? ($image = env('APP_URL') . $photo->image) : ($image = asset('assets/img/default.jpg'));
                                                            ?>
                                                            <div class="gallery-item" style="margin-bottom: 30px;"
                                                                data-image="{{ $image }}"
                                                                data-title="{{ $photo->id }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            {{ $data->links() }}
                                                        </div>
                                                        <div class="col-lg-4">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="gallery-2" role="tabpanel" aria-labelledby="gallery-tab-2">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Data Gallery Item</h4>
                                        <div class="card-header-form mr-5 w-25">
                                            <form action="" method="GET" >
                                                <div class="input-group">
                                                    <select class="form-control select2" name="content"
                                                        onchange="this.form.submit()">
                                                        <option value="-">-- Select Gallery --</option>
                                                        @foreach ($gallery as $key => $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ request()->has('content') && request()->get('content') == $item->id ? 'selected' : '' }}>
                                                                {{ $item->translations[0]->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="card-header-action">
                                            <a href="{{ route('gallery.photo.create', ['visibility' => request()->get('visibility')]) }}"
                                                class="btn btn-primary">Add Photo</a>
                                        </div>

                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="table-2">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Photo</th>
                                                        <th>content</th>
                                                        <th>Name</th>
                                                        <th>#</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($data as $photo)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>
                                                              @php
                                                                ($photo->image != 'default.jpg') ? $image = env('APP_URL').$photo->image : $image = asset('assets/img/default.jpg');
                                                              @endphp
                                                                <img src="{{ $image }}"
                                                                    alt="" width="100px">
                                                            </td>
                                                            <td>{{ $photo->gallery->translations[0]->name }}</td>
                                                            <td>{{ $photo->translations[0]->name }}</td>
                                                            <td>{{ $photo->custom }}</td>
                                                            <td>{!! Status($photo->status) !!}</td>
                                                            <td>
                                                                <a href="{{ route('gallery.photo.edit', ['id' => $photo->id, 'visibility' => request()->get('visibility')]) }}"
                                                                    class="btn btn-primary">Edit</a>


                                                                <a href="{{ route('gallery.photo.delete', $photo->id) }}"
                                                                    class="btn btn-danger confirm-delete">Delete</a>

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

    </section>
@endsection


@section('javascript')
    <script>
        // right click trigger event
        $('.gallery-item').contextmenu(function(e) {
            e.preventDefault();
            var id = $(this).data('title');
            var image = $(this).data('image');
            var url = "{{ route('gallery.photo.delete', ':id') }}";
            url = url.replace(':id', id);
            var urlEdit =
                "{{ route('gallery.photo.edit', ['id' => ':id', 'visibility' => request()->get('visibility')]) }}";
            urlEdit = urlEdit.replace(':id', id);

            var html = `
        <div class="dropdown-menu dropdown-custom" style="display: block; position:relative;z-index:20">
          <a class="dropdown-item has-icon" href="` + urlEdit + `"><i class="fas fa-edit"></i> Edit</a>
          <a class="dropdown-item has-icon confirm-delete" href="` + url + `"><i class="fas fa-trash"></i> Delete</a>
        </div>
      `;
            if ($('.dropdown-custom').length > 0) {
                $('.dropdown-custom').remove();
            }

            // $(this).parent().append(html);
            var newElement = $(html);
            newElement.insertAfter($(this));
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
        });

        // remove dropdown menu
        $(document).click(function() {
            $('.dropdown-custom').remove();
        });
    </script>
@endsection
