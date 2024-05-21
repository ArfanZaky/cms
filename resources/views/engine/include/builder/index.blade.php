<div class="card-body">
    <div class="card-header">
        <div class="card-options">
            <button type="button" class="btn btn-primary add">
                <i class="fa fa-plus"></i> {!! __('Add Section') !!}
            </button>
        </div>
    </div>
    <div class="form-page data-idx-{{ $i }}">
        @if (!empty($builder))
            @foreach ($builder[$i + 1] as $key => $values)
                <div class="card mb-3 form-number-{{ $key }} form-card-{{ $i }}">
                    <div class="card-header p-3">
                        <div class="card-title w-100">
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <h2
                                        class="btn btn-dark label-number-{{ $key }} label-index-{{ $i }}">
                                        {!! __('Section '.$key + 1) !!}
                                    </h2>
                                    <button data-id="{{ $key }}" type="button"
                                        class="btn btn-danger btn-icon remove end-0 m-2 top-0">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-4 border-end">
                                        <div class="input-group align-items-center gap-2">
                                            <label for="type" class="text-secondary">
                                                {!! __('Type') !!}
                                            </label>
                                        </div>
                                        <select name="type[{{ $i }}][]"
                                            class="form-control type-trigg type-{{ $key }}" required
                                            data-number="{{ $key }}" data-index="{{ $i }}">
                                            <option value="">-- Pilih --</option>
                                            <option value="CUSTOM" {{ $values['type'] == 'CUSTOM' ? 'selected' : '' }}>
                                                Custom
                                            </option>
                                            <option value="TEMPLATE"
                                                {{ $values['type'] == 'TEMPLATE' ? 'selected' : '' }}>
                                                Template
                                            </option>;
                                        </select>
                                    </div>
                                    <div class="col-4 border-end">
                                        <div class="input-group align-items-center gap-2">
                                            <label class="text-secondary">
                                                {!! __('Category') !!}
                                            </label>
                                        </div>
                                        <select name="category_id[{{ $i }}][]"
                                            {{ $values['type'] == 'CUSTOM' ? 'disabled' : '' }}
                                            data-number="{{ $key }}"
                                            class="form-control category_id-{{ $key }}-{{ $i }}">
                                            <option value="0">-- Pilih --</option>
                                            @foreach ($categories as $keyCategory => $value)
                                                <option disabled value="{{ $keyCategory }}">{{ $keyCategory }}
                                                </option>
                                                @foreach ($value as  $value)
                                                    <option value="{{ $value->model_id }}-{{ $value->model_type }}"
                                                        {{ $value->model_id . '-' . $value->model_type == $values['model_id'] . '-' . $values['model_type'] ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $value->name }}</option>
                                                @endforeach
                                            @endforeach



                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group align-items-center gap-2">
                                            <label class="text-secondary">
                                                {!! __('Template') !!}
                                            </label>
                                        </div>
                                        <select name="template[{{ $i }}][]"
                                            data-number="{{ $key }}"
                                            {{ $values['type'] == 'CUSTOM' ? 'disabled' : '' }}
                                            class="form-control  template-{{ $key }}-{{ $i }}">
                                            <option value="0">-- Pilih --</option>
                                            @foreach ($templates as $value)
                                                <option value="{{ $value->id }}"
                                                    {{ $value->id == $values['template_id'] ? 'selected' : '' }}>
                                                    {{ $value->code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mt-3 CUSTOM-STEP-{{ $key }}-{{ $i }} col-12" {{$values['description'][0] == null ? 'style=display:none' : ''}}>
                                        <div class="d-flex input-group align-items-center justify-content-end gap-2">
                                            <button type="button" data-exist="1"  class="btn btn-info add-column"
                                                data-number="{{ $key }}" data-index="{{ $i }}">
                                                <i class="fa fa-plus"></i> {!! __('Add column') !!}
                                            </button>
                                        </div>
                                        <div class="row des-index-{{ $i }} m-0 description_row_{{ $key }}-{{ $i }}">
                                            @foreach ($values['description'] as $keys => $value)
                                                <div class="flex-grow-1 w-25 p-1 remove-this">
                                                    <div class="form-group mce-app position-relative pr-3 pt-3">
                                                        <div class="mb-3 position-absolute"
                                                            style="top: 0; right: 0;z-index: 3;">
                                                            <button type="button" class="btn btn-danger remove-column" data-number="{{ $key }}" data-index="{{ $i }}" >
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                        <textarea name="description[{{ $i }}][{{ ($key) }}][]" id="tinymce_plugins_builder" class="form-control">{{ $value }}</textarea>
                                                    </div>
                                                </div>;
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        @endif
    </div>
</div>
