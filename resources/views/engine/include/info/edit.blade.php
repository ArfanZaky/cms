<div class="form-group">
    <label for="name">Info </label>
    <input type="text" name="info[]" class="form-control tinymce_plugins_info  @error('info.'.$i) is-invalid @enderror" id="info"
    placeholder="info"  value="{{ ($data->translations[$i]->info) ?? '' }}">
</div>

@error('info.'.$i)
    <div class="alert alert-danger">{{ $errors->first('info.'.$i) }}</div>
@enderror