<div class="form-group">
    <label for="name">Name <em class="text-danger">*required</em></label>
    <input type="text" name="name[]" class="form-control  @error('name.'.$i) is-invalid @enderror" id="name"
        placeholder="Name" required value="{{ ($data->translations[$i]->name) ?? '' }}">
</div>

@error('name.'.$i)
    <div class="alert alert-danger">{{ $errors->first('name.'.$i) }}</div>
@enderror

<div class="form-group">
    <label for="name">Description </label>
    <textarea  name="description[]" id="description_{{$i}}" class="form-control tinymce_plugins @error('description.'.$i) is-invalid @enderror" id="description" >{!! ($data->translations[$i]->description) ?? "" !!}</textarea>
</div>

@error('description.'.$i)
    <div class="alert alert-danger">{{ $errors->first('description.'.$i) }}</div>
@enderror
