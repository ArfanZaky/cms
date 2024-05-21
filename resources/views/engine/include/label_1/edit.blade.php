<div class="form-group">
    <label for="label_1">Label </label>
    <input type="text" name="label_1[]" class="form-control  @error('label_1.'.$i) is-invalid @enderror" id="label_1"
        placeholder="Label"  value="{{ ($data->translations[$i]->label_1) ?? '' }}">
</div>
@error('label_1.'.$i)
    <div class="alert alert-danger">{{ $errors->first('label_1.'.$i) }}</div>
@enderror