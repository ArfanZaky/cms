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
            <h1>Page Builder</h1>
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
                    <form action="{{ route('page.generic.builder.store', $data->id) }}" method="POST" novalidate
                        class="needs-validation" enctype="multipart/form-data" id="submit-builder">
                        @csrf
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Page Form</h4>
                                </div>

                                <div class="card-body">
                                    <ul class="nav nav-pills  justify-content-center" id="myTab3" role="tablist">
                                        {!! languageSettings() !!}
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <?php
                                        $array_languages = languages();
                                        $languages = config('app.language_setting');
                                        ?>
                                        <?php
                                        for($i=0; $i < $languages; $i++){ ?>
                                            <div class="tab-pane fade show {{ $i == 0 ? 'active' : '' }}"
                                                id="{{ $array_languages[$i] }}" role="tabpanel"
                                                aria-labelledby="{{ $array_languages[$i] }}">
                                                
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="name">Name </label>
                                                        <input type="text" name="name[]"  disabled class="form-control @error('name.'.$i) is-invalid @enderror" id="name"
                                                            placeholder="Name" required value="{{ ($data->translations[$i]->name) ?? '' }}">
                                                    </div>
                                                    @error('name.'.$i)
                                                        <div class="alert alert-danger">{{ $errors->first('name.'.$i) }}</div>
                                                    @enderror
                                                </div>
                                                @include('engine.include.builder.index')

                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="card-footer" style="text-align: end;">
                                    <input type="submit" value="{!! __('Submit & Preview') !!}" name="save" class="btn btn-primary submit-builder">
                        
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
            editor_config_builder('#tinymce_plugins_builder');
            let counter = 1;
            function addSECTION(index) {

                var number = $(".form-card-" + index).length + 1;
                let sku = [];
                var html = '<div class="card mb-3 form-number-' + number + ' form-card-' + index + '">' +
                    '<div class="card-header  p-3">' +
                    '<div class="card-title w-100">' +
                    '<div class="mb-3">' +
                    '<div class="d-flex align-items-center gap-2">' +
                    '<h2 class="btn btn-dark label-number-' + number + ' label-index-' + index + '">' +
                    '{!! __("Section '+number+'") !!}' +
                    '</h2>' +
                    '<button data-id="' + number +
                    '" type="button" class="btn btn-danger btn-icon remove  end-0 m-2 top-0">' +
                    '<i class="fa fa-trash"></i>' +
                    '</button>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-4 border-end">' +
                    '<div class="input-group align-items-center gap-2">' +
                    //   '<i class="fa fa-cubes"></i>' +
                    '<label for="type" class="text-secondary">' +
                    '{!! __('Type') !!}' +
                    '</label>' +
                    '</div>' +
                    '<select name="type[' + index + '][]" class="form-control type-trigg type-' + number +
                    '" required data-number="' + number + '" data-index="' + index + '">' +
                    '<option value="">-- Pilih --</option>' +
                    '<option value="CUSTOM">Custom</option>' +
                    '<option value="TEMPLATE">Template</option>';
                html += '</select>' +
                    '</div>' +
                    '<div class="col-4 border-end">' +
                    '<div class="input-group align-items-center gap-2">' +
                    //   '<i class="fa fa-grip-horizontal"></i>' +
                    '<label  class="text-secondary">' +
                    '{!! __('Category') !!}' +
                    '</label>' +
                    '</div>' +
                    '<select name="category_id[' + index + '][]" data-number="' + number +
                    '" class="form-control category_id-' + number + '-' + index + '" >' +
                    '<option value="0">-- Pilih --</option>';


                let categories = @json($categories);
                $.each(categories, function(key, value) {
                    html += '<option disabled value="' + key + '">' + key + '</option>';
                    $.each(value, function(key, value) {
                        html += '<option value="' + value.model_id + '-' + value.model_type + '">' +
                            '&nbsp;&nbsp;&nbsp;&nbsp;' +
                            value
                            .name +
                            '</option>';
                    });
                });

                html += '</select>' +
                    '</div>' +
                    '<div class="col-4">' +
                    '<div class="input-group align-items-center gap-2">' +
                    //   '<i class="fa fa-money-bill"></i>' +
                    '<label  class="text-secondary">' +
                    '{!! __('Template') !!}' +
                    '</label>' +
                    '</div>' +
                    '<select name="template[' + index + '][]" data-number="' + number +
                    '" class="form-control  template-' + number + '-' + index + '" >' +
                    '<option value="0">-- Pilih --</option>';
                let templates = @json($templates);
                $.each(templates, function(key, value) {
                    html += '<option value="' + value.id + '">' + value.code + '</option>';
                });
                html += '</select>' +
                    '</div>';
                html += '<div class="mt-3 CUSTOM-STEP-' + number + '-' + index + ' col-12" style="display:none">' +
                    '<div class="d-flex input-group align-items-center justify-content-end gap-2">' +
                    '<button type="button" class="btn btn-info add-column" data-number="' + number +
                    '" data-index="' + index +
                    '">' +
                    '<i class="fa fa-plus"></i> {!! __('Add column') !!}' +
                    '</button>' +
                    '</div>' +
                    '<div class="row des-index-' + index + ' m-0 description_row_' + number + '-' + index + '">' +
                        '<textarea hidden class="temp-des-' + number + '-' + index + '" name="description[' +
                    index + '][' + (
                        number - 1) + '][]" value=""></textarea>' +
                    // '<input type="hidden" class="temp-des-' + number + '-' + index + '" name="description[' +
                    // index + '][' + (
                    //     number - 1) + '][]" value="">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                $('.data-idx-' + index).append(html);
            }

            function typeTRIGG(val) {
                let number = $(val).data('number');
                let index = $(val).data('index');
                if ($(val).val() == 'CUSTOM') {
                    let template = $('.template-' + number + '-' + index).prop('disabled', true);
                    let category = $('.category_id-' + number + '-' + index).attr('disabled', true);
                    $('.CUSTOM-STEP-' + number + '-' + index).show();
                } else {
                    let template = $('.template-' + number + '-' + index).prop('disabled', false);
                    let category = $('.category_id-' + number + '-' + index).attr('disabled', false);
                    $('.CUSTOM-STEP-' + number + '-' + index).hide();
                    $('.temp-des-' + number + '-' + index).prop('disabled', false);
                }
            }

            function columnTRIGG(val) {
                let exist = $(val).data('exist');
                let number = $(val).data('number');
                let index = $(val).data('index');
                $('.temp-des-' + number + '-' + index).prop('disabled', true);
                var lenght = $('.description_row_' + number + '-' + index + ' .flex-grow-1').length;
                html = '<div class="flex-grow-1 w-25 p-1 remove-this">' +
                    '<div class="form-group mce-app position-relative pr-3 pt-3">' +
                    '<div class="mb-3 position-absolute" style="top: 0; right: 0;z-index: 3;">' +
                    '<button type="button" class="btn btn-danger remove-column" data-number="' + number +
                    '" data-index="' +
                    index + '">' +
                    '<i class="fa fa-minus"></i>' +
                    '</button>' +
                    '</div>' +
                    '<textarea  name="description[' + index + '][' + (number - (exist == 1 ? 0 : 1)) +
                    '][]" id="tinymce_plugins_builder_' + counter +
                    '" class="form-control" ></textarea>' +
                    '</div>' +
                    '</div>';
                $('.description_row_' + number + '-' + index).append(html);

            }

            function renameSection() {
                $('.form-page').each(function(i, obj) {
                    $('.label-index-' + i).each(function(index, obj) {
                        $(this).html('Section ' + (index + 1));
                    });

                    $('.des-index-' + i).each(function(indexs, obj) {
                        $(this).find('textarea').each(function(key, obj) {
                            $(this).attr('name', 'description[' + i + '][' + indexs + '][]');
                        });
                    });
                });

            }

            function reinitTinyMCE() {
                $('.mce-app').each(function() {
                    $(this).find('.tox').each(function(index, element) {
                        if (index == 0) {
                            $(this).show();
                        } else {
                            $(this).remove();
                        }
                    });
                });
            }
            $('.add').click(function() {
                $('.form-page').each(function(i, obj) {
                    addSECTION(i);
                });
                renameSection();
                $('.type-trigg').on('change', function() {
                    typeTRIGG(this);
                });
            });

            $(document).on('click', '.add-column', function() {
                console.log('add column');
                columnTRIGG(this);
                editor_config_builder('#tinymce_plugins_builder_' + counter);
                // reinitTinyMCE();
                $('.remove-column').on('click', function() {
                    $(this).parent().parent().parent().remove();
                });
                counter++;

            });
            $('.remove-column').on('click', function() {
                $(this).parent().parent().parent().remove();
            });

            $(document).on('click', '.remove', function() {
                let id = $(this).data('id');
                $('.form-number-' + id).each(function(i, obj) {
                    $(this).remove();
                });
                renameSection();
            });
            $('#submit-builder').on('submit', function() {
                $('select').prop('disabled', false);
                return true;
            });
        });
    </script>
@endsection

    