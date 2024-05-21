<div class="form-group">
    <label for="name">Name <em class="text-danger">*required</em></label>
    <input type="text" name="name[]" class="form-control @error('name.'.$i) is-invalid @enderror"  id="name" value="{{ old('name.'.$i) }}" 
        placeholder="Name" required>
</div>

@error('name.'.$i)
    <div class="alert alert-danger">{{ $errors->first('name.'.$i) }}</div>
@enderror