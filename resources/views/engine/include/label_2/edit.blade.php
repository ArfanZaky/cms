<div class="form-group">
    <label for="label_2">Label </label>
    <input type="text" name="label_2[]" class="form-control  @error('label_2.'.$i) is-invalid @enderror" id="label_2"
        placeholder="Label"  value="{{ ($data->translations[$i]->label_2) ?? '' }}">
</div>
@error('label_2.'.$i)
    <div class="alert alert-danger">{{ $errors->first('label_2.'.$i) }}</div>
@enderror