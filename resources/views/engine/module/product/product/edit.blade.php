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
            <h1>content Catalog Management</h1>
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
                    <form action="{{ route('product.update', $data->id) }}" novalidate  class="needs-validation" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>content Catalog Form</h4>
                                </div>
                               
                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                  
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="form-group">
                                            <label for="catalog_id">Catalog : <em class="text-danger">*required</em></label>
                                            <select name="catalog_id[]" multiple="" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                <option value="">- Choose Catalog</option>
                                                @foreach($menu_catalog as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id, $catalog_relation) ? 'selected' : '' }}>
                                                        {!! $item->translations[0]->name !!}
                                                        
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="variant_id">Variant : </label>
                                            <select name="variant_id[]" id="select-picker" multiple="" class="form-control input-sm show-tick select2" data-live-search="true" >
                                                @foreach($variant as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id, $variant_relation) ? 'selected' : '' }}>
                                                        {!! $item->translations[0]->name !!}
                                                        
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="brand_id">Brand : </label>
                                            <select name="brand_id" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                <option value="">- Choose Brand</option>
                                                @foreach($menu_brand as $item)
                                                    <option value="{{ $item->id }}" {{ $data->brand->id == $item->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $item->id }}" {{ $data->gallery_id == $item->id ? 'selected' : '' }}>
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
                                                @include('engine.include.name.edit')
                                                @include('engine.include.sub_name.edit')
                                                @include('engine.include.slug.edit')
                                                @include('engine.include.overview.edit')
                                                @include('engine.include.description.edit')
                                                @include('engine.include.info.edit')
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label for="custom">Custom </em></label>
                                            <input type="text" name="custom"  class="form-control @error('custom') is-invalid @enderror" id="custom" value="{{ $data->custom }}"
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
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                              </div>
                            </div>
                              <div class="collapse" id="mycard-collapse">
                            
                                <div class="card-body row">
                                    
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
    <script>
        // var data = '{{ $data->translations }}';
        // var data = JSON.parse(data);
        // console.log( data);

        // // var data = JSON.parse(data);
        // // select name
        // var description = $('[name="description[]"]');
        // description.each(function(index, value){
        //     $(this).val(data[index]);
        // });
    </script>

@endsection