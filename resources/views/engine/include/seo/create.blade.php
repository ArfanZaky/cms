<div class="form-group">
    <label>Meta Title : </label>
    <input name="meta_title[]" type="text" class="form-control input-sm @error('meta_title.'.$i) is-invalid @enderror" value="{{ old('meta_title.'.$i) }}">
</div>

<div class="form-group">
    <label>Meta Keyword : </label>
    <input name="meta_keyword[]" type="text" class="form-control input-sm @error('meta_keyword.'.$i) is-invalid @enderror" data-role="tagsinput" value="{{ old('meta_keyword.'.$i) }}">
</div>

<div class="form-group">
    <label>Meta Description :</label>
    <textarea name="meta_description[]"  class="form-control input-sm h-50 @error('meta_description.'.$i) is-invalid @enderror" rows="10">{{ old('meta_description.'.$i) }}</textarea>
</div>
