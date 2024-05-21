<div class="form-group">
    <label for="label_1">Label</label>
    <input type="text" name="label_1[]" class="form-control @error('label_1.'.$i) is-invalid @enderror"  id="label_1" value="{{ $i == 0 ? 'Learn more' : 'Lihat selengkapnya' }}" 
        placeholder="Label" >
</div>

@error('label_1.'.$i)
    <div class="alert alert-danger">{{ $errors->first('label_1.'.$i) }}</div>
@enderror