<div class="form-group">
    <label for="name">Description </label>
    <textarea  name="description[]" id="description_{{$i}}" class="form-control tinymce_plugins @error('description.'.$i) is-invalid @enderror" id="description" >{!! ($data->translations[$i]->description) ?? "" !!}</textarea>
</div>

@error('description.'.$i)
    <div class="alert alert-danger">{{ $errors->first('description.'.$i) }}</div>
@enderror