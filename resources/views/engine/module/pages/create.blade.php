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
<?php
    $type = (request()->has('type')) ? true : false;
    $route = ($type) ? route('page.generic.store', ['type' => 'static']) : route('page.generic.store');
?>
    <section class="section">
        <div class="section-header">
            <h1>Page Management</h1>
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
                    <form action="{{ $route }}" method="POST" novalidate  class="needs-validation" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Page Form</h4>
                                </div>

                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        @if(!$type)
                                            <div class="form-group">
                                                <label for="menu_id">Menu : </label>
                                                <select name="menu_id" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" >
                                                    <option value="0">None</option>
                                                    @foreach($menu_table as $menus)
                                                        <option value="{{ $menus['id'] }}" {{ old('menu_id') == $menus['id'] ? 'selected' : '' }}>
                                                            <?php
                                                                $menus['name'] = str_replace('<i class="fa fa-angle-double-right"></i>', '-', $menus['name']);
                                                                $menus['name'] = str_replace('<i class="fa fa-bars"></i>', '-', $menus['name']);
                                                            ?>
                                                            {!! $menus['name'] !!}

                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small>Use to connect with menu</small>
                                            </div>
                                        @else
                                            <input type="hidden" name="visibility" value="99">
                                            <input type="hidden" name="menu_id" value="0">
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
                                                @include('engine.include.name.create')
                                                @include('engine.include.sub_name.create')
                                                @include('engine.include.slug.create')
                                                @include('engine.include.overview.create')
                                                @include('engine.include.description.create')
                                            </div>
                                        <?php } ?>
                                        <div class="form-group d-none">
                                            <label for="custom">Custom </em></label>
                                            <input type="text" name="custom"  class="form-control @error('custom') is-invalid @enderror" id="custom" value="{{ old('custom') }}"
                                                placeholder="custom" >
                                        </div>
                                        @error('custom')
                                            <div class="alert alert-danger">{{ $errors->first('custom') }}</div>
                                        @enderror
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

    </script>
@endsection
