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
            <h1>Menu Management</h1>
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
                    <form action="{{ route('menu.store') }}" novalidate class="needs-validation" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Menu Form</h4>
                                </div>

                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="form-group">
                                            <label for="parent">Menu Parent: </label>
                                            <select name="parent" id="select-picker"
                                                class="form-control input-sm show-tick select2" data-live-search="true"
                                                required>
                                                <option value="0">None</option>
                                                @foreach ($menu_table_menu as $menus)
                                                    <option value="{{ $menus['id'] }}"
                                                        {{ old('parent') == $menus['id'] ? 'selected' : '' }}>
                                                        <?php
                                                        $menus['name'] = str_replace('<i class="fa fa-angle-double-right"></i>', '-', $menus['name']);
                                                        $menus['name'] = str_replace('<i class="fa fa-bars"></i>', '-', $menus['name']);
                                                        ?>
                                                        {!! $menus['name'] !!}

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
                                            <div class="form-group">
                                                <label for="name">Name </label>
                                                <input type="text" name="name[]" class="form-control @error('name.'.$i) is-invalid @enderror"  id="name" value="{{ old('name.'.$i) }}" 
                                                    placeholder="Name" >
                                            </div>
                                            
                                            @error('name.'.$i)
                                                <div class="alert alert-danger">{{ $errors->first('name.'.$i) }}</div>
                                            @enderror

                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Connect to Module or Use Direct Url :</h4>
                                </div>
                                <div class="card-body row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="content_id">content</label>
                                            <select name="content_id" id="select-picker"
                                                class="form-control input-sm show-tick select2" data-live-search="true"
                                                required>
                                                <option value="0">- None</option>
                                                @foreach ($menu_table_content as $menus)
                                                    <option value="{{ $menus['id'] }}"
                                                        {{ old('content_id') == $menus['id'] ? 'selected' : '' }}>
                                                        <?php
                                                        $menus['name'] = str_replace('<i class="fa fa-angle-double-right"></i>', '-', $menus['name']);
                                                        $menus['name'] = str_replace('<i class="fa fa-bars"></i>', '-', $menus['name']);
                                                        ?>
                                                        {!! $menus['name'] !!}

                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="menu_id">or as Alias from Other Menu</label>
                                            <select name="menu_id" id="select-picker"
                                                class="form-control input-sm show-tick select2" data-live-search="true"
                                                required>
                                                <option value="0">- None</option>
                                                @foreach ($menu_table_menu as $menus)
                                                    <option value="{{ $menus['id'] }}"
                                                        {{ old('menu_id') == $menus['id'] ? 'selected' : '' }}>
                                                        <?php
                                                        $menus['name'] = str_replace('<i class="fa fa-angle-double-right"></i>', '-', $menus['name']);
                                                        $menus['name'] = str_replace('<i class="fa fa-bars"></i>', '-', $menus['name']);
                                                        ?>
                                                        {!! $menus['name'] !!}

                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="menu_id">or Direct Url</label>
                                            <input type="text" name="url" class="form-control" required
                                                value="{{ old('url') ?? '#' }}">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="menu_id">add Parameter</label>
                                            <input type="text" name="parameter" class="form-control" value="">
                                            <small class="text-muted">Example : <code>?id=1</code></small>
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
                                    @include('engine.include.image.default.create')
                                </div>
                            </div>
                        </div>
         
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Image Mobile</h4>
                                </div>
                                <div class="card-body row">
                                    @include('engine.include.image.mobile.create')
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Image Multi Language (Optional)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="myTabContent2">
                                        <?php
                                        for($i=0; $i < $languages; $i++){ ?>
                                            <div class="tab-pane fade show {{ $i == 0 ? 'active' : '' }}  seo_{{$i}}"
                                                id="{{ $array_languages[$i] }}" role="tabpanel"
                                                aria-labelledby="{{ $array_languages[$i] }}">
                                                <div class="card-body row">
                                                    @include('engine.include.image.language.default.create')
                                                    @include('engine.include.image.language.mobile.create')
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
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

                                <div class="card-body row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="target">Target : </label>
                                            {{-- radio --}}
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" checked type="radio" name="target"
                                                    id="target" value="0"
                                                    {{ old('target') == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="target">_self</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="target" id="target"
                                                    value="1" {{ old('target') == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="target">_blank</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group" style="max-height: 200px;overflow: auto;">
                                            <label for="visibility">Visibility :</label>
                                            @foreach(config('cms.visibility.menu') as $key => $value)
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
    </section>
@endsection

@section('javascript')
<?php
for($i=0; $i < $languages; $i++){ ?>
    <script>
        $(document).ready(function () {
            lfm('lfm-{{$i}}', 'image');
            lfm('lfm-sm-{{$i}}', 'image');
        })
    </script>
<?php } ?>
@endsection
     
