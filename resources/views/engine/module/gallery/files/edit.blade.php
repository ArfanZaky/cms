@extends('layouts.app')
@section('content')
    <style>
        /* fixed in bottom righht */
        .fixed_screen {
            position: fixed;
            bottom: 0;
            right: 0;
            z-index: 999;
            justify-content: end;
        }
    </style>
    <section class="section">
        <div class="section-header">
            <h1>Files Management</h1>
        </div>
        {{-- if error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- end if error --}}

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('gallery.file.update', $data->id) }}" method="POST" novalidate
                        class="needs-validation" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Files Form</h4>
                                </div>

                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="form-group">
                                            <label for="parent">Menu Parent: <em
                                                    class="text-danger">*required</em></label>
                                            <select name="parent" id="select-picker"
                                                class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                <option value="">Choose content</option>
                                                @foreach ($gallery as $galleries)
                                                    <option value="{{ $galleries->id }}"
                                                        {{ $galleries->id == $data->gallery_id ? 'selected' : '' }}>
                                                        {!! $galleries->translations[0]->name !!}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <?php
                                        $array_languages = languages();
                                        $languages = config('app.language_setting');
                                        ?>
                                        <?php 
                                        for($i=0; $i < $languages; $i++){ ?>
                                        <div class="tab-pane fade show {{ $i == 0 ? 'active' : '' }}"
                                            id="{{ $array_languages[$i] }}" role="tabpanel"
                                            aria-labelledby="{{ $array_languages[$i] }}">
                                            @include('engine.include.name.edit')
                                            @include('engine.include.slug.edit')
                                            @include('engine.include.description.edit')

                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="url">File Url <em class="text-danger">*required</em></label>
                                        <input type="text" name="url" class="form-control" id="url"
                                            value="{{ $data->url }}" placeholder="Url" required value="#">
                                        <small>Example : http://www.domain.com/path/to/your.file
                                        </small>
                                    </div>
                                    <div class="form-group">
                                        <label>Input File :</label>
                                        <div id="append-gallery">
                                            <div class="row p-2" >
                                                <div class="col-6 mt-4">
                                                   
                                                    <a id="attachment_preview_filemanager-0" data-input="attachment-0"
                                                        data-preview="attachment_preview-0">
                                                        <div class="image_list">
                                                            <span>
                                                                <i class="fa fa-upload"></i> Choose Gallery
                                                            </span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-6">
                                                    <div class="image_list_preview" onclick="OpenModal()">
                                                        {{-- icon pdf --}}
                                                        <img class="img-fluid" src="{{ asset('assets/img/pdf.png') }}"
                                                            alt="Preview"> <br>
                                                        <small style="color:black">Click For Preview</small>
                                                        <input id="attachment-0" data-id="0" value="{{ $data->attachment }}"
                                                        class="form-control" type="text" name="attachment">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Image </h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.default.update')
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Mobile Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.mobile.update')
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="card-header">
                                        <h4>Tablet Image</h4>
                                    </div>
                                    @include('engine.include.image.tablet.update')
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Thumbnail Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.thumbnail.update')
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Apps Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.apps.update')
                                </div>
                            </div>
                        </div>


                        <div class="card fixed_screen card-primary">
                            <div class="card-header">
                                <h4>Submit Form</h4>

                                <div class="card-header-action">
                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                                            class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="collapse" id="mycard-collapse">

                                <div class="card-body ">
                                    
                                    @include('engine.include.status.update')
                                    {{-- Submit Button --}}
                                    <div class="col-12  text-right">
                                        @include('engine.include.submit.submit')
                                    </div>
                                    {{-- End Submit Button --}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
      {{-- modal preview pdf --}}
      <div class="modal fade" id="modal-preview" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Preview File</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body
              ">
                  <embed id="attachment_preview-0" src="{{ env('APP_URL').$data->attachment }}" frameborder="0" width="100%"
                      height="500px" />
              </div>
          </div>
      </div>
  </div>
  {{-- end modal preview pdf --}}
    @endsection
  
    @section('javascript')
        <script>
            lfm('attachment_preview_filemanager-0', 'document');

            function OpenModal() {
                $('#modal-preview').modal();
            }
            $(document).ready(function() {

            });
        </script>
    @endsection
