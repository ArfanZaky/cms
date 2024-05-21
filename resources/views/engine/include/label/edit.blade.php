<div class="form-group">
    <label for="label">Label </label>
    <input type="text" name="label[]" class="form-control  @error('label.'.$i) is-invalid @enderror" id="label"
        placeholder="Label"  value="{{ ($data->translations[$i]->label) ?? '' }}">
</div>
@error('label.'.$i)
    <div class="alert alert-danger">{{ $errors->first('label.'.$i) }}</div>
@enderror