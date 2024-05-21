<div class="form-group">
    <label for="name">Overview</label>
    <textarea type="text" name="overview[]" id="overview{{$i}}" class="form-control tinymce_plugins_2 @error('overview.'.$i) is-invalid @enderror" id="overview" >{!! ($data->translations[$i]->overview) ?? "" !!}</textarea>
</div>

@error('overview.'.$i)
    <div class="alert alert-danger">{{ $errors->first('overview.'.$i) }}</div>
@enderror