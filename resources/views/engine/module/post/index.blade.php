@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
       
        <div class="section-header d-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if($breadcrumbs)
                        @foreach ($breadcrumbs  as $breadcrumb)
                        <li class="breadcrumb-item
                        @if($loop->last)
                            active
                        @endif
                        ">
                            @if($loop->last)
                                {{$breadcrumb['name']}}
                            @else 
                                <a href="{{$breadcrumb['url']}}">{{$breadcrumb['name']}}</a>
                            @endif
                        </li>
                        @endforeach
                    @endif
                </ol>
            </nav>
            <h1>{{ $categories ? $categories?->translations?->first()?->name : '' }} Management</h1>
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
                            <h4>Data {{ $categories ? $categories?->translations?->first()?->name : '' }}</h4>
                            <div class="card-header-action">
                                @if ($categories)
                                    <a href="{{ route('article.create', ['content' => $categories->id]) }}"
                                        class="btn btn-primary">Add</a>
                                @else
                                    <a href="{{ route('article.create') }}" class="btn btn-primary">Add</a>
                                @endif
                            </div>
                        </div>
                        {{-- add button --}}

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-custom">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>content</th>
                                            <th width="30%">Name</th>
                                            <th>Sort</th>
                                            <th>#</th>
                                            <th>status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-content">
                                        @foreach ($data as $article)
                                            <tr class="s1" data-id="{{ $article->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $article->categories()->count() > 0 ? ($content = $article->categories()->first()->content_name) : ($content = 'Uncategorized') }}
                                                </td>
                                                <td>
                                                    {!! $article->translations->first()->name !!}
                                                    <span class="small text-muted">
                                                        {{ $article->translations->first()->sub_name }}
                                                    </span>
                                                </td>
                                                <td>{{ $article->sort }}</td>
                                                
                                                <td>
                                                    @if ($article->visibility == 29) 
                                                        {{ reference_format_tender($article->custom) }} 
                                                    @else
                                                        {{$article->custom}}  
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! Status($article->status) !!}
                                                </td>
                                                <td>

                                                    @if ($categories)
                                                        <a href="{{ route('article.edit', [$article->id, 'content' => $categories->id]) }}"
                                                            class="btn btn-primary">Edit</a>
                                                        @if (request()->has('dev'))
                                                            <a href="{{ route('article.delete', [$article->id, 'content_id' => $categories->id]) }}"
                                                            class="btn btn-danger confirm-delete">Delete</a>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('article.edit', $article->id) }}"
                                                            class="btn btn-primary">Edit</a>
                                                        @if (request()->has('dev'))
                                                            <a href="{{ route('article.delete', $article->id) }}"
                                                                class="btn btn-danger confirm-delete">Delete</a>
                                                        @endif
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

            $('#table-content').sortable({
                opacity: 0.6,
                cursor: 'move',
                itemSelector: 'tr',
                placeholder: '<tr class="placeholder"/>',
                update: function() {
                    var data = [];
                    data.model = 'App\\Models\\WebArticles';
                    data.route = '{{ route('sortable') }}';
                    data.token = '{{ csrf_token() }}';
                    // check show entries datatable
                    var showEntries = $('#table-custom_length select').val();
                    // show entries mush -1
                    if (showEntries < 1) {
                        sendOrderToServer(data);
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
