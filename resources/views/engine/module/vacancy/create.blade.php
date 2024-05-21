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
    textarea {  height: 150px !important; }
</style>
    <section class="section">
        <div class="section-header">
            <h1>Vacancy Management</h1>
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
                    <form action="{{ route('vacancy.store') }}" method="POST" novalidate  class="needs-validation" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Vacancy Form</h4>
                                </div>
                               
                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="form-group">
                                            <label for="category_id">Category :<em class="text-danger">*required</em></label>
                                            <select name="category_id" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
                                                <option value="">- Root Category</option>
                                                @foreach($vacancy as $vacancies)
                                                    <option value="{{ $vacancies->id }}">
                                                    {!! $vacancies->translations[0]->name !!}
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
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" id="city" name="city" required class="form-control" value="{{ old('city') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="salary">Salary </label>
                                            <input type="text" id="salary" name="salary" required class="form-control" value="{{ old('salary') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="experience">Experience </label>
                                            <textarea name="experience" id="experience" required class="form-control" cols="30" rows="10">{{ old('experience') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="position">Position </label>
                                            <input type="text" id="position" name="position" required class="form-control" value="{{ old('position') }}">
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