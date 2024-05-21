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
            <h1>Product Management</h1>
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
                    <form action="{{ route('product.store') }}" novalidate  class="needs-validation" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Product Form</h4>
                                </div>
                               
                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="form-group">
                                            <label for="catalog_id">Catalog : <em class="text-danger">*required</em></label>
                                            <select name="catalog_id[]" id="select-picker" multiple="" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                @foreach($menu_catalog as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id, old('catalog_id', [])) ? 'selected' : '' }}>
                                                        {!! $item->translations[0]->name !!}
                                                        
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="variant_id">Variant : </label>
                                            <select name="variant_id[]" id="select-picker" multiple="" class="form-control input-sm show-tick select2" data-live-search="true" >
                                                @foreach($variant as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id, old('variant_id', [])) ? 'selected' : '' }}>
                                                        {!! $item->translations[0]->name !!}
                                                        
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- <div class="form-group">
                                            <label for="brand_id">Brand : <em class="text-danger">*required</em></label>
                                            <select name="brand_id" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                <option value="">- Choose Brand</option>
                                                @foreach($menu_brand as $item)
                                                    <option value="{{ $item->id }}" {{ $item->id == old('brand_id') ? 'selected' : '' }}>
                                                        {!! $item->translations[0]->name  !!}
                                                        
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="form-group">
                                            <label for="gallery_id">Gallery : </label>
                                            <select name="gallery_id" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" >
                                                <option value="">- Choose Gallery</option>
                                                @foreach($menu_gallery as $item)
                                                    <option value="{{ $item->id }}" {{ $item->id == old('gallery_id') ? 'selected' : '' }}>
                                                        {!! $item->translations[0]->name  !!}
                                                        
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
                                                @include('engine.include.overview.create')
                                                @include('engine.include.description.create')
                                                @include('engine.include.info.create')
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
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