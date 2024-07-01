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
            <h1>Content Management</h1>
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
                    <?php
                    $parent = request()->has('parent') ? request()->get('parent') : false;
                    $url = route('content.article.store', ['parent' => request()->get('parent'), 'component' => request()->get('component')]);
                    ?>
                    <form action="{{ $url }}" novalidate  class="needs-validation" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Content Form</h4>
                                </div>

                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="form-group">
                                            <label for="Parent">Content Parent : </label>
                                            <select name="parent" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                @if(!$parent)
                                                    <option value="0">- Root Content</option>
                                                @endif
                                                @foreach($menu_table as $content)
                                                    <option value="{{ $content['id'] }}" {{ old('parent') == $content['id'] ? 'selected' : '' }}>
                                                        <?php
                                                            $content['name'] = str_replace('<i class="fa fa-angle-double-right"></i>', '-', $content['name']);
                                                            $content['name'] = str_replace('<i class="fa fa-bars"></i>', '-', $content['name']);
                                                        ?>
                                                        {!! $content['name'] !!}

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
                                                @include('engine.include.sub_name.create')
                                                @include('engine.include.slug.create')
                                                <div class="row">
                                                    <div class="col-4">
                                                        @include('engine.include.redirection.create')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.label.create')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.target.create')
                                                    </div>
                                                </div>

                                                @include('engine.include.overview.create')
                                                @include('engine.include.description.create')
                                                @include('engine.include.info.create')
                                                <!-- <div class="row">
                                                    <div class="col-4">
                                                        @include('engine.include.url_1.create')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.label_1.create')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.target_1.create')
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-4">
                                                        @include('engine.include.url_2.create')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.label_2.create')
                                                    </div>
                                                    <div class="col-4">
                                                        @include('engine.include.target_2.create')
                                                    </div>
                                                </div> -->
                                            </div>
                                        <?php } ?>
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
                                                @include('engine.include.seo.create')

                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="tab-content" id="myTabContent2">
                                    <?php
                                    for($i=0; $i < $languages; $i++){ ?>
                                        <div class="tab-pane fade show {{ $i == 0 ? 'active' : '' }}  section_{{$i}}"
                                            id="{{ $array_languages[$i] }}" role="tabpanel"
                                            aria-labelledby="{{ $array_languages[$i] }}">

                                                @include('engine.include.section.create')
                                        </div>
                                    <?php } ?>

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

                        <div class="col-12" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Mobile Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.mobile.create')
                                </div>
                            </div>
                        </div>

                        <div class="col-12" style="display: none;">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="card-header">
                                        <h4>Tablet Image</h4>
                                    </div>
                                    @include('engine.include.image.tablet.create')
                                </div>
                            </div>
                        </div>

                        <div class="col-12" >
                            <div class="card">
                                <div class="card-header">
                                    <h4>Thumbnail Image</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.thumbnail.create')
                                </div>
                            </div>
                        </div>

                        <div class="col-12" style="display: none;">
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

                                <div class="card-body">
                                    <div class="col-12">
                                        <h5>Template</h5>
                                        <div class="form-group row" style="max-height: 200px;overflow: auto;">
                                            @php
                                                $visibilityParentData = false;
                                                if (!empty($parentData) && !empty(config('cms.visibility.post.detail.'.$parentData->visibility))) {
                                                    $visibilityParentData = collect(config('cms.visibility.post.detail.'.$parentData->visibility));
                                                }
                                                $index = 0;
                                            @endphp

                                            @if ($visibilityParentData)
                                                @foreach($visibilityParentData as $keys => $values)
                                                    <div class="col-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input"  {{ 0 == $index ? 'checked' : '' }} type="radio" name="visibility" id="visibility2" value="{{$keys}}">
                                                            <label class="form-check-label text-nowrap  " for="visibility2">
                                                                {{$values}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $index++;
                                                    @endphp
                                                @endforeach
                                            @else
                                                @foreach( config('cms.visibility.post.content') as $keys => $values)
                                                    <div class="col-6">
                                                        <label for="visibility">{{$keys}}</label>
                                                        @foreach($values as $key => $value)
                                                            <div class="form-check">
                                                                <input class="form-check-input"  {{ 50 == $key ? 'checked' : '' }} type="radio" name="visibility" id="visibility2" value="{{$key}}">
                                                                <label class="form-check-label text-nowrap  " for="visibility2">
                                                                    {{$value}}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    {{-- add line every 4 --}}
                                                    @if(($loop->iteration % 4) == 0)
                                                        <div class="w-100 border-bottom my-3">

                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                    @include('engine.include.status.create')
                                    @include('engine.include.set-menu.create')
                                    @include('engine.include.date.create')
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
            $(document).ready(function () {
            editor_config_builder('.tinymce_plugins_info');
            });
        </script>
    @endsection
