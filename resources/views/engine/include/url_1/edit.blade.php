<div class="form-group">
    <label for="url_1">Url </label>
    <input type="text" name="url_1[]" class="form-control  @error('url_1.'.$i) is-invalid @enderror" id="url_1"
        placeholder="Url"  value="{{ ($data->translations[$i]->url_1) ?? '' }}">
</div>
@error('url_1.'.$i)
    <div class="alert alert-danger">{{ $errors->first('url_1.'.$i) }}</div>
@enderror