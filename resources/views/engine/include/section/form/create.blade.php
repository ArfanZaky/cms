
<p>Section </p>
<div class="form-group">
    <label for="name">Name <em class="text-danger">*required</em></label>
    <input type="text" name="section_name[{{$lang}}][]" class="form-control @error('name.'.$i) is-invalid @enderror"  id="name" value="{{ old('name.'.$i) }}"
        placeholder="Name" required>
</div>

@error('name.'.$i)
    <div class="alert alert-danger">{{ $errors->first('name.'.$i) }}</div>
@enderror

<div class="form-group">
    <label for="name">Description </label>
    <textarea  name="section_description[{{$lang}}][]" id="section_description_{{$i}}" class="form-control tinymce_plugins{{$i}} @error('section_description.'.$i) is-invalid @enderror" >{!! old('section_description.'.$i) !!}</textarea>
</div>
@error('section_description.'.$i)
    <div class="alert alert-danger">{{ $errors->first('section_description.'.$i) }}</div>
@enderror
