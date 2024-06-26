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
            <h1>Gallery Item Management</h1>
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
                    <form action="{{ route('gallery.photo.store', ['_visibility' => request()->get('visibility')]) }}"
                    method="POST" novalidate  class="needs-validation" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Gallery Item Form</h4>
                                </div>
                               
                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="form-group">
                                            <label for="parent">content : <em class="text-danger">*required</em></label>
                                            <select name="parent" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                <option value="">None</option>
                                                @foreach($gallery as $galleries)
                                                    <option value="{{ $galleries->id }}">
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
                                                @include('engine.include.name.create')
                                                @include('engine.include.slug.create')
                                                @include('engine.include.description.create')

                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="custom">Custom </em></label>
                                        <input type="text" name="custom"  class="form-control @error('custom') is-invalid @enderror" id="custom" value="{{ old('custom') }}"
                                            placeholder="custom" >
                                    </div>
                                    @error('custom')
                                        <div class="alert alert-danger">{{ $errors->first('custom') }}</div>
                                    @enderror
                                    {{-- <div class="form-group">
                                        <label for="url">File Url  <em class="text-danger">*required</em></label>
                                        <input type="text" name="url" class="form-control" id="url"
                                            placeholder="Url" required value="#">
                                        <small>Example : http://www.domain.com/path/to/your.file
                                        </small>
                                    </div>
                                    <div class="form-group">
                                        <label>Input File :</label>
                                        <div id="append-gallery">
                                            <div class="row p-2" style="border: ridge;">
                                                <div class="col-6 mt-4">
                                                    <input id="attachment-0" data-id="0" style="display: none"
                                                        class="form-control" type="text" name="attachment">
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
                                                    <div class="image_list_preview">
                                                        <img src="{{ asset('assets/img/default.jpg') }}" id="attachment_preview-0" width="400px" height="400px" alt="image"
                                                            class="img-thumbnail">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>  --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Image </h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.default.create')
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Mobile Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.mobile.create')
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="card-header">
                                        <h4>Tablet Image</h4>
                                    </div>
                                    @include('engine.include.image.tablet.create')
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Thumbnail Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.thumbnail.create')
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Apps Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.apps.create')
                                </div>
                            </div>
                        </div>
                     
                        <div class="card fixed_screen card-primary">
                            <div class="card-header">
                                <h4>Submit Form</h4>
                       
                              <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                              </div>
                            </div>
                              <div class="collapse" id="mycard-collapse">
                            
                                <div class="card-body row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="visibility">Visibility :</label>
                                            @foreach(collect(config('cms.visibility.gallery_item'))->has(request()->get('visibility')) ? config('cms.visibility.gallery_item')[request()->get('visibility')] : [] as $key => $value)
                                                <div class="form-check">
                                                    <input class="form-check-input" {{ 1 == $key ? 'checked' : '' }} type="radio" name="visibility"  value="{{$key}}">
                                                    <label class="form-check-label">
                                                        {{$value}}
                                                    </label>
                                                </div>
                                            @endforeach
                                           
                                        </div>
                                    </div>
                                    
                                    @include('engine.include.status.create')
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
    @endsection
@section('javascript')
<script>

    $(document).ready(function() {
        lfm('attachment_preview_filemanager-0', 'image');

    });
</script>
@endsection