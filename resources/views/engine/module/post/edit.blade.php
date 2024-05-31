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
            <h1>{{ $parent_categories ? $parent_categories?->translations?->first()?->name : '' }} Management</h1>
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
                    <form action="{{ route('article.update', $data->id) }}" novalidate  class="needs-validation" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Data {{ $parent_categories ? $parent_categories?->translations?->first()?->name : '' }} Form</h4>
                                </div>
                               
                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="form-group">
                                            <label for="content_id">content :<em class="text-danger">*required</em></label>
                                            <select name="content_id" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                               
                                                @foreach($categories as $content)
                                                    <option value="{{ $content->id }}" {{ $content->id == $data?->categories()?->first()?->id ? 'selected' : '' }}>
                                                        {{$content->translations->first()->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($zone)
                                        <input type="text" name="zone" hidden id="zone" value="1">
                                            <div class="form-group">
                                                <label for="zone">Branch :<em class="text-danger">*required</em></label>
                                                <select name="zone" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                    @foreach($zone as $zoneItem)
                                                        <option value="{{ $zoneItem['id'] }}" {{ $zoneItem['id'] == $relatedZone?->content_zone ? 'selected' : '' }}>
                                                            {{$zoneItem['name']}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
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
                                                @include('engine.include.sub_name.edit')
                                                @include('engine.include.slug.edit')
                                                
                                                <div class="row">
                                                    <div class="col-4">
                                                        @include('engine.include.redirection.edit')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.label.edit')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.target.edit')
                                                    </div>
                                                </div>

                                                @include('engine.include.overview.edit')
                                                @include('engine.include.description.edit')
                                                @include('engine.include.info.edit')
                                                {{-- <div class="row">
                                                    <div class="col-4">
                                                        @include('engine.include.url_1.edit')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.label_1.edit')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.target_1.edit')
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-4">
                                                        @include('engine.include.url_2.edit')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.label_2.edit')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.target_2.edit')
                                                    </div>
                                                </div> --}}
                                            </div>
                                        <?php } ?>
                                        <div class="form-group" style="display: none">
                                            <label for="custom">Custom </em></label>
                                            <input type="text" name="custom"  class="form-control @error('custom') is-invalid @enderror" id="custom" value="{{ $data->custom }}"
                                                placeholder="custom" >
                                        </div>
                                        @error('custom')
                                            <div class="alert alert-danger">{{ $errors->first('custom') }}</div>
                                        @enderror

                                        <div class="form-group" style="display: none">
                                            <label>Input File :</label>
                                            <div id="append-gallery">
                                                <div class="row p-2" >
                                                    <div class="col-6 mt-4">
                                                       
                                                        <a id="attachment_preview_filemanager-0" data-input="attachment-0"
                                                            data-preview="attachment_preview-0">
                                                            <div class="image_list">
                                                                <span>
                                                                    <i class="fa fa-upload"></i> Choose Document
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
                                                        </div>
                                                        <input id="attachment-0" data-id="0" value="{{ $data->attachment }}"
                                                         class="form-control" type="text" name="attachment">
                                                    </div>
                                                    <div class="col-2">
                                                    </div>
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
                                    <h4>SEO</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="myTabContent2">
                                        <?php 
                                        for($i=0; $i < $languages; $i++){ ?>
                                            <div class="tab-pane fade show {{ $i == 0 ? 'active' : '' }}  seo_{{$i}}"
                                                id="{{ $array_languages[$i] }}" role="tabpanel"
                                                aria-labelledby="{{ $array_languages[$i] }}">
                                                @include('engine.include.seo.update')

                                            </div>
                                        <?php } ?>
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

                        <div class="col-12" style="display: none">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Mobile Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.mobile.update')
                                </div>
                            </div>
                        </div>

                        <div class="col-12" style="display: none">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="card-header">
                                        <h4>Tablet Image</h4>
                                    </div>
                                    @include('engine.include.image.tablet.update')
                                </div>
                            </div>
                        </div>

                        <div class="col-12" style="display: none">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Thumbnail Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.thumbnail.update')
                                </div>
                            </div>
                        </div>

                        <div class="col-12" style="display: none">
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
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                              </div>
                            </div>
                              <div class="collapse" id="mycard-collapse">
                            
                                <div class="card-body">
                                    <div class="col-12">
                                        <h5>Template</h5>
                                        <div class="form-group">
                                            @if(isset(config('cms.visibility.post.item')[$parent_categories->visibility]))
                                                @foreach(config('cms.visibility.post.item')[$parent_categories->visibility] as $key => $value)
                                                    <div class="form-check">
                                                        <input class="form-check-input"   {{ $data->visibility == $key ? 'checked' : '' }} type="radio" name="visibility" id="visibility2" value="{{$key}}">
                                                        <label class="form-check-label" for="visibility2">
                                                            {{$value}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                @foreach(config('cms.visibility.post.item.default') as $key => $value)
                                                    <div class="form-check">
                                                        <input class="form-check-input"   {{ $data->visibility == $key ? 'checked' : '' }} type="radio" name="visibility" id="visibility2" value="{{$key}}">
                                                        <label class="form-check-label" for="visibility2">
                                                            {{$value}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    @include('engine.include.date.update')
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
    @endsection

        
    @section('javascript')
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
        <script>
            lfm('attachment_preview_filemanager-0', 'document');

            function OpenModal() {
                $('#modal-preview').modal();
            }
            $(document).ready(function () {
                editor_config_builder('.tinymce_plugins_info');
            });
        </script>
    @endsection