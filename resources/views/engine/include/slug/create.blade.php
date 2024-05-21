<div class="form-group">
    <label for="name">Slug <em class="text-danger">*required</em></label>
    <input type="text" name="slug[]" onkeydown="return /[0-9a-zA-Z-]/i.test(event.key)" class="form-control @error('slug.'.$i) is-invalid @enderror" id="slug" value="{{ old('slug.'.$i) }}"
        placeholder="Slug" required>
</div>
@error('slug.'.$i)
    <div class="alert alert-danger">{{ $errors->first('slug.'.$i) }}</div>
@enderror