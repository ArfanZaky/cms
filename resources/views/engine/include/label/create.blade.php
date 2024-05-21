<div class="form-group">
    <label for="label">Label </label>
    <input type="text" name="label[]" class="form-control @error('label.'.$i) is-invalid @enderror"  id="label" value="{{ $i == 0 ? 'Learn more' : 'Lihat selengkapnya' }}"
        placeholder="Label" >
</div>

@error('label.'.$i)
    <div class="alert alert-danger">{{ $errors->first('label.'.$i) }}</div>
@enderror