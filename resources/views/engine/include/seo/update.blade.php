<div class="form-group">
    <label>Meta Title : </label>
    <input name="meta_title[]" type="text" class="form-control input-sm @error('meta_title.'.$i) is-invalid @enderror" value="{{ ($data->translations[$i]->meta_title) ?? "" }}">
</div>

<div class="form-group">
    <label>Meta Keyword : </label>
    <input name="meta_keyword[]" type="text" class="form-control input-sm @error('meta_keyword.'.$i) is-invalid @enderror" data-role="tagsinput" value="{{ ($data->translations[$i]->meta_keyword) ?? "" }}">
</div>

<div class="form-group">
    <label>Meta Description :</label>
    <textarea name="meta_description[]"  class="form-control input-sm h-50 @error('meta_description.'.$i) is-invalid @enderror" rows="10">{!!  ($data->translations[$i]->meta_description) ?? "" !!}</textarea>
</div>
