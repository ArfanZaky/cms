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
            <h1>Category Management</h1>
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
                        <h4>Data Category</h4>
                        <div class="card-header-action">
                          <?php
                          $parent = request()->has('parent') ? request()->get('parent') : false;
                          $url = route('category.article.create', ['parent' => request()->get('parent'), 'component' => request()->get('component')]);
                          ?>
                          <a href="{{ $url }}" class="btn btn-primary">Add Pages</a>
                      </div>
                    </div>
                    {{-- add button --}}

                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped" id="table-2">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th width="40%">Name</th>
                              <th>Template</th>
                              <th>sort</th>
                              <th>status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody id="sort">
                            @foreach ($menu_table as $item)
                            @php
                                if (!$parent) {
                                  $product = [33];
                                  if (request()->get('component') == 'product') {
                                    if (!in_array($item['visibility'], $product)) {
                                      continue;
                                    }
                                  }else{
                                    if (in_array($item['visibility'], $product)) {
                                      continue;
                                    }
                                  }
                                }
                               
                            @endphp

                            @if ($item['parent'] != 0)
                              @continue
                            @endif
                            <tr class="s1" data-id="{{$item['id']}}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                  @if (in_array($item['visibility'], [2,3,5, 6, 7]))
                                      <a href="{{ route('article',['category' => $item['id'],'component' => request()->get('component')]) }}">{!! $item['name']!!}</a>
                                    @else
                                      <a href="{{ route('category.article', ['parent' => $item['id'], 'component' => request()->get('component')]) }}">{!! $item['name']!!}</a>
                                    @endif
                                </td>
                                <td>
                                  {{ _get_visibility_post(config('cms.visibility.post.category'))->where('key', $item['visibility'])->pluck('value')->first() }}
                                </td>
                                <td>{{ $item['sort'] }}</td>
                                <td>{!! Status($item['status']) !!}</td>
                                <td>
                                 
                                  <a href="{{ \\App\Helper\Helper::_view_page() . $item['url'] }}" target="_blank" class="btn btn-info ">View</a>
                                  <a href="{{ route('category.article.edit', [$item['id'], 'parent' => request()->get('parent'), 'component' => request()->get('component')]) }}" class="btn btn-primary ">Edit</a>
                                  @if (request()->has('dev'))
                                    <a href="{{ route('category.article.delete', $item['id']) }}" class="btn btn-danger confirm-delete">Delete</a>
                                  @endif
                                  @if ($item['visibility'] == 21)
                                    <a href="{{ route('article',['category' => $item['id'],'component' => request()->get('component')]) }}" class="btn btn-success ">
                                      Items
                                    </a>
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

            $('#sort').sortable({
                opacity: 0.6,
                cursor: 'move',
                itemSelector: 'tr',
                placeholder: '<tr class="placeholder"/>',
                update: function() {
                    var data = [];
                    data.model = 'App\\Models\\WebContent';
                    data.route = '{{ route('sortable') }}';
                    data.token = '{{ csrf_token() }}';
                    var showEntries = $('#table-custom_length select').val();
                    var countpag = $('ul .paginate_button').length;
                    if (showEntries < 1 || countpag == 3) {
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
