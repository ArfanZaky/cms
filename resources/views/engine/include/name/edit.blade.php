<div class="form-group">
    <label for="name">Name <em class="text-danger">*required</em></label>
    <input type="text" name="name[]" class="form-control  @error('name.'.$i) is-invalid @enderror" id="name"
        placeholder="Name" required value="{{ ($data->translations[$i]->name) ?? '' }}">
</div>
@error('name.'.$i)
    <div class="alert alert-danger">{{ $errors->first('name.'.$i) }}</div>
@enderror