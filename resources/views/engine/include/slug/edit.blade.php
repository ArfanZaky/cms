<div class="form-group">
    <label for="name">Slug <em class="text-danger">*required</em></label>
    <input type="text" name="slug[]" class="form-control  @error('slug.'.$i) is-invalid @enderror" id="slug"
        placeholder="Slug" onkeydown="return /[0-9a-zA-Z-]/i.test(event.key)" required value="{{ ($data->translations[$i]->slug) ?? "" }}">
</div>

@error('slug.'.$i)
    <div class="alert alert-danger">{{ $errors->first('slug.'.$i) }}</div>
@enderror