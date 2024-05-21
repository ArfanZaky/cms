<div class="form-group">
    <label for="sub_name">Sub Name </label>
    <input type="text" name="sub_name[]" class="form-control @error('sub_name.'.$i) is-invalid @enderror"  id="sub_name" value="{{ old('sub_name.'.$i) }}" 
        placeholder="Sub name" >
</div>

@error('sub_name.'.$i)
    <div class="alert alert-danger">{{ $errors->first('sub_name.'.$i) }}</div>
@enderror