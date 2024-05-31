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
            <h1>content Chatbot Management</h1>
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
                        <h4>Data content</h4>
                        <div class="card-header-action">
                          <?php
                          $parent = request()->has('parent') ? request()->get('parent') : false;
                          if ($parent) {
                              $url = route('content.chatbot.create', ['parent' => $parent]);
                          } else {
                              $url = route('content.chatbot.create');
                          }
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
                              <th>status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($menu_table as $item)
                            @if ($item['parent'] != 0)
                              @continue
                            @endif
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{!! has_child($item,'content.chatbot') !!}</td>
                                <td>{!! Status($item['status']) !!}</td>
                                <td>
                                  @if (!$parent)
                                      <a href="{{ route('content.chatbot.edit', $item['id']) }}"
                                          class="btn btn-primary ">Edit</a>
                                  @else
                                      <a href="{{ route('content.chatbot.edit', [$item['id'], 'parent' => $parent]) }}"
                                          class="btn btn-primary ">Edit</a>
                                  @endif
                                  @if (request()->has('dev'))
                                    <a href="{{ route('content.chatbot.delete', $item['id']) }}" class="btn btn-danger confirm-delete">Delete</a>
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

@endsection
