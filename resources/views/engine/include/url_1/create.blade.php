<div class="form-group">
    <label for="url_1">Url </label>
    <input type="text" name="url_1[]" class="form-control @error('url_1.'.$i) is-invalid @enderror"  id="url_1" value="{{ old('url_1.'.$i) }}" 
        placeholder="Url" >
</div>

@error('url_1.'.$i)
    <div class="alert alert-danger">{{ $errors->first('url_1.'.$i) }}</div>
@enderror