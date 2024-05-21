<div class="form-group">
    <label for="redirection">Redirect Url </label>
    <input type="text" name="redirection[]" class="form-control  @error('redirection.'.$i) is-invalid @enderror" id="redirection"
        placeholder="Url"  value="{{ ($data->translations[$i]->redirection) ?? '' }}">
</div>
@error('redirection.'.$i)
    <div class="alert alert-danger">{{ $errors->first('redirection.'.$i) }}</div>
@enderror