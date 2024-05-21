<div class="form-group">
    <label for="url_2">Second Url </label>
    <input type="text" name="url_2[]" class="form-control @error('url_2.'.$i) is-invalid @enderror"  id="url_2" value="{{ old('url_2.'.$i) }}" 
        placeholder="Second Url" >
</div>

@error('url_2.'.$i)
    <div class="alert alert-danger">{{ $errors->first('url_2.'.$i) }}</div>
@enderror